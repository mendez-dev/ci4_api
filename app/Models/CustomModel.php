<?php

/**
 * This file is part of the SYSTEM_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Models;

use Michalsn\Uuid\UuidModel;

/**
 * Modelo `CustomModel`
 *
 * Contiene funciones que seran heredadas por otros modelos
 *
 * @package  API_CI4
 * @category Model
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class CustomModel extends UuidModel
{
    // Los UUID se almacenaran como strings
    protected $uuidUseBytes   = false;

    /**
     * Se encarga de aplicar los filtros segun los parámetros de busqueda
     * enviados.
     *
     * @param array $filters arreglo relacional con los parámetros de busqueda
     * @param bool $strict indica si la busqueda sera un like o where
     * @return void
     */
    public function filterArray(array $filters, bool $strict = false, bool $verify_allowed_fields = true): void
    {

        // Eliminamos del arreglo los campos que no estan permitidos
        if ($verify_allowed_fields) {
            $filters = array_intersect_key($filters, array_flip($this->allowedFields));
        }

        // Si el arreglo esta vacio retornamos
        if (empty($filters)) return;

        // Agrupamos las condiciones para que no interfieran con el WHERE deleted_at IS NULL
        $this->orGroupStart();

        // Iniciamos grupo de filtros
        // Verificamos que los campos a filtrar existan en el modelo
        foreach ($filters as $key => $value) {

            if ($strict) {
                // Si la busqueda es estricta usamos where
                $this->where($key, $value);
            } else {
                // Si la busqueda no es estricta usamos orLike
                if ($value === "true") {
                    $value = 1;
                } else if ($value === "false") {
                    $value = 0;
                }
                $this->OrLike($key, $value);
            }
        }

        // Cerramos grupo de filtros
        $this->groupEnd();
    }

    /**
     * Filtra por medio de un arreglo con parámetros de busqueda,
     * 
     * Recibe un arreglo relacional donde los `key` son los nombres de las
     * columnas que se encuentren en `$this->allowedFields` y el `value` el
     * parametro de busqueda, si el `key` no se encuentra dentro de
     * `$this->allowedFields` se omite el campo y se elimina del arreglo.
     * 
     * Se debe indicar si el filtro será estricto o no, por defecto será no
     * estricto, por lo cual devolverá resultados que coincidan con los
     * parámetros de busqueda pero que no sean exactos, si el filtro es estricto
     * se buscarán resultados que coincidan exactamente con todos los
     * parámetros de busqueda.
     * 
     * Retorna todos los resultados encontrados.
     *
     * @param array $filters arreglo relacional con los parámetros de busqueda
     * @param bool $strict indica si la busqueda sera un like o where
     * @return array
     */
    public function filterAll(array $filters, bool $strict = false): array
    {
        // Aplicamos las condiciones a la busqueda
        $this->filterArray($filters, $strict);

        // Retornamos todos los resultados
        return $this->findAll();
    }

    /**
     * Filtra por medio de un arreglo con parámetros de busqueda, 
     * 
     * Recibe un arreglo relacional donde los `key` son los nombres de las
     * columnas que se encuentren en `$this->allowedFields` y el `value` el
     * parametro de busqueda, si el `key` no se encuentra dentro de
     * `$this->allowedFields` se omite el campo y se elimina del arreglo.
     * 
     * Se debe indicar si el filtro será estricto o no, por defecto será no
     * estricto, por lo cual devolverá resultados que coincidan con los
     * parámetros de busqueda pero que no sean exactos, si el filtro es estricto
     * se buscarán resultados que coincidan exactamente con todos los
     * parámetros de busqueda.
     * 
     * Retorna solo el primer resultado encontrado.
     *
     * @param array $filters arreglo relacional con los parámetros de busqueda
     * @param bool $strict indica si la busqueda sera un like o where
     * @return array|object|null
     */
    public function filterOne(array $filters, bool $strict = false)
    {
        // Aplicamos las condiciones a la busqueda
        $this->filterArray($filters, $strict);

        // Retornamos todos los resultados
        return $this->first();
    }

    /**
     * Retorna datos paginados con la información del total de paginas
     *
     * @param integer $page numero de página solicitada
     * @param integer $records_per_page total de registros por página
     * @return array
     */
    public function getPagination(int $page = 1, int $records_per_page = RECORDS_PER_PAGE): array
    {

        $paginate = [
            "data"         => $this->orderBy("created_at", "ASC")->paginate($records_per_page, "default", $page),
            'current_page' => $page,
            'total_pages'  => $this->pager->getPageCount(),

        ];

        // Verificamos que la pagina solicitada no exceda el limite
        if ($paginate['current_page'] > $paginate['total_pages']) {
            $paginate['data'] = [];
        }

        return $paginate;
    }

    /**
     * Realiza una consulta de datos para retornarlos directamente en la api
     * 
     * Permite retornar datos paginados o retornar el listado de todos los registros,
     * tambien es capas de aplicar filtros por medio de los [allowedFields] de la tabla
     * por defecto retorna los datos ordenados por su fecha de creacion `created_at` pero
     * tambien se pueden ordenar por cualquiera de los [allowedFields]
     *
     * @param array $query_params el formato del arreglo debe ser el siguiete
     *   [
     *   'filters' => ['filed' => 'value],
     *   'page' => 1,
     *   'records_per_page' => 10,
     *   'sort_by' => 'created_at',
     *   'order_by' => 'ASC'
     * ]
     * @return array
     */
    public function getData(array $query_params, array $extra_filters = []): array
    {

        // Agrupamos condiciones para que no interfieran con el WHERE deleted_at IS NULL
        if (!empty($query_params['filters']) && !empty($extra_filters)) {
            $this->orGroupStart();
        }
        // Aplicamos las condiciones a la busqueda
        $this->filterArray($query_params['filters']);
        // Aplicamos los filtros extra
        $this->filterArray($extra_filters, false, false);
        // Agrupamos condiciones para que no interfieran con el WHERE deleted_at IS NULL
        if (!empty($query_params['filters']) && !empty($extra_filters)) {
            $this->GroupEnd();
        }

        // Ordenamos los resultados de la búsqueda
        $sort_by = "created_at";
        $order_by = "ASC";

        if (null != $query_params["sort_by"]) {
            if (in_array($query_params["sort_by"], $this->allowedFields)) {
                $sort_by = $query_params["sort_by"];
            }
        }

        if (null != $query_params["order_by"]) {
            if (
                $query_params["order_by"] == "asc"
                || $query_params["order_by"] == "ASC"
                || $query_params["order_by"] == "desc"
                || $query_params["order_by"] == "DESC"
            ) {
                $order_by = $query_params["order_by"];
            }
        }

        $this->orderBy($sort_by, $order_by);



        if ($query_params["page"]) {
            $data["response"] = $this->getPagination($query_params["page"], $query_params["records_per_page"]);
            if (!empty($data["response"]["data"])) {
                $data["code"] = 200;
                return $data;
            }
        } else {
            $data["response"] = $this->findAll();
            if (!empty($data["response"])) {
                $data["code"] = 200;
                return $data;
            }
        }

        return [
            "response" => ["errors" => ['No se encontraron registros']],
            "code" => 404
        ];
    }
}

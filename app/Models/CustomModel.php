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

use CodeIgniter\Model;

/**
 * Modelo `CustomModel`
 *
 * Contiene funciones que seran heredadas por otros modelos
 *
 * @package  API_CI4
 * @category Model
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class CustomModel extends Model
{

    /**
     * Funcion interna del modelo que se encarga de aplicar los filtros segun
     * los parámetros de busqueda enviados.
     *
     * @param array $filters arreglo relacional con los parámetros de busqueda
     * @param bool $strict indica si la busqueda sera un like o where
     * @return void
     */
    private function filterArray(array $filters, bool $strict = false) : void
    {
        
        // Verificamos que los campos a filtrar existan en el modelo
        foreach ($filters as $key => $value) {
            if (!in_array($key, $this->allowedFields)) {
                unset($filters[$key]);
            }else{
                if ($strict) {
                    // Si la busqueda es estricta usamos where
                    $this->where($key, $value);
                } else {
                    // Si la busqueda no es estricta usamos orLike
                    $this->orLike($key, $value);
                }
            }
        }

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
    public function filterAll(array $filters, bool $strict = false) : array
    {
        // Aplicamos las condiciones a la busqueda
        $this->filterArray( $filters, $strict);

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
        $this->filterArray( $filters, $strict);

        // Retornamos todos los resultados
        return $this->first();
    }
}

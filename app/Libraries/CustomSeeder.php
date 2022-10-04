<?php

/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilberdev@gmail.com>
 *
 * For the full copyright and license information, please refer to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Libraries;

use CodeIgniter\Database\Seeder;

class CustomSeeder extends Seeder
{

    /**
     * Obtiene el id del primer usuario registrado en el sistema
     * 
     * De esta forma se puede usar como referencia al momento de crear otros 
     * registros
     *
     * @return string
     */
    protected function getSuperUserId(): string
    {
        // Obtenemos el id del usuario administrador, El primer usuario 
        // registrado
        $first_user = $this->db->table(TBL_USER)
            ->orderBy("created_by", 'ASC')->get()->getRow();
        return $first_user->id_user;
    }

    /**
     * Obtiene el id de un permiso por su nombre
     *
     * @param string $name
     * @return string
     */
    protected function getPermissionIdByName(String $name = "")
    {
        $permissions = $this->db->table(TBL_PERMISSION)
            ->where("name", $name)->get()->getRow();
        return $permissions->id_permission;
    }

    /**
     * Obtiene el id de una ruta por su nombre
     * 
     * @param string $name mombre de la ruta
     * @return string
     */
    protected function getRouteIdByName(String $name = ""): string
    {
        $route = $this->db->table(TBL_ROUTE)
            ->where("name", $name)->get()->getRow();
        return $route->id_route;
    }

    /**
     * Inserta un registro verificando que no exista por medio de un campo
     * 
     * @param string $table_name nombre de la tabla
     * @param array $data datos a insertar
     * @param string $field nombre del campo por el cual se verifica si existe
     */
    protected function insertIfNotExists(
        string $table_name,
        array $data,
        string $field
    ) {
        $exists = $this->db->table($table_name)
            ->where($field, $data[$field])->get()->getRow();
        if (!$exists) {
            $this->db->table($table_name)->insert($data);
        }
    }

    /**
     * Inserta un lote de datos verificando que no existan por medio de un campo
     * 
     * @param string $table_name nombre de la tabla
     * @param array $data datos a insertar
     * @param string $field campo por el cual se verifica si existe
     */
    protected function insertBatchIfNotExists(
        string $table_name,
        array $data,
        string $field
    ) {
        $this->db->transStart();
        foreach ($data as $key => $value) {
            $exists = $this->db->table($table_name)
                ->where($field, $value[$field])
                ->get()->getRow();
            if (!$exists) {
                $this->db->table($table_name)->insert($value);
            }
        }
        $this->db->transComplete();
    }

    /**
     * Insertar lote de permisos de ruta
     * 
     * Verifica que no existan por medio del campo id_route y id_permission y 
     * que no esten borrados
     * 
     * @param array $data
     */
    protected function insertBatchRoutePermission(array $data)
    {
        $this->db->transStart();
        foreach ($data as $key => $value) {
            $exists = $this->db->table(TBL_ROUTE_PERMISSIONS)
                ->where("id_route", $value["id_route"])
                ->where("id_permission", $value["id_permission"])
                ->where("deleted_at", null)
                ->get()->getRow();
            if (!$exists) {
                $this->db->table(TBL_ROUTE_PERMISSIONS)->insert($value);
            }
        }
        $this->db->transComplete();
    }
}

// Constantes con nombres de iconos
define("FA_EYE", "fa-solid fa-eye");
define("FA_CIRCLE_PLUS", "fa-solid fa-circle-plus");
define("FA_PEN_CIRCLE", "fa-solid fa-pen-circle");
define("FA_CIRCLE_TRASH", "fa-solid fa-circle-trash");
define("FA_CIRCLE_CHECK", "fa-solid fa-circle-check");
define("FA_CIRCLE_MINUS", "fa-solid fa-circle-minus");
define("FA_KEY", "fa-solid fa-key");
define("FA_GEAR", "fa-solid fa-gear");
define("FA_USER", "fa-solid fa-user");
define("FA_USERS", "fa-solid fa-users");
define("FA_MOBILE", "fa-solid fa-mobile");

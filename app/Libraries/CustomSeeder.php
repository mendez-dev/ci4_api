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

    protected function getRouteIdByName(String $name = "")
    {
        $menu = $this->db->table(TBL_ROUTE)
            ->where("name", $name)->get()->getRow();
        return $menu->id_menu;
    }

    protected function getPermissionIdByName(String $name = "")
    {
        $permissions = $this->db->table(TBL_PERMISSION)
            ->where("name", $name)->get()->getRow();
        return $permissions->id_permission;
    }

    /**
     * Obtiene el id del primer usuario registrado en el sistema
     * 
     * De esta forma se puede usar como referencia al momento de crear otros registros
     *
     * @return string
     */
    protected function getSuperUserId(): string
    {
        // Obtenemos el id del usuario administrador, El primer usuario registrado
        $first_user = $this->db->table(TBL_USER)->orderBy("created_by", 'ASC')->get()->getRow();
        return $first_user->id_user;
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
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

    protected function getMenuIdByRoute(String $route = "")
    {
        $menu = $this->db->table(TBL_MENU)
            ->where("route", $route)->get()->getRow();
        return $menu->id_menu;
    }

    protected function getPermissionIdByName(String $name = "")
    {
        $permissions = $this->db->table(TBL_PERMISSION)
            ->where("name", $name)->get()->getRow();
        return $permissions->id_permission;
    }
}

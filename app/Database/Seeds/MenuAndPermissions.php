<?php

namespace App\Database\Seeds;

use App\Libraries\CustomSeeder;
use App\Libraries\MigrationUtils;

class MenuAndPermissions extends CustomSeeder
{
    public function run()
    {
        // Inserción de menus y permisos
        $this->call('PermissionsUser');
        $this->call('PermissionsSettings');

        // Asignación de permisos al grupo super administrador
        $this->call('AssignPermissions');
    }
}

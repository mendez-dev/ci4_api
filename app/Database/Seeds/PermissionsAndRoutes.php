<?php

namespace App\Database\Seeds;

use App\Libraries\CustomSeeder;

class PermissionsAndRoutes extends CustomSeeder
{
    public function run()
    {
        // Inserción de permisos
        $this->call('PermissionsUser');
        $this->call('PermissionsSettings');
        $this->call('PermissionsSync');

        // Asignación de permisos al grupo super administrador
        $this->call('AssignPermissions');

        // Inserción de rutas
        $this->call('RoutesSettings');
    }
}

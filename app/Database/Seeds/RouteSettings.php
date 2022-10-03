<?php

namespace App\Database\Seeds;

use App\Libraries\CustomSeeder;

class RouteSettings extends CustomSeeder
{
    public function run()
    {
        // Obtenemos el id del super usuario
        $id_user = $this->getSuperUserId();

        // Definimos los builders que utilizaremos
        $routesBuilder = $this->db->table(TBL_ROUTE);

        // Definimos las rutas principales o padres
        $main_routes = [
            [
                "label"        => "Configuración",
                "name"         => "settings",
                "path"         => "/settings",
                "icon"         => FA_GEAR,
                "priority"     => 10,
                "type"         => "ALL",
                "show_in_menu" => 1,
                "is_active"    => 1,
                "created_by"   => $id_user,
                "updated_by"   => $id_user,
            ],
        ];

        // Insertamos las rutas principales
        $routesBuilder->insertBatch($main_routes);

        // Definimos las rutas hijos
        $routes = [
            [
                "label"        => "Usuarios",
                "name"         => "users",
                "path"         => "/users",
                "icon"         => FA_USER,
                "priority"     => 10,
                "type"         => "ALL",
                "show_in_menu" => 1,
                "is_active"    => 1,
                "created_by"   => $id_user,
                "updated_by"   => $id_user,
            ],
        ];
    }
}

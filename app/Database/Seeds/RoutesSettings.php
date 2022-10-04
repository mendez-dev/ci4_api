<?php

namespace App\Database\Seeds;

use App\Libraries\CustomSeeder;

class RoutesSettings extends CustomSeeder
{
    public function run()
    {
        // Obtenemos el id del super usuario
        $id_user = $this->getSuperUserId();

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
        $this->insertBatchIfNotExists(TBL_ROUTE, $main_routes, "name");

        // Definimos las rutas hijos
        $routes = [
            [
                "id_parent_route" => $this->getRouteIdByName("settings"),
                "label"        => "Usuarios",
                "name"         => "users",
                "path"         => "/users",
                "icon"         => FA_USER,
                "priority"     => 1,
                "type"         => "ALL",
                "show_in_menu" => 1,
                "is_active"    => 1,
                "created_by"   => $id_user,
                "updated_by"   => $id_user,
            ],
            [
                "id_parent_route" => $this->getRouteIdByName("settings"),
                "label"        => "Grupos",
                "name"         => "groups",
                "path"         => "/groups",
                "icon"         => FA_USERS,
                "priority"     => 2,
                "type"         => "ALL",
                "show_in_menu" => 1,
                "is_active"    => 1,
                "created_by"   => $id_user,
                "updated_by"   => $id_user,
            ],
            [
                "id_parent_route" => $this->getRouteIdByName("groups"),
                "label"        => "Asignar permisos a grupo",
                "name"         => "groups-assign-permissions",
                "path"         => "/groups-assign-permissions",
                "icon"         => FA_SHIELD,
                "priority"     => 1,
                "type"         => "ALL",
                "show_in_menu" => 0,
                "is_active"    => 1,
                "created_by"   => $id_user,
                "updated_by"   => $id_user,
            ],
            [
                "id_parent_route" => $this->getRouteIdByName("settings"),
                "label"        => "Ajustes de la aplicación",
                "name"         => "app-settings",
                "path"         => "/app-settings",
                "icon"         => FA_MOBILE,
                "priority"     => 2,
                "type"         => "ALL",
                "show_in_menu" => 1,
                "is_active"    => 1,
                "created_by"   => $id_user,
                "updated_by"   => $id_user,
            ],

        ];

        // Insertamos las rutas hijos
        $this->insertBatchIfNotExists(TBL_ROUTE, $routes, "name");

        // Definimos los permisos para las rutas
        $permissions = [
            [
                "id_route"      => $this->getRouteIdByName("users"),
                "id_permission" => $this->getPermissionIdByName("USER_READ"),
                "created_by"    => $id_user,
                "updated_by"    => $id_user,
            ],
            [
                "id_route"      => $this->getRouteIdByName("groups"),
                "id_permission" => $this->getPermissionIdByName("USER_GROUP_READ"),
                "created_by"    => $id_user,
                "updated_by"    => $id_user,
            ],
            [
                "id_route"      => $this->getRouteIdByName("app-settings"),
                "id_permission" => $this->getPermissionIdByName("APP_SETTINGS_READ"),
                "created_by"    => $id_user,
                "updated_by"    => $id_user,
            ],

            
        ];

        // Insertamos los permisos para las rutas
        $this->insertBatchRoutePermission($permissions);
    }
}

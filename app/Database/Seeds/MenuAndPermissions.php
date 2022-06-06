<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuAndPermissions extends Seeder
{
    protected $app_user_table = "app_user";
    protected $app_menu_table = "app_menu";
    protected $app_permission_table = "app_permission";
    protected $app_group_table = "app_group";
    protected $app_group_permission_table = "app_group_permission";

    public function run()
    {
        // Obtenemos el id del usuario administrador, El primer usuario registrado
        $first_user    = $this->db->table($this->app_user_table)->get()->getRow();
        $first_user_id = $first_user->id_app_user;

        // Obtenemos el id del grupo super administrador, El primer grupo registrado
        $first_group    = $this->db->table($this->app_group_table)->get()->getRow();
        $first_group_id = $first_group->id_app_group;

        // Definimos los builders que utilizaremos
        $menuBuilder            = $this->db->table($this->app_menu_table);
        $permissionsBuilder     = $this->db->table($this->app_permission_table);
        $groupPermissionBuilder = $this->db->table($this->app_group_permission_table);


        // Insertamos los registros del menu -----------------------------------
        $menu = [
            [
                "label"      => "Usuarios",
                "icon"       => "fa-solid fa-users",
                "route"      => "/users",
                "priority"   => "10",
                "is_active"  => "1",
                "created_by" => $first_user_id,
                "updated_by" => $first_user_id,
            ],
            [
                "label"      => "Configuración",
                "icon"       => "fa-solid fa-gears",
                "route"      => "/settings",
                "priority"   => "11",
                "is_active"  => "1",
                "created_by" => $first_user_id,
                "updated_by" => $first_user_id,
            ]
        ];

        // Insertamos el lote de registros del menu
        $menuBuilder->insertBatch($menu);

        // Insertamos los registros de los permisos ----------------------------
        // Arreglos con permisos principales que no dependen de otros
        $main_permissions = [
            [
                "id_menu"     => $this->getMenuIdByRoute("/users"),
                "name"        => "USER_READ",
                "label"       => "Ver usuarios",
                "description" => "Permite ver los usuarios registrados en el sistema.",
                "icon"        => FA_EYE,
                "is_active"   => "1",
                "created_by"  => $first_user_id,
                "updated_by"  => $first_user_id,
            ],
            [
                "id_menu"     => $this->getMenuIdByRoute("/settings"),
                "name"        => "APP_SETTINGS_READ",
                "label"       => "Ver configuración de la aplicación",
                "description" => "Permite ver los ajustes de la aplicación",
                "icon"        => FA_EYE,
                "is_active"   => "1",
                "created_by"  => $first_user_id,
                "updated_by"  => $first_user_id,
            ],
        ];

        // Insertamos los permisos principales
        $permissionsBuilder->insertBatch($main_permissions);

        // Arreglo con permisos que dependen de otros permisos
        $permissions = [
            [
                "id_menu"     => $this->getMenuIdByRoute("/users"),
                "name"        => "USER_CREATE",
                "label"       => "Crear usuario",
                "description" => "Permite registrar nuevos usuarios en el sistema.",
                "icon"        => FA_CIRCLE_PLUS,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $first_user_id,
                "updated_by"  => $first_user_id,
            ],
            [
                "id_menu"     => $this->getMenuIdByRoute("/users"),
                "name"        => "USER_UPDATE",
                "label"       => "Actualizar usuario",
                "description" => "Permite actualizar datos de los usuarios en el sistema.",
                "icon"        => FA_PEN_CIRCLE,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $first_user_id,
                "updated_by"  => $first_user_id,
            ],
            [
                "id_menu"     => $this->getMenuIdByRoute("/users"),
                "name"        => "USER_DELETE",
                "label"       => "Eliminar usuario",
                "description" => "Permite borrar usuarios del sistema.",
                "icon"        => FA_CIRCLE_TRASH,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $first_user_id,
                "updated_by"  => $first_user_id,
            ],
            [
                "id_menu"     => $this->getMenuIdByRoute("/users"),
                "name"        => "USER_ENABLE",
                "label"       => "Activar usuario",
                "description" => "Permite activar usuarios que se encuentren desactivados.",
                "icon"        => FA_CIRCLE_CHECK,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $first_user_id,
                "updated_by"  => $first_user_id,
            ],
            [
                "id_menu"     => $this->getMenuIdByRoute("/users"),
                "name"        => "USER_DISABLE",
                "label"       => "Desactivar usuario",
                "description" => "Permite desactivar usuarios que se encuentren activos.",
                "icon"        => FA_CIRCLE_MINUS,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $first_user_id,
                "updated_by"  => $first_user_id,
            ],
            [
                "id_menu"     => $this->getMenuIdByRoute("/users"),
                "name"        => "USER_PASSWORD",
                "label"       => "Cambiar contraseñas",
                "description" => "Permite cambiar las contraseñas de todos los usuarios del sistema.",
                "icon"        => FA_KEY,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $first_user_id,
                "updated_by"  => $first_user_id,
            ],
            [
                "id_menu"     => $this->getMenuIdByRoute("/settings"),
                "name"        => "APP_SETTINGS_UPDATE",
                "label"       => "Actualizar la configuración de la aplicación",
                "description" => "Permite cambiar la configuración de la aplicación.",
                "icon"        => FA_KEY,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("APP_SETTINGS_READ"),
                "created_by"  => $first_user_id,
                "updated_by"  => $first_user_id,
            ],
        ];

        $permissionsBuilder->insertBatch($permissions);

        // Asignamos todos los permisos al grupo super administrador ----------
        $all_permissions = $permissionsBuilder->get()->getResult();
        foreach ($all_permissions as $permission) {
            $groupPermissionBuilder->insert(
                [
                    "id_group" => $first_group_id,
                    "id_permission" => $permission->id_permission,
                    "created_by" => $first_user_id,
                    "updated_by" => $first_user_id
                ]
            );
        }
    }

    private function getMenuIdByRoute(String $route = "")
    {
        $menu = $this->db->table($this->app_menu_table)
            ->where("route", $route)->get()->getRow();
        return $menu->id_menu;
    }

    private function getPermissionIdByName(String $name = "")
    {
        $permissions = $this->db->table($this->app_permission_table)
            ->where("name", $name)->get()->getRow();
        return $permissions->id_permission;
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

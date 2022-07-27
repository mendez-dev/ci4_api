<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Libraries\MigrationUtils;

class MenuAndPermissions extends Seeder
{
    public function run()
    {
        $migrationUtils = new MigrationUtils();
        $id_user = $migrationUtils->getFirstUserId();
        /**
         * @var Object
         */
        $super_admin_group = $migrationUtils->getFirstRow(TBL_GROUP);
        $id_super_admin_group = $super_admin_group->id_user_group;

        // Definimos los builders que utilizaremos
        $menuBuilder            = $this->db->table(TBL_MENU);
        $permissionsBuilder     = $this->db->table(TBL_PERMISSION);
        $groupPermissionBuilder = $this->db->table(TBL_USER_GROUP_PERMISSION);


        // Insertamos los registros del menu -----------------------------------
        $menu = [
            [
                "label"      => "Usuarios",
                "icon"       => "fa-solid fa-users",
                "route"      => "/users",
                "priority"   => "10",
                "type"       => "WEB",
                "is_active"  => "1",
                "created_by" => $id_user,
                "updated_by" => $id_user,
            ],
            [
                "label"      => "Configuración",
                "icon"       => "fa-solid fa-gears",
                "route"      => "/settings",
                "priority"   => "11",
                "type"       => "WEB",
                "is_active"  => "1",
                "created_by" => $id_user,
                "updated_by" => $id_user,
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
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "id_menu"     => $this->getMenuIdByRoute("/settings"),
                "name"        => "APP_SETTINGS_READ",
                "label"       => "Ver configuración de la aplicación",
                "description" => "Permite ver los ajustes de la aplicación",
                "icon"        => FA_EYE,
                "is_active"   => "1",
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
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
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "id_menu"     => $this->getMenuIdByRoute("/users"),
                "name"        => "USER_UPDATE",
                "label"       => "Actualizar usuario",
                "description" => "Permite actualizar datos de los usuarios en el sistema.",
                "icon"        => FA_PEN_CIRCLE,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "id_menu"     => $this->getMenuIdByRoute("/users"),
                "name"        => "USER_DELETE",
                "label"       => "Eliminar usuario",
                "description" => "Permite borrar usuarios del sistema.",
                "icon"        => FA_CIRCLE_TRASH,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "id_menu"     => $this->getMenuIdByRoute("/users"),
                "name"        => "USER_ENABLE",
                "label"       => "Activar usuario",
                "description" => "Permite activar usuarios que se encuentren desactivados.",
                "icon"        => FA_CIRCLE_CHECK,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "id_menu"     => $this->getMenuIdByRoute("/users"),
                "name"        => "USER_DISABLE",
                "label"       => "Desactivar usuario",
                "description" => "Permite desactivar usuarios que se encuentren activos.",
                "icon"        => FA_CIRCLE_MINUS,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "id_menu"     => $this->getMenuIdByRoute("/users"),
                "name"        => "USER_PASSWORD",
                "label"       => "Cambiar contraseñas",
                "description" => "Permite cambiar las contraseñas de todos los usuarios del sistema.",
                "icon"        => FA_KEY,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("USER_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
            [
                "id_menu"     => $this->getMenuIdByRoute("/settings"),
                "name"        => "APP_SETTINGS_UPDATE",
                "label"       => "Actualizar la configuración de la aplicación",
                "description" => "Permite cambiar la configuración de la aplicación.",
                "icon"        => FA_KEY,
                "is_active"   => "1",
                "depends_on"  => $this->getPermissionIdByName("APP_SETTINGS_READ"),
                "created_by"  => $id_user,
                "updated_by"  => $id_user,
            ],
        ];

        $permissionsBuilder->insertBatch($permissions);

        // Asignamos todos los permisos al grupo super administrador ----------
        $all_permissions = $permissionsBuilder->get()->getResult();
        foreach ($all_permissions as $permission) {
            $groupPermissionBuilder->insert(
                [
                    "id_user_group" => $id_super_admin_group,
                    "id_permission" => $permission->id_permission,
                    "created_by" => $id_user,
                    "updated_by" => $id_user
                ]
            );
        }
    }

    private function getMenuIdByRoute(String $route = "")
    {
        $menu = $this->db->table(TBL_MENU)
            ->where("route", $route)->get()->getRow();
        return $menu->id_menu;
    }

    private function getPermissionIdByName(String $name = "")
    {
        $permissions = $this->db->table(TBL_PERMISSION)
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

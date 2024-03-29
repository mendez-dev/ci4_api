<?php

/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refer to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Models\PermissionModel;

/**
 * Entidad `Group`
 *
 * @package  API_CI4
 * @category Entity
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class Group extends Entity
{
    protected $datamap = [];
    protected $dates   = [];
    protected $casts   = [
        'id_app_group' => 'string',
        'is_active'     => 'integer',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => '?string'
    ];

    /**
     * Asignar nombre
     *
     * Pasa el nombre a mayusculas
     *
     * @param string $name el nombre del grupo
     * @return void
     */
    protected function setName(string $name): void
    {
        $this->attributes['name'] =  strtoupper($name);
    }

    /**
     * Asignar descripción
     *
     * Pasa la descripción a mayúscula
     *
     * @param string $description descripción del grupo
     *
     * @return void
     */
    protected function setDescription(string $description): void
    {
        $this->attributes['description'] =  strtoupper($description);
    }


    /**
     * Retorna el listado de menus a los que tiene permiso
     *
     * @return array
     */
    protected function getMenu()
    {
        if (!empty($this->attributes['id_user_group_permission'])) {
            $menuModel = Model('MenuModel');

            $subquery = $menuModel->db->table("app_permission AS p")->select("p.id_menu")
                ->join("app_group_permission AS gp", "p.id_permission = gp.id_permission")
                ->where("gp.id_group", $this->attributes['id_user_group_permission']);

            return $menuModel->whereIn("id_menu", $subquery)->findAll();
        }
    }

    /**
     * Retorna el listado de permisos
     *
     * @return array
     */
    protected function getPermissions()
    {
        if (!empty($this->attributes['id_user_group'])) {
            $permissionModel = new PermissionModel();

            $subquery = $permissionModel->db->table("user_group_permission AS gp")->select("gp.id_permission")
                ->where("gp.id_user_group", $this->attributes['id_user_group'])
                ->where("deleted_at", null);


            return $permissionModel->whereIn("id_permission", $subquery)->findAll();
        }
    }
}

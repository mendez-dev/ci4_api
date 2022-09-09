<?php

/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Entities;

use CodeIgniter\Entity\Entity;

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
        'id_app_group' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => '?integer'
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
     * Asignar descripcion
     *
     * Pasa la descripción a mayuscula
     *
     * @param string $description descripcion del grup
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
        if (!empty($this->attributes['id_app_group'])) {
            $menuModel = Model('MenuModel');

            $subquery = $menuModel->db->table("app_permission AS p")->select("p.id_menu")
                ->join("app_group_permission AS gp", "p.id_permission = gp.id_permission")
                ->where("gp.id_group", $this->attributes['id_app_group']);

            return $menuModel->whereIn("id_menu", $subquery)->findAll();
        }
    }
}

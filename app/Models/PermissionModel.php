<?php

/**
 * This file is part of the SYSTEM_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Models;

use App\Entities\Permission;
use App\Models\CustomModel;

/**
 * Modelo `PermissionModel`
 *
 * Administra la interaccion con la base de datos de la tabla app_permission
 *
 * @package  API_CI4
 * @category Model
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class PermissionModel extends CustomModel
{

    /**
     * --------------------------------------------------------------------
     * Configurar parametros del modelo
     *
     */
    protected $table          = TBL_PERMISSION;
    protected $primaryKey     = 'id_permission ';
    protected $returnType     = Permission::class;
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';
    protected $allowedFields  = [
        'id_menu',
        'name',
        'label',
        'description',
        'icon',
        'depends_on',
        'type',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}

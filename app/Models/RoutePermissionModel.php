<?php

namespace App\Models;

use App\Entities\RoutePermission;
use App\Models\CustomModel;

/**
 * Modelo `RoutePermission`
 * 
 * Administra la interaccion con la base de datos de la tabla route_permission
 * 
 * @package  API_CI4
 * @category Model
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class RoutePermissionModel extends CustomModel
{
    protected $table          = TBL_ROUTE_PERMISSION;
    protected $primaryKey     = 'id_route_permission';
    protected $uuidFields     = ['id_route_permission'];
    protected $returnType     = RoutePermission::class;
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';
    protected $allowedFields  = [
        'id_route',
        'id_user_group',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}

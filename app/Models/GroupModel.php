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

use CodeIgniter\Model;
use App\Entities\Group;

/**
 * Modelo `GroupModel`
 *
 * Administra la interaccion con la base de datos de la tabla app_menu
 *
 * @package  API_CI4
 * @category Model
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class GroupModel extends Model
{
    protected $table            = 'app_group';
    protected $primaryKey       = 'id_app_group';
    protected $returnType       = Group::class;
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $protectFields    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
    protected $allowedFields    = [
        'name',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}

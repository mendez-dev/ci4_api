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
use App\Entities\Menu;

/**
 * Modelo `MenuModel`
 *
 * Administra la interaccion con la base de datos de la tabla app_menu
 *
 * @package  API_CI4
 * @category Model
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class MenuModel extends Model
{
    protected $table            = TBL_MENU;
    protected $primaryKey       = 'id_menu';
    protected $returnType       = Menu::class;
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $protectFields    = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    protected $allowedFields    = [
        'label',
        'icon',
        'route',
        'priority',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}

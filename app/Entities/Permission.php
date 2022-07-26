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
 * Entidad `Permission`
 *
 * @package  API_CI4
 * @category Entity
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class Permission extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id_permission' => 'integer',
        'id_menu' => 'integer',
        'depends_on' => '?integer',
        'is_active'   => 'bool',
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
        'deleted_by'  => '?integer'
    ];
}

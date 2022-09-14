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
    protected $dates   = [];
    protected $casts   = [
        'id_permission' => 'string',
        'id_menu' => 'string',
        'depends_on' => '?string',
        'is_active'   => 'integer',
        'created_by'  => 'string',
        'updated_by'  => 'string',
        'deleted_by'  => '?string'
    ];
}

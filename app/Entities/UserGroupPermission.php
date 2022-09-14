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
 * Entidad `UserGroupPermission`
 *
 * @package  API_CI4
 * @category Entity
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class UserGroupPermission extends Entity
{
    protected $datamap = [];
    protected $dates   = [];
    protected $casts   = [
        'id_user_group_permission' => 'string',
        'id_user_group' => 'string',
        'id_permission' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => '?string'
    ];
}

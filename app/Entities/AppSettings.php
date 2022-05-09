<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

/**
 * Entidad `AppSettings`
 *
 * @package  API_CI4
 * @category Entity
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class AppSettings extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id_settings' => 'integer',
        'default_tax' => 'double',
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
        'deleted_by'  => '?integer'
    ];
}

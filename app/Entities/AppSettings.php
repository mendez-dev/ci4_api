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
    protected $dates   = [];
    protected $casts   = [
        'default_tax' => 'double',
        'deleted_by'  => '?integer'
    ];
}

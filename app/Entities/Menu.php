<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Menu extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id_menu'   => 'integer',
        'priority'  => 'integer',
        'is_active' => 'bool',
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
        'deleted_by'  => '?integer'
    ];

    /**
     * Asignar etiqueta
     *
     * Pasa la etiqueta a mayusculas
     *
     * @param string $label etiqueta del menu
     * @return void
     */
    protected function setLabel(string $label): void
    {
        $this->attributes['label'] =  strtoupper($label);
    }
}

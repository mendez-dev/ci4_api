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

/**
 * Modelo `UserModel`
 *
 * Administra la interaccion con la base de datos de la tabla app_user
 *
 * @package  API_CI4
 * @category Model
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class UserModel extends Model
{
    /**
     * --------------------------------------------------------------------
     * Configurar parametros del modelo
     *
     */
    protected $table          = 'user';
    protected $primaryKey     = 'id_user';
    protected $returnType     = User::class;
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';
    protected $allowedFields  = [
        'id_group',
        'firstname',
        'lastname',
        'username',
        'email',
        'password_hash',
        'picture',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}

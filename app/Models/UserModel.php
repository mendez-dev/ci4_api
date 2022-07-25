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

use App\Entities\User;
use App\Models\CustomModel;

/**
 * Modelo `UserModel`
 *
 * Administra la interaccion con la base de datos de la tabla app_user
 *
 * @package  API_CI4
 * @category Model
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class UserModel extends CustomModel
{
    /**
     * --------------------------------------------------------------------
     * Configurar parametros del modelo
     *
     */
    protected $table          = 'app_user';
    protected $primaryKey     = 'id_app_user';
    protected $returnType     = User::class;
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';
    protected $allowedFields  = [
        'id_group',
        'id_legacy',
        'firstname',
        'lastname',
        'username',
        'email',
        'password_hash',
        'picture',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $validationRules = [
        'id_legacy' => [
            'label' => 'id heredado',
            'rules' => 'permit_empty|integer|is_unique[app_user.id_legacy,id_app_user,{id_app_user}]'
        ],
        'id_group ' => [
            'label' => 'id grupo',
            'rules' => 'required|integer|is_not_unique[app_group.id_app_group]'
        ],
        'firstname' => [
            'label' => 'nombres',
            'rules' => 'required|max_length[100]'
        ],
        'lastname' => [
            'label' => 'apellidos',
            'rules' => 'required|max_length[100]'
        ],
        'username' => [
            'label' => 'nombre de usuario',
            'rules' =>
            'required|max_length[30]|is_unique[app_user.username,id_app_user,{id_app_user}]'
        ],
        'email' => [
            'label' => 'correo',
            'rules' => 'required|max_length[50]|is_unique[app_user.email,id_app_user,{id_app_user}]'
        ]
    ];

    /**
     *
     * Busca un usuario a partir de la información de sus columnas
     *
     * @param string|array $column nombre de la columna por la que se buscara
     * @param string $value valor a buscar
     *
     * @return User
     */
    public function getUserBy($column, string $value)
    {
        if (is_array($column)) {
        } else {
            return $this->where($column, $value)->first();
        }
    }
}

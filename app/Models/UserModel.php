<?php

/**
 * This file is part of the SYSTEM_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refer to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Models;

use App\Entities\User;
use App\Models\CustomModel;

/**
 * Modelo `UserModel`
 *
 * Administra la interaccion con la base de datos de la tabla user
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
    protected $table          = TBL_USER;
    protected $primaryKey     = 'id_user';
    protected $uuidFields     = ['id_user'];
    protected $returnType     = User::class;
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';
    protected $allowedFields  = [
        'id_user_group',
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
            'rules' => 'permit_empty|is_unique[' . TBL_USER . '.id_legacy,id_user,{id_user}]'
        ],
        'id_user_group ' => [
            'label' => 'id grupo',
            'rules' => 'required|is_not_unique[' . TBL_GROUP . '.id_user_group]'
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
            'required|max_length[30]|is_unique[' . TBL_USER . '.username,id_user,{id_user}]'
        ],
        'email' => [
            'label' => 'correo',
            'rules' => 'required|max_length[50]|is_unique[' . TBL_USER . '.email,id_user,{id_user}]'
        ]
    ];

    /**
     * --------------------------------------------------------------------
     * Relaciones
     *
     */
    protected $hasOne = [
        'group' => [
            'App\Models\GroupModel',
            'id_user_group',
            'id_user_group'
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
    public function getUserBy(string $column, string $value)
    {
        if (in_array($column, $this->allowedFields)) {
            return $this->where($column, $value)->first();
        }
    }
}

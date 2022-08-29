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
 * Entidad `AppUser`
 *
 * @package  API_CI4
 * @category Entity
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class User extends Entity
{
    protected $datamap = [];
    protected $dates   = [];
    protected $casts   = [
        'id_user'       => 'string',
        'id_legacy'     => '?integer',
        'id_user_group' => 'string',
        'is_active'     => 'bool',
        'created_by'    => 'string',
        'updated_by'    => 'string',
        'deleted_by'    => '?string'
    ];

    /**
     * set password
     *
     * Usa hash_pbkdf2 para encriptar contraseñas, los valores del `salt` y el
     * numero de iteraciones se encuentra en el archivo `Config/Constants.php`
     *
     * @param string $password contraseña a encriptar
     * @return void
     */
    protected function setPassword(string $password)
    {
        $this->attributes['password'] = $password;

        $this->attributes['password_hash'] = hash_pbkdf2(
            "sha512",
            $password,
            SALT,
            HASH_ITERATIONS,
            128
        );
    }

    /**
     * Retorna el grupo al que pertenece el usuario
     *
     * @return void
     */
    protected function getGroup()
    {
        if (!empty($this->attributes['id_user_group'])) {
            $groupModel = model("GroupModel");
            return $groupModel
                ->find($this->attributes['id_user_group']);
        }

        return null;
    }
}

<?php

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
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id_app_user' => 'integer',
        'id_legacy'   => '?integer',
        'id_group'    => 'integer',
        'is_active'   => 'bool',
        'created_by'  => 'integer',
        'updated_by'  => 'integer',
        'deleted_by'  => '?integer'
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
}
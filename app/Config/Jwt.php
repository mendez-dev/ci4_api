<?php

/**
 * This file is part of the FUPAPP.
 * 
 * (c) Open Solution Systems <operaciones@tumundolaboral.com.sv>
 * 
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Jwt extends BaseConfig
{
    /**
     * contains the key to decrypt the tokens
     *
     * @var string
     */
    public $jwt_key = 'jWnZr4u7x!A%D*G-KaNdRgUkXp2s5v8yQeThWmZq4t7w!z%C*F-JaNcRfUjXn2r5+KbPeShVmYq3t6w9z$C&F)J@NcQfTjWnD(G-KaPdSgVkYp3s6v9y$B&E)H@MbQeT!A%D*G-JaNdRgUkXp2s5v8y/B?E(H+Mb';

    /**
     * token expiration time
     *
     * @var string
     */
    public $expiration_time ="+7 day";

}
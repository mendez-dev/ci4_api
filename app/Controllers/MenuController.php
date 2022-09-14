<?php

/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refer to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\Authorization;
use App\Models\UserModel;

/**
 * Controlador ´MenuController´
 * 
 * Administracion de elementos del menu lateral
 */
class MenuController extends ResourceController
{

    /**
     * Instancia de UserModel.
     * @var \App\Models\UserModel
     */
    protected $userModel;

    public function __construct()
    {
        $this->userModel = model('UserModel');
    }

    public function index()
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();
        $user = $this->userModel->find($auth->id_user);

        if (!empty($user->group->menu)) {
            return $this->respond($user->group->menu);
        }
        return $this->respond(["errors" => ['No se encontraron registros']], 404);
    }
}

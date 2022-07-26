<?php

/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refere to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Entities\User;
use App\Libraries\Authorization;
use CodeIgniter\HTTP\Response;

/**
 * Controllador `AuthController`
 * 
 * Controla la autenticación de usuarios
 *
 * @package  API_CI4
 * @category Controller
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class AuthController extends ResourceController
{

    /**
     * Instancia de UserModel.
     * @var \App\Models\UserModel
     */
    protected $userModel;

    public function __construct()
    {
        // Cargamos modelos librerias y helpers
        $this->userModel = model('UserModel');
        helper('validation');
    }

    public function index(): Response
    {
        // Establecemos las validaciones del formulario
        $validation = service('validation');
        $validation->setRules([
            'username'  => ['label' => 'usuario',    'rules' => 'required'],
            'password'  => ['label' => 'contraseña', 'rules' => 'required']
        ]);

        // Si las validaciones fallan
        if (!$validation->withRequest($this->request)->run()) {
            return $this->respond(
                ['errors' => get_errors_array($validation->getErrors())],
                400
            );
        }

        // Guardamos los datos de la peticion
        $username  = trim($this->request->getVar('username'));
        $password  = trim($this->request->getVar('password'));

        /**
         * Buscamos un usuario que coincida con los parametros enviados
         *
         * @var \App\Entities\AppUser $user
         */
        if (!$user = $this->userModel->filterOne(["username" => $username], true)) {
            // ! Si ningun usuario coincide retornamos un mensaje de error
            return $this->respond(
                ['errors' => ['Usuario o contraseña incorrectos']],
                401
            );
        }

        /**
         * Hacemos uso de la entidad usuario para encriptar la contraseña
         * Al asignar el atributo `password` automaticamente se encripta el
         * valor enviado
         */
        $form = new User(['password' => $password]);

        // Se compara el hash de la contraseña enviada con la base de datos
        if ($form->password_hash != $user->password_hash) {
            // ! Si las contraseñas no coinciden retornamos un mensaje de error
            return $this->respond(
                ['errors' => ['Usuario o contraseña incorrectos']],
                401
            );
        }

        // Verivicamos si el usuario esta activado
        if ($user->is_active == 0) {
            // ! Si el usuario esta inactivo retornmaos un mensaje de error
            return $this->respond(
                ['errors' => ['Usuario desactivado']],
                401
            );
        }

        $token = Authorization::generateToken(["id_user" => $user->id_app_user]);
        return $this->respond(["token" => $token]);
    }

    public function verify()
    {
        $data = Authorization::getData();

        if (!empty($data) && isset($data->id_user)) {
            if ($user = $this->userModel->find($data->id_user)) {
                // Se elimina el password_hash de los datos retornados
                unset($user->password_hash);
                return $this->respond($user);
            }
        }

        return $this->respond(["errors" => ['No se encontro el usuario']], 401);
    }
}

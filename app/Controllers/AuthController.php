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
use App\Entities\User;
use App\Libraries\Authorization;
use CodeIgniter\HTTP\Response;

/**
 * Controlador `AuthController`
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
        // Cargamos modelos librerías y helpers
        $this->userModel = model('UserModel');
        helper('validation');
    }

    /**
     * Autentica un usuario
     * 
     * Recibe un correo/usuario y una contraseña y retorna un token de autenticación
     *
     * @return Response
     */
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

        // Almacenamos en variables los datos de la petición
        $username  = trim($this->request->getVar('username'));
        $password  = trim($this->request->getVar('password'));

        /**
         * Buscamos un usuario que coincida con los parámetros enviados
         *
         * @var \App\Entities\AppUser $user
         */
        if (!$user = $this->userModel->filterOne(["username" => $username], true)) {
            // ! Si ningún usuario coincide retornamos un mensaje de error
            return $this->respond(
                ['errors' => ['Usuario o contraseña incorrectos']],
                401
            );
        }

        /**
         * Hacemos uso de la entidad usuario para encriptar la contraseña
         * Al asignar el atributo `password` automáticamente se encripta el
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

        // Verificamos si el usuario esta activado
        if ($user->is_active == 0) {
            // ! Si el usuario esta inactivo retornamos un mensaje de error
            return $this->respond(
                ['errors' => ['Usuario desactivado']],
                401
            );
        }

        // Generamos el token de autenticación
        $token = Authorization::generateToken(["id_user" => $user->id_user]);
        // Retornamos el token
        return $this->respond(["token" => $token]);
    }

    /**
     * Verifica si un token es válido
     * 
     * Recibe un token y retorna los datos del usuario o un mensaje de error
     * 
     * @return Response
     */
    public function verify()
    {
        $data = Authorization::getData();

        if (!empty($data) && isset($data->id_user)) {
            if ($user = $this->userModel->find($data->id_user)) {
                // Se elimina el password_hash de los datos retornados
                unset($user->password_hash);
                // Retornamos la información del grupo de usuario
                /**
                 * @var \App\Entities\Group $user
                 */

                $userArray = $user->toArray();
                $userArray['group'] = $user->group->toArray();
                $userArray['group']['permissions'] = $user->group->permissions;


                return $this->respond($userArray);
            }
        }

        return $this->respond(["errors" => ['No se encontró el usuario']], 401);
    }
}

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
 * @OA\Info(title="Api codeigniter 4", version="0.1"),
 * @OA\SecurityScheme(
 *   securityScheme="bearerToken",
 *   type="http",
 *   in="header",
 *   description="Token de acceso, se puede generar en /login",
 *   name="Authorization",
 *   scheme="bearer",
 *   bearerFormat="JWT",
 * )
 * 
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

    /**
     * @OA\Post(
     *     path="/v1/login",
     *     tags={"Autenticación"},
     *     description="Verifíca las credenciales de autenticación y retorna un 
           token de acceso, si las credenciales son incorrectas o el usuario
           está inactivo retornara el correspondiente error.",
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *         type="object",
     *         required={"username", "password"},
     *         @OA\Property(property="username", type="string"),
     *         @OA\Property(property="password", type="string")
     *       )
     *     ),
     *     @OA\Response(
     *       response=200,
     *       description="Retorna el token de autenticación",
     *       @OA\JsonContent(
     *         type="object",
     *         example={
     *           "token": "xxxxxxxxxx.xxxxxxxxxxxxxxxxxxxxxxxxxxxx"
     *         }
     *       )
     *     ),
     *     @OA\Response(
     *       response=400,
     *       description="La petición no se pudo procesar, falta uno o más parametros en el RequestBody.",
     *       @OA\JsonContent(
     *         type="object",
     *         example={
     *           "errors": {
     *             "El campo contraseña es obligatorio."
     *           }
     *         }
     *       )
     *     ),
     *     @OA\Response(
     *       response=401,
     *       description="No autorizado, las credenciales son inválidas o el usuario está desactivado.",
     *       @OA\JsonContent(
     *         @OA\Examples(
     *           summary="Credenciales inválidas",
     *           example="",
     *           value={
     *             "errors": {
     *               "Usuario o contraseña incorrectos"
     *             } 
     *           }
     *         ),
     *         @OA\Examples(
     *           summary="Usuario desactivado",
     *           example="No se encontraron datos",
     *           value={
     *             "errors": {
     *               "Usuario desactivado"
     *             } 
     *           }
     *         )
     *       )
     *     )
     * )
     * 
     * Verifica las credenciales del usuario y si son correctas retorna un token
     * de autenticación.
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

    /**
     * @OA\Get(
     *   path="/v1/verify",
     *   tags={"Autenticación"},
     *   description="Verifíca el token de acceso y retorna la información del 
         usuario",
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       example={
     *          {
     *            "id_app_user": 1,
     *            "id_legacy": 1,
     *            "id_group": 1,
     *            "firstname": "ADMIN",
     *            "lastname": "ADMIN",
     *            "username": "ADMIN",
     *            "email": "admin@admin.com",
     *            "picture": "",
     *            "is_active": true,
     *            "created_by": 1,
     *            "created_at": {
     *              "date": "2022-05-12 10:49:07.000000",
     *              "timezone_type": 3,
     *              "timezone": "America/El_Salvador"
     *            },
     *            "updated_by": 1,
     *            "updated_at": {
     *              "date": "2022-05-12 11:14:21.000000",
     *              "timezone_type": 3,
     *              "timezone": "America/El_Salvador"
     *            },
     *            "deleted_by": null,
     *            "deleted_at": null
     *          }
     *       }
     *     )
     *   ),
     *  @OA\Response(
     *    response=401,
     *    description="El token de acceso es inválido o el usuario esta
          desactivado.",
     *    @OA\JsonContent(
     *      @OA\Examples(
     *        summary="No se envió el token, o es inválido",
     *        example="",
     *        value={
     *          "errors": {
     *            "Unautorized"
     *          } 
     *        }
     *       ),
     *      @OA\Examples(
     *        summary="Token expirado",
     *        example="expired token",
     *        value={
     *          "errors": {
     *            "expired token"
     *          } 
     *        }
     *       ),
     *      @OA\Examples(
     *        summary="Usuario desactivado",
     *        example="No se encontraron datos",
     *        value={
     *          "errors": {
     *            "Usuario desactivado"
     *          } 
     *        }
     *      )
     *    )
     *  ),
     *  security={{"bearerToken": {}}}
     * )
     */
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

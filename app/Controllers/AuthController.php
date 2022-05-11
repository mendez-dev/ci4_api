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

/**
 * @OA\Info(title="Api codeigniter 4", version="0.1")
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
     *     path="/login",
     *     tags={"Autenticación"},
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
    public function index()
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
         if ($user->status == 0) {
            // ! Si el usuario esta inactivo retornmaos un mensaje de error
            return $this->respond(
                ['errors' => ['Usuario desactivado']],
                401
            );
        }

        $token = Authorization::generateToken($user->id_app_user);
        return $this->respond(["token" => $token]);

    }

    /**
     * @OA\Get(
     *   tags={"Rutas para pruebas covid19"},
     *   path="/tamizaje/tipo_paciente/{ids}",
     *   summary="Muestra el listado de tipos de paciente filtrados por el tipo de servicio al que pueden aplicar",
     *   description="Muestra el listado de pacientes filtrados por el tipo de servicio al que pueden aplicat",
     *   @OA\Parameter(
     *     name="ids",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer"),
     *     example="1"
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Lista de tipo de pacientes filtrada",
     *     @OA\JsonContent(
     *       @OA\Property(property="resultado", type="string"),
     *       @OA\Property(property="datos", type="object", @OA\Property(property="error", type="string")),
     *       @OA\Property(property="entregado", type="string"),
     *       @OA\Property(property="consumo", type="number"),
     *       @OA\Examples(
     *         summary="OK",
     *         example="",
     *           value={
     *             "resultado": "OK",
     *             "datos": {
     *               {
     *                 "id": "1",
     *                 "nombre": "PACIENTE"
     *               },
     *               {
     *                 "id": "2",
     *                 "nombre": "PERSONAL DE SALUD"
     *               }
     *             },
     *             "entregado": "2022-03-07 13:52:53 America\/El_Salvador",
     *             "consumo": 0.06
     *           }
     *       ),
     *       @OA\Examples(
     *         summary="ERROR",
     *         example="No se encontraron datos",
     *           value={
     *             "resultado": "ERROR",
     *             "datos": {},
     *             "entregado": "2022-03-07 13:52:53 America\/El_Salvador",
     *             "consumo": 0.06
     *           }
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Token de acceso incorrecto",
     *     @OA\JsonContent(
     *       @OA\Examples(
     *         summary="Error",
     *         example="Autenticación incorrecta",
     *         value = {
     *           "error": "Usuario o clave inválida"
     *         }
     *       )
     *     )
     *   ),
     *   security={{"ApiKeyAuth": {}}}
     * ),
     */
    public function testFunction(){

    }
}

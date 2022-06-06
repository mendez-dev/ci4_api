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


    /**
     * @OA\Get(
     *   tags={"Menu"},
     *   path="/v1/menu",
     *   summary="Retorna los elementos del menu para dibujarlo",
     *   description="Retorna los elementos del menu a los que el usuario tiene
         acceso",
     *   @OA\Response(
     *     response=200,
     *     description="Listado de elementos del menu",
     *     @OA\JsonContent(
     *       type="object",
     *       example= {
     *       "data": {
     *           {
     *             "id_menu": 1,
     *             "label": "Usuarios",
     *             "icon": "fa-solid fa-users",
     *             "route": "/users",
     *             "priority": 10,
     *             "is_active": true,
     *             "created_by": 1,
     *             "created_at": {
     *               "date": "2022-06-06 12:20:38.000000",
     *               "timezone_type": 3,
     *               "timezone": "America/El_Salvador"
     *             },
     *             "updated_by": 1,
     *             "updated_at": {
     *               "date": "2022-06-06 12:20:38.000000",
     *               "timezone_type": 3,
     *               "timezone": "America/El_Salvador"
     *             },
     *             "deleted_by": null,
     *             "deleted_at": null
     *           }
     *         }
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="No se encontraron resultados que coincidan con los
           parametros de busqueda",
     *     @OA\JsonContent(
     *       type="object",
     *       example={
     *         "errors": {
     *           "No se encontraron registros"
     *         }
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="El token de acceso es inválido o el usuario esta
           desactivado.",
     *     @OA\JsonContent(
     *       @OA\Examples(
     *         summary="No se envió el token, o es inválido",
     *         example="",
     *         value={
     *             "errors": {
     *             "Unautorized"
     *           } 
     *         }
     *      ),
     *      @OA\Examples(
     *        summary="Token expirado",
     *        example="expired token",
     *        value={
     *            "errors": {
     *            "expired token"
     *          } 
     *        }
     *      ),
     *      @OA\Examples(
     *        summary="Usuario desactivado",
     *        example="No se encontraron datos",
     *        value={
     *            "errors": {
     *            "Usuario desactivado"
     *          } 
     *        }
     *      )
     *     ),
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Sin permisos para realizar esta acción",
     *     @OA\JsonContent(
     *       type="object",
     *       example={
     *         "errors": {
     *           "No tienes permisos para realizar esta acción"
     *         }
     *       }
     *     )
     *   ),
     *  security={{"bearerToken": {}}}
     * )
     */
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

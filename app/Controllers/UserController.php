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


/**
 * Controlador ´UserController´
 * 
 * Gestión de usuarios, registro, actualización, eliminación, cambio de estado
 * de los usuarios del sistema.
 */
class UserController extends ResourceController
{
    /**
     * Instancia de UserModel.
     * @var \App\Models\UserModel
     */
    private $userModel;

    public function __construct()
    {
        // Cargamos modelos librerias y helpers
        $this->userModel = model('UserModel');
        helper('validation');
        helper('utils');
    }



    /**
     * @OA\Get(
     *   tags={"Usuarios"},
     *   path="/v1/user",
     *   summary="Retorna el listado de usuarios paginados",
     *   description="Retorna el listado de usuarios paginados, se puede filtrar
         por nombre, apellido, correro, y por estado",
     *   @OA\Response(
     *     response=200,
     *     description="Listado de usuarios",
     *     @OA\JsonContent(
     *       type="object",
     *       example= {
     *         "data": {
     *           {
     *             "id_app_user": 1,
     *             "id_legacy": 1,
     *             "id_group": 1,
     *             "firstname": "ADMIN",
     *             "lastname": "ADMIN",
     *             "username": "ADMIN",
     *             "email": "admin@admin.com",
     *             "picture": "",
     *             "is_active": true,
     *             "created_by": 1,
     *             "created_at": {
     *               "date": "2022-05-16 09:43:07.000000",
     *               "timezone_type": 3,
     *               "timezone": "America/El_Salvador"
     *             },
     *             "updated_by": 1,
     *             "updated_at": {
     *               "date": "2022-05-16 09:43:07.000000",
     *               "timezone_type": 3,
     *               "timezone": "America/El_Salvador"
     *             },
     *             "deleted_by": null,
     *             "deleted_at": null
     *           }
     *         },
     *         "current_page": 1,
     *         "total_pages": 1
     *       }
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     description="El número de pagina que se está solicitando",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer"),
     *     example="1"
     *   ),
     *   @OA\Parameter(
     *     name="records_per_page",
     *     description="El número de registros que se quieren por página, por
           defecto 10",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(
     *     name="firstname",
     *     description="Resultados filtrados por nombres (like %query%)",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *     name="lastname",
     *     description="Resultados filtrados por apellidos (like %query%)",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *     name="email",
     *     description="Resultados filtrados por correo (like %query%)",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(
     *     name="is_active",
     *     description="Resultados filtrados por estado (like %query%)",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
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
     *     )
     *   ),
     *  security={{"bearerToken": {}}}
     * )
     */
    public function index()
    {
        // Obtenemos el numero de registros por página
        $page = getPage($this->request);
        $records_per_page = getRecordsPerPage($this->request);

        // Aplicamos los filtros enviados por query params
        $this->userModel->filterArray($this->request->getVar());
        // Buscamos los datos paginados
        $response = $this->userModel->getPagination($page, $records_per_page);

        if (!empty($response["data"])) {
            // Eliminamos el campo password_hash
            foreach ($response["data"] as $key => $value) {
                unset($response["data"][$key]->password_hash);
            }

            return $this->respond($response);
        }

        return $this->respond(["errors" => ['No se encontraron registros']], 404);


    }
}

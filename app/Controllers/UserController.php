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

use App\Entities\User;
use App\Libraries\Authorization;
use CodeIgniter\HTTP\Response;
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
    public function index(): Response
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

    /**
     * @OA\Get(
     *   tags={"Usuarios"},
     *   path="/v1/user/{id}",
     *   summary="Retorna la información de un usuario",
     *   description="Se envia en el path el id del usuario que se quiere obtener",
     *   @OA\Response(
     *     response=200,
     *     description="Listado de usuarios",
     *     @OA\JsonContent(
     *       type="object",
     *       example= {
     *         "id_app_user": 1,
     *         "id_legacy": 1,
     *         "id_group": 1,
     *         "firstname": "ADMIN",
     *         "lastname": "ADMIN",
     *         "username": "ADMIN",
     *         "email": "admin@admin.com",
     *         "picture": "",
     *         "is_active": true,
     *         "created_by": 1,
     *         "created_at": {
     *           "date": "2022-05-16 09:43:07.000000",
     *           "timezone_type": 3,
     *           "timezone": "America/El_Salvador"
     *         },
     *         "updated_by": 1,
     *         "updated_at": {
     *           "date": "2022-05-16 09:43:07.000000",
     *           "timezone_type": 3,
     *           "timezone": "America/El_Salvador"
     *         },
     *         "deleted_by": null,
     *         "deleted_at": null
     *       }
     *     )
     *   ),
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     example="1",
     *     description="id del usuario",
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
    public function info(int $id)
    {
        if (null !== $id) {
            $user = $this->userModel->find($id);
            if (!empty($user)) {
                unset($user->password_hash);
                return $this->respond($user);
            }
        }

        return $this->respond(["errors" => ['No se encontraron registros']], 404);
    }

    /**
     * @OA\Post(
     *   tags={"Usuarios"},
     *   path="/v1/user",
     *   summary="Guardar usuario en la base de datos",
     *   description="Se envian los datos del usuario en formato Json",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"id_group", "firstname", "lastname", "username", "email", "password"},
     *       @OA\Property(
     *         property="id_group", 
     *         type="integer",
     *         minimum="1",
     *         example="1",
     *         description="Id del grupo al que pertenece y del cual hereda los
               permisos" 
     *       ),
     *       @OA\Property(
     *         property="id_legacy",
     *         type="integer",
     *         example="1",
     *         uniqueItems= true,
     *         description="Id heredado del sistema, de la tabla de usuarios original
               para referencia, debe ser único"
     *       ),
     *       @OA\Property(
     *         property="firstname",
     *         type="string",
     *         maxLength=100,
     *         example="Juan",
     *         description="Nombres del usuario"
     *       ),
     *       @OA\Property(
     *         property="lastname",
     *         type="string",
     *         maxLength=100,
     *         example="Pérez",
     *         description="Apellidos del usuario"
     *       ),
     *       @OA\Property(
     *         property="username",
     *         type="string",
     *         maxLength=30,
     *         example="juan_perez",
     *         description="Nombre de usuario para acceder al sistema"
     *       ),
     *       @OA\Property(
     *         property="email",
     *         type="string",
     *         maxLength=50,
     *         format="email",
     *         example="juan_perez@gmail.com",
     *         description="Correo del usuario para acceder al sistema"
     *       ),
     *       @OA\Property(
     *         property="password",
     *         type="string",
     *         minLength=6,
     *         format="password ",
     *         example="123456",
     *         description="contraseña del usuario"
     *       ),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Usuario registrado con éxito",
     *     @OA\JsonContent(
     *       type="object",
     *       example= {
     *         "id_app_user": 1,
     *         "id_legacy": 1,
     *         "id_group": 1,
     *         "firstname": "ADMIN",
     *         "lastname": "ADMIN",
     *         "username": "ADMIN",
     *         "email": "admin@admin.com",
     *         "picture": "",
     *         "is_active": true,
     *         "created_by": 1,
     *         "created_at": {
     *           "date": "2022-05-16 09:43:07.000000",
     *           "timezone_type": 3,
     *           "timezone": "America/El_Salvador"
     *         },
     *         "updated_by": 1,
     *         "updated_at": {
     *           "date": "2022-05-16 09:43:07.000000",
     *           "timezone_type": 3,
     *           "timezone": "America/El_Salvador"
     *         },
     *         "deleted_by": null,
     *         "deleted_at": null
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="No se pudo completar la petición, faltan parámetros o
           los parametros enviados no son válidos",
     *     @OA\JsonContent(
     *       type="object",
     *       example={
     *         "errors": {
     *           "El campo id heredado debe contener un valor único."
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
    public function store()
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Creamos nuestra entidad usuario
        $user = new User((array) $this->request->getVar());

        // Agregamos información del estado y de quien creó el registro
        $user->is_active  = true;
        $user->created_by = $auth->id_user;
        $user->updated_by = $auth->id_user;

        // Almacenamos en la base de datos
        if ($this->userModel->save($user)) {
            // Retornamos el usuario registrado
            $id_user = $this->userModel->getInsertID();
            $new_user = $this->userModel->find($id_user);
            unset($new_user->password_hash);
            return $this->respond($new_user);
        } else {
            return $this->respond(['errors' => get_errors_array($this->userModel->errors())], 400);
        }

        return $this->respond(["errors" => ['No se pudo registrar el usuario, error al escribir en la base de datos']], 400);
    }

    /**
     * @OA\Put(
     *   tags={"Usuarios"},
     *   path="/v1/user/{id}",
     *   summary="Actualiza usuario en la base de datos",
     *   description="Se envian los datos del usuario en formato Json",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     example="1",
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"id_group", "firstname", "lastname", "username", "email"},
     *       @OA\Property(
     *         property="id_group", 
     *         type="integer",
     *         minimum="1",
     *         example="1",
     *         description="Id del grupo al que pertenece y del cual hereda los
               permisos" 
     *       ),
     *       @OA\Property(
     *         property="id_legacy",
     *         type="integer",
     *         example="1",
     *         uniqueItems= true,
     *         description="Id heredado del sistema, de la tabla de usuarios original
               para referencia, debe ser único"
     *       ),
     *       @OA\Property(
     *         property="firstname",
     *         type="string",
     *         maxLength=100,
     *         example="Juan",
     *         description="Nombres del usuario"
     *       ),
     *       @OA\Property(
     *         property="lastname",
     *         type="string",
     *         maxLength=100,
     *         example="Pérez",
     *         description="Apellidos del usuario"
     *       ),
     *       @OA\Property(
     *         property="username",
     *         type="string",
     *         maxLength=30,
     *         example="juan_perez",
     *         description="Nombre de usuario para acceder al sistema"
     *       ),
     *       @OA\Property(
     *         property="email",
     *         type="string",
     *         maxLength=50,
     *         format="email",
     *         example="juan_perez@gmail.com",
     *         description="Correo del usuario para acceder al sistema"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Usuario registrado con éxito",
     *     @OA\JsonContent(
     *       type="object",
     *       example= {
     *         "id_app_user": 1,
     *         "id_legacy": 1,
     *         "id_group": 1,
     *         "firstname": "ADMIN",
     *         "lastname": "ADMIN",
     *         "username": "ADMIN",
     *         "email": "admin@admin.com",
     *         "picture": "",
     *         "is_active": true,
     *         "created_by": 1,
     *         "created_at": {
     *           "date": "2022-05-16 09:43:07.000000",
     *           "timezone_type": 3,
     *           "timezone": "America/El_Salvador"
     *         },
     *         "updated_by": 1,
     *         "updated_at": {
     *           "date": "2022-05-16 09:43:07.000000",
     *           "timezone_type": 3,
     *           "timezone": "America/El_Salvador"
     *         },
     *         "deleted_by": null,
     *         "deleted_at": null
     *       }
     *     )
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="No se pudo completar la petición, faltan parámetros o
           los parametros enviados no son válidos",
     *     @OA\JsonContent(
     *       type="object",
     *       example={
     *         "errors": {
     *           "El campo id heredado debe contener un valor único."
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
    public function update($id = 0)
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();
        // Verificamos si el usuario existe
        if ($id !== 0) {
            $user = $this->userModel->find($id);
            if (empty($user)) {
                return $this->respond(["errors" => ['No existe usuario con el id enviado']], 400);
            }
        }

        // Creamos nuestra entidad usuario
        $user = new User((array) $this->request->getVar());
        $user->id_app_user = $id;
        $user->updated_by = $auth->id_user;

        // Almacenamos en la base de datos
        if ($this->userModel->save($user) == true) {
            $updated_user = $this->userModel->find($id);
            unset($updated_user->password_hash);
            return $this->respond($updated_user);
        } else {
            return $this->respond(['errors' => get_errors_array($this->userModel->errors())], 400);
        }

        return $this->respond(["errors" => ['No se pudo actualizar el usuario, error al escribir en la base de datos']], 400);
    }

    /**
     * @OA\Delete(
     *   tags={"Usuarios"},
     *   path="/v1/user/{id}",
     *   summary="Elimina un usuario de la base de datos",
     *   description="Usa soft delete para eliminar el usuario de la base de datos
         registrando quien lo elimino y la fecha y hora de la acción",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer"),
     *     example="2",
     *     description="id del usuario que se decea eliminar"
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(
     *     response=400,
     *     description="No se pudo procesar la petición, parámetros inválidos",
     *     @OA\JsonContent(
     *       type="object",
     *       example={
     *         "errors": {
     *           "No existe usuario con el id enviado"
     *         }
     *       }
     *     )
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
     *   security={{"bearerToken": {}}}
     * ),
     */
    public function delete($id = null)
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        if ($id !== 0) {
            $user = $this->userModel->find($id);
            if (empty($user)) {
                return $this->respond(["errors" => ['No existe usuario con el id enviado']], 400);
            }
        }

        // Indicamos quien eliminó el registro
        $user->deleted_by = $auth->id_user;

        // Iniciamos transact
        $this->userModel->db->transBegin();

        // actualizamos el id del usuario que guardo eliminó el registro
        $this->userModel->save($user);
        $this->userModel->delete($id);

        if ($this->userModel->db->transStatus() === false) {
            $this->userModel->db->transRollback();
        } else {
            $this->userModel->db->transCommit();
            return $this->respond([]);
        }

        return $this->respond(["errors" => ['No se pudo eliminar el usuario, error al escribir en la base de datos']], 400);
    }

    /**
     * @OA\Put(
     *   tags={"Usuarios"},
     *   path="/v1/user/{id}/enable",
     *   summary="Cambia el estado del usuario a activo",
     *   description="Cambia el estado del usuario a activo para que pueda acceder
         al sistema",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer"),
     *     example="2",
     *     description="id del usuario que se decea habilitar
     *     "
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(
     *     response=400,
     *     description="No se pudo procesar la petición, parámetros inválidos",
     *     @OA\JsonContent(
     *       type="object",
     *       example={
     *         "errors": {
     *           "No existe usuario con el id enviado"
     *         }
     *       }
     *     )
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
     *   security={{"bearerToken": {}}}
     * ),
     */
    public function enable($id = null)
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        if ($id !== 0) {
            $user = $this->userModel->find($id);
            if (empty($user)) {
                return $this->respond(["errors" => ['No existe usuario con el id enviado']], 400);
            }
        }

        if ($user->is_active == 1) {
            return $this->respond(["errors" => ['El usuario ya se encuentra activo']], 400);
        }

        // Indicamos quien eliminó el registro
        $user->updated_by = $auth->id_user;
        $user->is_active  = 1;

        // actualizamos el id del usuario que guardo eliminó el registro
        if ($this->userModel->save($user)) {
            return $this->respond([]);
        }

        return $this->respond(["errors" => ['No se pudo habilitar el usuario, error al escribir en la base de datos']], 400);
    }

    /**
     * @OA\Put(
     *   tags={"Usuarios"},
     *   path="/v1/user/{id}/disable",
     *   summary="Cambia el estado del usuario a inactivo",
     *   description="Cambia el estado del usuario a inactivo para que pueda acceder
         al sistema",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer"),
     *     example="2",
     *     description="id del usuario que se decea deshabilitar
     *     "
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(
     *     response=400,
     *     description="No se pudo procesar la petición, parámetros inválidos",
     *     @OA\JsonContent(
     *       type="object",
     *       example={
     *         "errors": {
     *           "No existe usuario con el id enviado"
     *         }
     *       }
     *     )
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
     *   security={{"bearerToken": {}}}
     * ),
     */
    public function disable($id = null)
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        if ($id !== 0) {
            $user = $this->userModel->find($id);
            if (empty($user)) {
                return $this->respond(["errors" => ['No existe usuario con el id enviado']], 400);
            }
        }

        if ($user->is_active == 0) {
            return $this->respond(["errors" => ['El usuario ya se encuentra inactivo']], 400);
        }

        // Indicamos quien eliminó el registro
        $user->updated_by = $auth->id_user;
        $user->is_active  = 0;

        // actualizamos el id del usuario que guardo eliminó el registro
        if ($this->userModel->save($user)) {
            return $this->respond([]);
        }

        return $this->respond(["errors" => ['No se pudo habilitar el usuario, error al escribir en la base de datos']], 400);
    }

    /**
     * @OA\Put(
     *   tags={"Usuarios"},
     *   path="/v1/user/{id}/password",
     *   summary="Actualiza la contraseña de un usuario",
     *   description="Actualiza la contraseña de un usuario",
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer"),
     *     example="2",
     *     description="id del usuario que se decea deshabilitar
     *     "
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"password"},
     *       @OA\Property(
     *         property="password",
     *         type="string",
     *         minLength=6,
     *       )
     *     )
     *   ),
     *   @OA\Response(response=200, description="OK"),
     *   @OA\Response(
     *     response=400,
     *     description="No se pudo procesar la petición, parámetros inválidos",
     *     @OA\JsonContent(
     *       type="object",
     *       example={
     *         "errors": {
     *           "El campo contraseña es obligatorio."
     *         }
     *       }
     *     )
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
     *   security={{"bearerToken": {}}}
     * ),
     */
    public function chagePassword($id = null)
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Obtenemos el usuario que queremos actualizar
        if ($id !== 0) {
            $user = $this->userModel->find($id);
            if (empty($user)) {
                return $this->respond(["errors" => ['No existe usuario con el id enviado']], 400);
            }
        }

        // Cargamos la libreria para validar
        $validation = service('validation');

        // Definimos las reglas de validación
        $validation->setRules([
            'password' => [
                'label' => 'contraseña',
                'rules' => rule_array([
                    'required',
                    'max_length[7]'
                ])
            ]
        ]);

        // Si las validaciones fallan
        if (!$validation->withRequest($this->request)->run()) {
            return $this->respond(['errors' => get_errors_array($validation->getErrors())], 400);
        }

        // Indicamos quien eliminó el registro
        $user->updated_by = $auth->id_user;
        $user->password  = $this->request->getVar("password");

        // actualizamos el id del usuario que guardo eliminó el registro
        if ($this->userModel->save($user)) {
            return $this->respond([]);
        }

        return $this->respond(["errors" => ['No se pudo habilitar el usuario, error al escribir en la base de datos']], 400);
    }
}

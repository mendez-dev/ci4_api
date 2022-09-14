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
        // Cargamos modelos librerías y helpers
        $this->userModel = model('UserModel');
        helper('validation');
        helper('utils');
    }

    /**
     * Retorna todos los usuarios del sistema
     *
     * @return Response
     */
    public function index(): Response
    {
        $query_params = getQueryParams($this->request);

        // Filtros extras para la búsqueda
        // Agregamos un filtro que busque por el nombre del usuario concatenado con el apellido
        $filters = [];
        if ($this->request->getVar('full_name') !== null) {
            $filters = [
                "CONCAT(firstname, ' ', lastname)" => $this->request->getVar('full_name'),
            ];
        }

        // Buscamos los usuarios
        $data = $this->userModel->getData($query_params, $filters);

        // Eliminamos el password hash de la data a retornar y retornamos la información del grupo
        if (isset($data["response"]["data"])) {
            foreach ($data["response"]["data"] as $key => $value) {
                unset($data["response"]["data"][$key]->password_hash);
                $data["response"]["data"][$key]->group = $data["response"]["data"][$key]->group;
            }
        } else if (!isset($data["response"]["errors"])) {
            foreach ($data["response"] as $key => $value) {
                unset($data["response"][$key]->password_hash);
                $data["response"][$key]->group = $data["response"][$key]->group;
            }
        }

        // Retornamos la data
        return $this->respond($data["response"], $data["code"]);
    }

    /**
     * Retorna la información de un usuario
     * 
     * @param string $id id del usuario
     * @return Response
     */
    public function info(string $id)
    {
        if (null !== $id) {
            $user = $this->userModel->find($id);
            if (!empty($user)) {
                unset($user->password_hash);
                // Retornamos también la data del grupo al que pertenece el usuario
                $user->group = $user->group;
                return $this->respond($user);
            }
        }

        return $this->respond(["errors" => ['No se encontraron registros']], 404);
    }

    /**
     * Crea un nuevo usuario
     *
     * @return Response
     */
    public function store()
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Validamos la contraseña
        $validation = service('validation');

        // Definimos las reglas de validación
        $validation->setRules([
            'password' => [
                'label' => 'contraseña',
                'rules' => rule_array([
                    'required',
                    'min_length[8]'
                ])
            ]
        ]);

        // Si las validaciones fallan
        if (!$validation->withRequest($this->request)->run()) {
            return $this->respond(['errors' => get_errors_array($validation->getErrors())], 400);
        }

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
     * Actualiza la información de un usuario
     *
     * @param string $id id del usuario
     * @return Response
     */
    public function update($id = "")
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Verificamos si el usuario existe
        if ($id !== "") {
            $user = $this->userModel->find($id);
            if (empty($user)) {
                return $this->respond(["errors" => ['No existe usuario con el id enviado']], 400);
            }
        }

        // Creamos nuestra entidad usuario
        $user = new User((array) $this->request->getVar());
        $user->id_user = $id;
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
     * Elimina un usuario
     * 
     * usa soft deletes para no eliminar el registro de la base de datos
     *
     * @param string $id id del usuario
     * @return Response
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
     * Activa un usuario
     * 
     * Cambia el estado de un usuario a activo
     * 
     * @param string $id id del usuario
     * @return Response
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
     * Desactiva un usuario
     * 
     * Cambia el estado de un usuario a inactivo
     * 
     * @param string $id id del usuario
     * @return Response
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
     * Actualiza la contraseña de un usuario
     * 
     * @param string $id id del usuario
     * @return Response
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

        // Cargamos la librería para validar
        $validation = service('validation');

        // Definimos las reglas de validación
        $validation->setRules([
            'password' => [
                'label' => 'contraseña',
                'rules' => rule_array([
                    'required',
                    'min_length[8]'
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

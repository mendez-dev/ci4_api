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

use App\Entities\Group;
use App\Libraries\Authorization;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Response;

/**
 * Controlador ´GroupController´
 * 
 * Gestión de grupos, registro, actualización, eliminación, cambio de estado
 * de los grupos de usuarios del sistema.
 */
class GroupController extends ResourceController
{
    /**
     * Instancia de GroupModel.
     * @var \App\Models\GroupModel
     */
    private $groupModel;

    /**
     * Instancia de PermissionModel.
     * @var \App\Models\PermissionModel
     */
    private $permissionModel;

    /**
     * Instancia de UserGroupPermissionModel.
     * @var \App\Models\UserGroupPermissionModel
     */
    private $userGroupPermissionModel;

    public function __construct()
    {
        // Cargamos modelos librerías y helpers
        $this->groupModel = model('GroupModel');
        $this->permissionModel = model('PermissionModel');
        $this->userGroupPermissionModel = model('UserGroupPermissionModel');
        helper('validation');
        helper('utils');
    }

    /**
     * Retorna todos los grupos registrados en el sistema
     *
     * @return Response
     */
    public function index(): Response
    {
        $query_params = getQueryParams($this->request);
        $data = $this->groupModel->getData($query_params);
        return $this->respond($data["response"], $data["code"]);
    }

    /**
     * Retorna un grupo en específico
     *
     * @param int $id id del grupo
     * @return Response
     */
    public function info(string $id): Response
    {
        if (null !== $id) {
            $group = $this->groupModel->find($id);
            if (!empty($group)) {
                return $this->respond($group, 200);
            }
        }
        return $this->respond(["errors" => ['No se encontraron registros']], 404);
    }

    /**
     * Crea un nuevo grupo de usuarios.
     * @return Response
     */
    public function store()
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Creamos nuestra entidad de grupo
        $group = new Group((array) $this->request->getVar());

        // Agregamos la información del usuario que crea el grupo
        $group->is_active   = true;
        $group->created_by = $auth->id_user;
        $group->updated_by = $auth->id_user;

        // Almacenamos en la base de datos
        if ($this->groupModel->save($group)) {
            $id_group = $this->groupModel->getInsertID();
            $new_group = $this->groupModel->find($id_group);
            return $this->respond($new_group);
        } else {
            return $this->respond(["errors" => $this->groupModel->errors()], 400);
        }

        return $this->respond(["errors" => ['No se pudo registrar el grupo, error al escribir en la base de datos']], 400);
    }

    /**
     * Actualiza la información de un grupo de usuarios.
     * @param string $id
     * @return Response
     */
    public function update($id = "")
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Verificamos si el grupo existe
        if ($id !== "") {
            $group = $this->groupModel->find($id);
            if (empty($group)) {
                return $this->respond(["errors" => ['No existe grupo con el id enviado']], 400);
            }
        }

        // Creamos nuestra entidad de grupo
        $group = new Group((array) $this->request->getVar());
        $group->id_user_group = $id;
        $group->updated_by = $auth->id_user;

        // Almacenamos en la base de datos
        if ($this->groupModel->save($group)) {
            $updated_group = $this->groupModel->find($id);
            return $this->respond($updated_group);
        } else {
            return $this->respond(["errors" => $this->groupModel->errors()], 400);
        }

        return $this->respond(["errors" => ['No se pudo actualizar el grupo, error al escribir en la base de datos']], 400);
    }

    /**
     * Elimina un grupo de usuarios.
     * @param string $id
     * @return Response
     */
    public function delete($id = "")
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Verificamos si el grupo existe
        if ($id !== "") {
            $group = $this->groupModel->find($id);
            if (empty($group)) {
                return $this->respond(["errors" => ['No existe grupo con el id enviado']], 400);
            }
        }

        // Indicamos quien elimina el grupo
        $group->deleted_by = $auth->id_user;
        // Iniciamos la transacción
        $this->groupModel->db->transBegin();

        //  Actualizamos el id del usuario que elimino el grupo
        $this->groupModel->save($group);
        // Eliminamos el grupo
        $this->groupModel->delete($id);

        // Verificamos si se ejecutaron las dos consultas
        if ($this->groupModel->db->transStatus() === FALSE) {
            $this->groupModel->db->transRollback();
        } else {
            $this->groupModel->db->transCommit();
            return $this->respond([]);
        }

        return $this->respond(["errors" => ['No se pudo eliminar el grupo, error al escribir en la base de datos']], 400);
    }

    /**
     * Habilita un grupo de usuarios.
     * @param string $id
     * @return Response
     */
    public function enable($id = "")
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Verificamos si el grupo existe
        if ($id !== "") {
            $group = $this->groupModel->find($id);
            if (empty($group)) {
                return $this->respond(["errors" => ['No existe grupo con el id enviado']], 400);
            }
        }

        // Verificamos si el grupo esta activo
        if ($group->is_active) {
            return $this->respond(["errors" => ['El grupo ya se encuentra activo']], 400);
        }

        // Indicamos quien habilita el grupo
        $group->updated_by = $auth->id_user;
        $group->is_active = true;

        // Almacenamos en la base de datos
        if ($this->groupModel->save($group)) {
            return $this->respond([]);
        } else {
            return $this->respond(["errors" => $this->groupModel->errors()], 400);
        }

        return $this->respond(["errors" => ['No se pudo habilitar el grupo, error al escribir en la base de datos']], 400);
    }

    /**
     * Deshabilita un grupo de usuarios.
     * @param string $id
     * @return Response
     */
    public function disable($id = "")
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Verificamos si el grupo existe
        if ($id !== "") {
            $group = $this->groupModel->find($id);
            if (empty($group)) {
                return $this->respond(["errors" => ['No existe grupo con el id enviado']], 400);
            }
        }

        // Verificamos si el grupo esta deshabilitado
        if (!$group->is_active) {
            return $this->respond(["errors" => ['El grupo ya se encuentra deshabilitado']], 400);
        }

        // Indicamos quien deshabilita el grupo
        $group->updated_by = $auth->id_user;
        $group->is_active = false;

        // Almacenamos en la base de datos
        if ($this->groupModel->save($group)) {
            return $this->respond([]);
        } else {
            return $this->respond(["errors" => $this->groupModel->errors()], 400);
        }

        return $this->respond(["errors" => ['No se pudo deshabilitar el grupo, error al escribir en la base de datos']], 400);
    }

    /**
     * Obtiene los permisos de un grupo de usuarios.
     * @param string $id
     * @return Response
     */
    public function permissions($id = "")
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Verificamos si el grupo existe
        if ($id !== "") {
            $group = $this->groupModel->find($id);
            if (empty($group)) {
                return $this->respond(["errors" => ['No existe grupo con el id enviado']], 400);
            }
        }
        // Obtenemos los permisos del grupo
        $permissions = $group->permissions;

        // Verificamos si el grupo tiene permisos
        if (empty($permissions)) {
            return $this->respond(["errors" => ['El grupo no tiene permisos asignados']], 404);
        }

        return $this->respond($permissions);
    }

    /**
     * Asigna permisos a un grupo de usuarios.
     * @param string $id
     * @return Response
     */
    public function assignPermissions($id = "")
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Verificamos si el grupo existe
        if ($id !== "") {
            $group = $this->groupModel->find($id);
            if (empty($group)) {
                return $this->respond(["errors" => ['No existe grupo con el id enviado']], 400);
            }
        }

        // Obtenemos los permisos enviados
        $permissions = $this->request->getVar();

        // print_r($permissions);

        // Verificamos si se enviaron permisos
        if (empty($permissions)) {
            return $this->respond(["errors" => ['No se enviaron permisos']], 400);
        }

        // Iniciamos la transacción
        $this->groupModel->db->transBegin();

        // Eliminamos los permisos actuales
        $this->groupModel->deletePermissions($id, $auth->id_user);

        $errors = [];
        // Asignamos los permisos
        foreach ($permissions as $permission) {
            $errors = array_merge($errors, $this->groupModel->assignPermission($id, $permission, $auth->id_user));
        }

        // Verificamos si se ejecutaron las dos consultas
        if ($this->groupModel->db->transStatus() === FALSE) {
            $this->groupModel->db->transRollback();
            // Retornamos el error en la respuesta

        } else {
            if (!empty($errors)) {
                $this->groupModel->db->transRollback();
                return $this->respond(["errors" => $errors], 400);
            } else {
                $this->groupModel->db->transCommit();
                return $this->respond([]);
            }
        }

        return $this->respond(["errors" => ['No se pudo asignar los permisos, error al escribir en la base de datos']], 400);
    }
}

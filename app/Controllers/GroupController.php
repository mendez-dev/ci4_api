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

use App\Entities\Group;
use App\Libraries\Authorization;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Response;
use App\Entities\User;

/**
 * Controlador ´GroupController´
 * 
 * Gestión de grupos, registro, actualización, eliminación, cambio de estado
 * de los grupos de usuarioss del sistema.
 */
class GroupController extends ResourceController
{
    /**
     * Instancia de GroupModel.
     * @var \App\Models\GroupModel
     */
    private $groupModel;

    public function __construct()
    {
        // Cargamos modelos librerias y helpers
        $this->groupModel = model('GroupModel');
        helper('validation');
        helper('utils');
    }

    public function index(): Response
    {
        $query_params = getQueryParams($this->request);
        $data = $this->groupModel->getData($query_params);
        return $this->respond($data["response"], $data["code"]);
    }

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
        $group = new Group((array) $this->request->getVar);

        // Agregamos la información del usuario que crea el grupo
        $group->is_active   = true;
        $group->created_by = $auth->id_user;
        $group->updated_by = $auth->id_user;

        // Almacenamos en la base de datos
        if ($this->groupModel->save($group)) {
            $id_group = $this->groupModel->insertID();
            $new_group = $this->groupModel->find($id_group);
            return $this->respond($new_group);
        } else {
            return $this->respond(["errors" => $this->groupModel->errors()], 400);
        }

        return $this->respond(["errors" => ['No se pudo registrar el grupo, error al escribir en la base de datos']], 400);
    }

    public function update($id = "")
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Verificamos si el grupo existe
        if ($id !== "") {
            $group = $this->groupModel->find($id);
            if (empty($group)) {
                return $this->respond(["errors" => ['No existe grupo con el id enviado']], 404);
            }
        }

        // Creamos nuestra entidad de grupo
        $group = new Group((array) $this->request->getVar);
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
}

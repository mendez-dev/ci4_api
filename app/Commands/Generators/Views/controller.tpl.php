<@php
/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilberdev@gmail.com>
 *
 * For the full copyright and license information, please refer to LICENSE file
 * that has been distributed with this source code.
 */

namespace {namespace};
use App\Entities\<?= $entity ?>;
use App\Libraries\Authorization;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Response;

class <?= $entity ?>Controller extends ResourceController
{
    /**
     * Instancia de <?= $entity ?>Model.
     * @var \App\Models\<?= $entity ?>Model
     */
    private $<?= strtolower($entity) ?>Model;

    public function __construct()
    {
        // Cargamos modelos librerías y helpers
        $this-><?= strtolower($entity) ?>Model = model('<?= $entity ?>Model');
        helper('validation');
        helper('utils');
    }

    /**
     * Retorna todos los <?= $entity ?> registrados en el sistema
     *
     * @return Response
     */
    public function index(): Response
    {
        $query_params = getQueryParams($this->request);
        $data = $this-><?= strtolower($entity) ?>Model->getData($query_params);
        return $this->respond($data["response"], $data["code"]);
    }

    /**
     * Retorna un elemento en específico
     *
     * @param int $id
     * @return Response
     */
    public function info(string $id): Response
    {
        if (null !== $id) {
            $<?= strtolower($entity) ?> = $this-><?= strtolower($entity) ?>Model->find($id);
            if (!empty($<?= strtolower($entity) ?>)) {
                return $this->respond($<?= strtolower($entity) ?>, 200);
            }
        }
        return $this->respond(["errors" => ['No se encontraron registros']], 404);
    }

    /**
     * Crea un nuevo registro.
     * @return Response
     */
    public function store()
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Creamos nuestra entidad
        $<?= strtolower($entity) ?> = new <?= $entity ?>((array) $this->request->getVar());

        // Agregamos la información del usuario que crea el registro
        $<?= strtolower($entity) ?>->is_active   = true;
        $<?= strtolower($entity) ?>->created_by = $auth->id_user;
        $<?= strtolower($entity) ?>->updated_by = $auth->id_user;

        // Almacenamos en la base de datos
        if ($this-><?= strtolower($entity) ?>Model->save($<?= strtolower($entity) ?>)) {
            $id_<?= strtolower($entity) ?> = $this-><?= strtolower($entity) ?>Model->getInsertID();
            $new_<?= strtolower($entity) ?> = $this-><?= strtolower($entity) ?>Model->find($id_<?= strtolower($entity) ?>);
            return $this->respond($new_<?= strtolower($entity) ?>);
        } else {
            return $this->respond(["errors" => get_errors_array($this-><?= strtolower($entity) ?>Model->errors())], 400);
        }

        return $this->respond(["errors" => ['No se pudo guardar el registro, error al escribir en la base de datos']], 400);
    }

    /**
     * Actualiza la información de un registro.
     * @param string $id
     * @return Response
     */
    public function update($id = "")
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Verificamos si el registro existe
        if ($id !== "") {
            $<?= strtolower($entity) ?> = $this-><?= strtolower($entity) ?>Model->find($id);
            if (empty($<?= strtolower($entity) ?>)) {
                return $this->respond(["errors" => ['No existe registro con el id enviado']], 400);
            }
        }

        // Creamos nuestra entidad
        $<?= strtolower($entity) ?> = new <?= $entity ?>((array) $this->request->getVar());
        $<?= strtolower($entity) ?>-><?= $id ?> = $id;
        $<?= strtolower($entity) ?>->updated_by = $auth->id_user;

        // Almacenamos en la base de datos
        if ($this-><?= strtolower($entity) ?>Model->save($<?= strtolower($entity) ?>)) {
            $updated_<?= strtolower($entity) ?> = $this-><?= strtolower($entity) ?>Model->find($id);
            return $this->respond($updated_<?= strtolower($entity) ?>);
        } else {
            return $this->respond(["errors" => get_errors_array($this-><?= strtolower($entity) ?>Model->errors())], 400);
        }

        return $this->respond(["errors" => ['No se pudo actualizar el registro, error al escribir en la base de datos']], 400);
    }

    /**
     * Elimina un registro.
     * @param string $id
     * @return Response
     */
    public function delete($id = "")
    {
        // Obtenemos la información del token
        $auth = Authorization::getData();

        // Verificamos si el registro existe
        if ($id !== "") {
            $<?= strtolower($entity) ?> = $this-><?= strtolower($entity) ?>Model->find($id);
            if (empty($<?= strtolower($entity) ?>)) {
                return $this->respond(["errors" => ['No existe registro con el id enviado']], 400);
            }
        }

        // Indicamos quien elimina el registro
        $<?= strtolower($entity) ?>->deleted_by = $auth->id_user;
        // Iniciamos la transacción
        $this-><?= strtolower($entity) ?>Model->db->transBegin();

        //  Actualizamos el id del usuario que elimino el registro
        $this-><?= strtolower($entity) ?>Model->save($<?= strtolower($entity) ?>);
        // Eliminamos el registro
        $this-><?= strtolower($entity) ?>Model->delete($id);

        // Verificamos si se ejecutaron las dos consultas
        if ($this-><?= strtolower($entity) ?>Model->db->transStatus() === FALSE) {
            $this-><?= strtolower($entity) ?>Model->db->transRollback();
        } else {
            $this-><?= strtolower($entity) ?>Model->db->transCommit();
            return $this->respond([]);
        }

        return $this->respond(["errors" => ['No se pudo eliminar el registro, error al escribir en la base de datos']], 400);
    }
}
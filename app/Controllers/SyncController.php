<?php

namespace App\Controllers;

use CodeIgniter\HTTP\Response;
use CodeIgniter\RESTful\ResourceController;

class SyncController extends ResourceController
{

    public function resources_list(): Response
    {
        // Retorna los nombres de las tablas de la base de datos que se pueden descargar
        $db = \Config\Database::connect();
        $tables = $db->listTables();
        $tables = array_values(array_diff($tables, ['migrations', 'app_settings', 'route', 'route_permission']));
        
        return $this->respond($tables);
    }


    public function batchs_info(): Response
    {
        // Obtenemos las variables para hacer la consulta
        $resource = $this->request->getGet('resource');
        $limit = $this->request->getGet('limit');
        $lastUpdate = $this->request->getGet('last_update');

        // Validamos los parámetros
        if (empty($resource)) {
            return $this->respond(['message' => 'Debe enviar el nombre del recurso'], 400);
        }

        if (empty($limit)) {
            return $this->respond(['message' => 'Debe enviar la cantidad de registros por lotes'], 400);
        }

        $db = \Config\Database::connect();
        $tables = $db->listTables();
        $tables = array_values(array_diff($tables, ['migrations', 'app_settings', 'route', 'route_permission']));

        if (!in_array($resource, $tables)) {
            return $this->respond(['message' => 'El recurso no es válido'], 400);
        }

        $builder = $db->table($resource);

        if (!empty($lastUpdate)) {
            $builder->where('updated_at >', $lastUpdate);
        }

        $total = $builder->countAllResults();
        $totalBatches = ceil($total / $limit);
        return $this->respond(['total_batches' => $totalBatches]);
    }

    public function data(){
        // Obtenemos las variables para hacer la consulta
        $resource = $this->request->getGet('resource');
        $batch = $this->request->getGet('batch');
        $limit = $this->request->getGet('limit');
        $lastUpdate = $this->request->getGet('last_update');

        // Validamos los parámetros
        if (empty($resource)) {
            return $this->respond(['message' => 'Debe enviar el nombre del recurso'], 400);
        }

        if (empty($batch)) {
            return $this->respond(['message' => 'Debe enviar el número de lote'], 400);
        }

        if (empty($limit)) {
            return $this->respond(['message' => 'Debe enviar la cantidad de registros por lotes'], 400);
        }

        $db = \Config\Database::connect();
        $tables = $db->listTables();
        $tables = array_values(array_diff($tables, ['migrations']));

        if (!in_array($resource, $tables)) {
            return $this->respond(['message' => 'El recurso no es válido'], 400);
        }

        $builder = $db->table($resource);

        if (!empty($lastUpdate)) {
            $builder->where('updated_at >', $lastUpdate);
        }

        // Obtenemos los registros del lote
        $offset = ($batch - 1) * $limit;
        $records = $builder->get($limit, $offset)->getResultArray();

        return $this->respond($records);



    }
}

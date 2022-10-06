<?php

/**
 * This file is part of the SYSTEM_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refer to LICENSE file
 * that has been distributed with this source code.
 */

namespace App\Models;

use App\Entities\Route;
use App\Models\CustomModel;

/**
 * Modelo `RouteModel`
 * 
 * Administra la interaccion con la base de datos de la tabla route
 * 
 * @package  API_CI4
 * @category Model
 * @author   Wilber Méndez <mendezwilberdev@gmail.com>
 */
class RouteModel extends CustomModel
{
    protected $table          = TBL_ROUTE;
    protected $primaryKey     = 'id_route';
    protected $uuidFields     = ['id_route'];
    protected $returnType     = Route::class;
    protected $useSoftDeletes = true;
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';
    protected $allowedFields  = [
        'id_route',
        'id_parent_route',
        'label',
        'name',
        'path',
        'icon',
        'priority',
        'type',
        'show_in_menu',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * Función recursiva para obtener las rutas con sus hijos
     * 
     * Primero obtiene las rutas padre, luego para cada ruta padre obtiene sus hijos
     * y así sucesivamente hasta que no haya más hijos.
     * 
     * 
     * @return array
     */
    public function getRoutes(array $permissions, string $filter = "")
    {

        $this->where('id_parent_route', null)
            ->where('is_active', true)
            ->orderBy('priority', 'ASC');

        if ($filter && $filter != 'ALL') {
            $this->groupStart()
                ->where('type', $filter)
                ->orWhere('type', 'ALL')
            ->groupEnd();
        }

        $routes = $this->findAll();

        foreach ($routes as $route) {
            $route->children = $this->getChildren($route->id_route, $permissions, $filter);
        }

        return $routes;
    }

    /**
     * Obtiene los hijos de una ruta verificando que el usuario tenga permisos
     * para acceder a esa ruta
     * 
     * @param string $id_route
     * @return array
     */
    private function getChildren($id_route, array $permissions, string $filter = '')
    {
        $this->where('id_parent_route', $id_route)
            ->where('is_active', true)
            ->orderBy('priority', 'ASC');

        if ($filter && $filter != 'ALL') {
            $this->groupStart()
                ->where('type', $filter)
                ->orWhere('type', 'ALL')
            ->groupEnd();
        }

        $children = $this->findAll();

        foreach ($children as $child) {
            $child->children = $this->getChildren($child->id_route, $permissions);
        }

        // Verifica que el usuario tenga permisos para acceder a la ruta
        $children = array_filter($children, function ($child) use ($permissions) {

            // Obtenemos los permisos asociados a la ruta
            $routePermissionModel = model('RoutePermissionModel');
            $routePermissions = $routePermissionModel
                ->where('id_route', $child->id_route)->find();

            // Si la ruta no tiene permisos, se retorna la ruta
            if (empty($routePermissions)) {
                return true;
            }

            // Si la ruta tiene permisos, se verifica que el usuario tenga
            // alguno de los permisos para acceder a la ruta
            foreach ($routePermissions as $routePermission) {
                if (in_array($routePermission->id_permission, $permissions)) {
                    return true;
                }
            }
        });

        return $children;
    }
}

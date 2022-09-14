<?php

/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refer to LICENSE file
 * that has been distributed with this source code.
 */

if (!function_exists('getPage')) {
    /**
     * 
     * Obtiene el numero de pagina a partir de los query params
     *
     * @param \CodeIgniter\HTTP\IncomingRequest $request
     *
     * @return int
     */
    function getPage($request): int
    {
        if (null !== ($request->getVar("page"))) {

            $page = (int) $request->getVar("page") != 0
                ? (int) $request->getVar("page")
                : 1;

            return $page;
        }

        return 0;
    }
}

if (!function_exists('getRecordsPerPage')) {
    /**
     * 
     * Obtiene el numero de regidtros pagina a partir de los query params
     *
     * @param \CodeIgniter\HTTP\IncomingRequest $request
     *
     * @return int
     */
    function getRecordsPerPage($request): int
    {
        if (null !== ($request->getVar("records_per_page")) && ($request->getVar("records_per_page")) >= 1) {

            $records_per_page = (int) $request->getVar("records_per_page") != 0
                ? (int) $request->getVar("records_per_page")
                : RECORDS_PER_PAGE;

            return $records_per_page;
        }

        return 10;
    }
}

if (!function_exists('getSortBy')) {
    /**
     * Obtiene el nombre de la columna por la que se ordenaran los resultados
     *
     * @param \CodeIgniter\HTTP\IncomingRequest $request
     * @return string
     */
    function getSortBy($request): string
    {
        if (null !== ($request->getVar("sort_by"))) {
            return $request->getVar("sort_by");
        }
        return "created_at";
    }
}

if (!function_exists('getOrderBy')) {
    /**
     * Obtiene la forma en la que se ordenara los resultados
     *
     * @param \CodeIgniter\HTTP\IncomingRequest $request
     * @return string
     */
    function getOrderBy($request): string
    {
        if (null !== ($request->getVar("order_by"))) {
            if ($request->getVar("order_by") == "asc" || $request->getVar("order_by") == "ASC") {
                return "ASC";
            }
            if ($request->getVar("order_by") == "desc" || $request->getVar("order_by") == "DESC") {
                return "DESC";
            }
            return $request->getVar("order_by");
        }
        return "ASC";
    }
}

if (!function_exists('getQueryParams')) {
    /**
     * Retorna todos los parametros necesarios pra hacer una consulta de datos
     *
     * @param \CodeIgniter\HTTP\IncomingRequest $request
     * @return array
     */
    function getQueryParams($request): array
    {
        $data = ["page" => 1, "records_per_page" => 10, "sort_by" => "created_at", "order_by" => "ASC", "filters" => []];

        $data["filters"]          = $request->getVar();
        $data["page"]             = getPage($request);
        $data["records_per_page"] = getRecordsPerPage($request);
        $data["sort_by"]          = getSortBy($request);
        $data["order_by"]         = getOrderBy($request);

        return $data;
    }
}

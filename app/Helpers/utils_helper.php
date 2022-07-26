<?php
/**
 * This file is part of the API_CI4.
 *
 * (c) Wilber Mendez <mendezwilber94@gmail.com>
 *
 * For the full copyright and license information, please refere to LICENSE file
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
    function getPage($request) : int
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
    function getRecordsPerPage($request) : int
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

?>
<?php
if ( !function_exists('json_parse') ) {

    /**
     * converte uma colecao de dados para json
     *
     * @param $collection
     * @return mixed
     */
    function json_parse($collection): mixed
    {
        return json_decode(json_encode($collection), FALSE);
    }
}

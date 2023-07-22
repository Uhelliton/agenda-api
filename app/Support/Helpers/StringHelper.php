<?php
if ( !function_exists('isValidDate') ) {

    /**
     * @param string $date
     * @return bool
     */
    function isValidDate(string $date): bool
    {
       return (strtotime($date) !== false);
    }
}

<?php

/**
 * Recursively removes all empty elements from the given array. An element is
 * considered empty if it does not exist or if its value equals FALSE. Uses
 * empty() internally.
 * 
 * @param array $array
 * @return array Array with empty elements removed
 */
function array_remove_empty($array) {

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $array[$key] = array_remove_empty($array[$key]);
        }
        if (empty($array[$key])) {
            unset($array[$key]);
        }
    }
    return $array;
}



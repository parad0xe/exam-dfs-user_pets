<?php

if(!function_exists("endsWith")) {
    function endsWith($in, $search) {
        return substr($in, -strlen($search)) === $search;
    }
}

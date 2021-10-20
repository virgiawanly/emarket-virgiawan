<?php

/**
 * Cek apakah link sedang aktif atau tidak
 *
 * @return boolean
 */
function is_active_link(...$routes){
    foreach($routes as $route){
        if(request()->is($route)){
            return 'active';
        }
    }
    return '';
}

?>

<?php
/* 
 * Core of back side
 * Author: alexman92
 */

require_once 'Router.php';
if(isset($_REQUEST) && is_array($_REQUEST) && (count($_REQUEST) > 0) ) {

   $secured_post = secure($_REQUEST);
   $out = Router::route($secured_post);

   echo $out;
}

/**
 * checks input
 */
function secure(array $data) {
    $secured_arr = array();
    foreach($data as $key=> $value) {
        if(!is_array($value)) {
            $secured_arr[$key] = htmlspecialchars(strip_tags($value));
        } else {
           $secured_arr[$key] = $value;
        }
    }
    
    return $secured_arr;
}
<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WPSP_Hash_Auth {
    
    public function getHash($data){
        $salt = wp_salt();
        return hash_hmac('md5', $data, $salt);
    }
    
    public function checkAuth($data,$auth){
        $salt = wp_salt();
        if( hash_hmac('md5', $data, $salt) == $auth ){
            return true;
        } else {
            return false;
        }
    }
    
}
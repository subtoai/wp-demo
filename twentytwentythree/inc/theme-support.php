<?php

/*

@package sub2ai

    ========================
    THEME SUPPORT OPTIONS
    ========================
*/

if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool
    {
        $needle_len = strlen($needle);
        return ($needle_len === 0 || 0 === substr_compare($haystack, $needle, - $needle_len));
    }
}

add_filter( 'rest_authentication_errors', 'rest_authentication_filter_incoming_connections' );

function rest_authentication_filter_incoming_connections( $errors ) {

    if (!current_user_can('manage_options')) {
        // Bail if rest_route isn't defined (shouldn't happen!)
        if (empty($GLOBALS['wp']->query_vars['rest_route'])) {
            return $errors;
        }
        $request_uri = $_SERVER['REQUEST_URI'];
        if(str_ends_with($request_uri, "/wp-json") || str_ends_with($request_uri, "wp-json/")) {
            return new WP_Error('forbidden_access', 'Access denied', $request_uri);
        }

        $route = ltrim($GLOBALS['wp']->query_vars['rest_route'], '/');

        $pos = strpos($route, 'wp/v2');
        if($pos === false) {
            return $errors;
        }
        if (isset($pos) && $pos >= 0 ) {
            return new WP_Error('forbidden_access', 'Access denied', $route);
        }
    }

    return $errors; 

}

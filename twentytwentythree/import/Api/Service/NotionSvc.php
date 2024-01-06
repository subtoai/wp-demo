<?php

/**
 * @package  tuco
 */

namespace Import\Api\Service;

// Create an api context by passing in the desired context (Contacts, Forms, Pages, etc), the $auth object from above
// and the base URL to the Mautic server (i.e. http://my-mautic-server.com/api/)

class NotionSvc
{
    public function register()
    {
        add_action(
            'rest_api_init',
            function () {

                // option 1: https://vip.sub2ai.com/?rest_route=/notion/auth/v1/callback
                // option 2: https://vip.sub2ai.com/wp-json/notion/auth/v1/callback
                register_rest_route('notion/auth/v1', '/callback', array(
                    'methods' => ['POST', 'GET'],
                    'callback' => array($this, 'getToken'),
                    'permission_callback' => function () {
                        return true; // security can be done in the handler
                    }
                    // https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/#permissions-callback
                    // 'permission_callback' => function () {
                    //     return current_user_can( 'edit_others_posts' );
                    // }
                ));

                // https://vip.sub2ai.com/?rest_route=/webhook/notion/callback
                register_rest_route('webhook/notion', '/callback', array(
                    'methods' => 'GET',
                    'callback' => array($this, 'debug'),
                    'permission_callback' => function () {
                        return true; // security can be done in the handler
                    }
                    // https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/#permissions-callback
                    // 'permission_callback' => function () {
                    //     return current_user_can( 'edit_others_posts' );
                    // }
                ));
            }
        );
    }

    function debug(\WP_REST_Request $request)
    {
        $tokenData = [
            "status" => 200
        ];   
        return static::send_response($tokenData);
    }

    function getToken(\WP_REST_Request $request)
    {
        //$provision_id = $request->get_param("id");
        try {

            $payload = @file_get_contents('php://input');
            //$data = \json_decode($payload, true);

            // // $provision_id = $data["id"];
            // // $meta_key = $data["key"];
            // // $meta_value = (isset($data["value"]) && !empty($data["value"])) ? $data["value"] : "";
            // $meta_key = $request->get_param("key");
            // $meta_value = $request->get_param("value");
            // $meta_value = (isset($meta_value) && !empty($meta_value)) ? $meta_value : "";

            // $meta_sector = $request->get_param("sector");
            // $meta_append = $request->get_param("append");

            //$data = $request->get_body();
            $code = $request->get_param("code");
            //file_put_contents("/tmp/logs/authCallback.log", $code);
            //HttpUtil::send_response(array("code" => $code));
            //$tokenData = $this->marketAuthHelper->resetUserApiServiceToken($code);
            // $contents = $response; //->getBody()->getContents();
            // $result = $contents->access_token;

            $res = ["code" => $code];
            return static::send_response($res);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            static::send_response(array("status" => $e->getResponse()->getBody()->getContents()));
        }
    }

    public static function send_response($resp)
    {
        wp_send_json($resp);
        wp_die();
    }

}
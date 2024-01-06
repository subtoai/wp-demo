<?php

namespace Import;

final class Init
{

    function __construct()
    {
    }

    /**
     * store all the classes inside an array
     * @return array full list of classes
     */
    public static function get_services()
    {
        return [
            Api\Service\NotionSvc::class,
        ];
    }

    /**
     * loop through the classes, initialize them,
     * and call the register() method if exists
     */

    public static function register_services()
    {
        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);
            if (method_exists($service, 'register')) {
                $service->register();
            }
        }
    }


    /**
     * Initialize the class
     * @param class $class  class from the service array
     * @return class instance new instance of the class
     */
    private static function instantiate($class)
    {
        $service = new $class();
        return $service;
    }
}
<?php

namespace Shridhar\Angular\Facades;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Traits\Macroable;
use Shridhar\Bower\Bower;
use Shridhar\Bower\Asset;
use Exception;

/**
 * Description of App
 *
 * @author Shridhar
 */
class App {

    use Macroable;

    protected static $__config = [];

    function getConfig($key) {
        return array_get(static::$__config, $key);
    }

    function setConfig($key, $value = null) {
        if (is_array($key)) {
            static::$__config = array_replace_recursive(static::$__config, $key);
        } else {
            array_set(static::$__config, $key, $value);
        }
    }

    public function __construct($data) {
        $this->setConfig($data);
    }

    static function get($data) {
        return app()->makeWith(__CLASS__, [
            "data" => $data
        ]);
    }

    static function getByName($name) {
        $config = static::getAllApps()->where("name", $name)->first();
        if (!$config) {
            throw new Exception("App config not found.");
        }
        return static::get($config);
    }

    static function getAllApps() {
        return collect(config("angular.apps"));
    }

}

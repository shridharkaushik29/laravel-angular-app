<?php

namespace Shridhar\Angular\Facades;

use Illuminate\Support\Facades\Route;
use Shridhar\Bower\Bower;
use Shridhar\Bower\Asset;

/**
 * Description of App
 *
 * @author Shridhar
 */
class App {

    protected $index_file = "index", $files_dir = "files", $apps_view_dir, $html, $assets_loaded = [];
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

    function rename($new_name) {
        $name = $this->name();
        if (file_exists(resource_path("views/angular-apps/$name"))) {
            rename(resource_path("views/angular-apps/$name"), resource_path("views/angular-apps/$new_name"));
        }

        if (file_exists(public_path("assets/$name"))) {
            rename(public_path("assets/$name"), public_path("assets/$new_name"));
        }

        if (file_exists(config_path("shridhar/angular-apps/$name.php"))) {
            rename(config_path("shridhar/angular-apps/$name.php"), config_path("shridhar/angular-apps/$new_name.php"));
        }
    }

    function bootstrap() {
        return $this->index();
    }

    function index() {
        return $this->file("index");
    }

    function file($path) {
        return $this->view("files/$path");
    }

    function view($path, $vars = []) {
        $variables = collect($vars);
        $variables->put("app", $this);
        $variables->put("html", $this->html());
        $views_path = $this->getConfig("views.path") ?: $this->name();
        $view = view("$views_path/$path", $variables->toArray());
        return $view;
    }

    function getVar($key) {
        return $this->getConfig("vars.$key");
    }

    function bower() {
        $bower = $this->getConfig("bower") ?: [];
        return Bower::make($bower);
    }

    function html() {
        return app()->makeWith(Html::class, [
                    "app" => $this
        ]);
    }

    function vars($key = null) {
        return $this->getConfig("vars.$key");
    }

    function asset($name, $type = null, $baseUrl = null, $basePath = null) {
        $app = $this->getConfig("name");
        $asset_path = $this->getConfig("assets.$name") ?: $name;

        $base_path = $basePath ?: $this->getConfig("assets.path") ?: public_path("assets/$app");
        $base_url = $baseUrl ?: $this->getConfig("assets.url") ?: url("assets/$app");

        $path = "$base_path/$asset_path";
        $url = "$base_url/$asset_path";

        $this->assets_loaded[] = [
            "full_path" => $path,
            "base_url" => $base_url,
            "base_path" => $base_path,
            "name" => $name
        ];

        return Asset::make([
                    "path" => $path,
                    "url" => $url,
                    "type" => $type,
        ]);
    }

    function loadedAssets() {
        return $this->assets_loaded;
    }

    function assetGlobal($asset_path, $type = null) {
        $base_url = asset("");
        $base_path = public_path("");
        return $this->asset($asset_path, $type, $this->getConfig("assets.global.url") ?: $base_url, $this->getConfig("assets.global.path") ?: $base_path);
    }

    function assetExternal($url, $type = null) {
        return Asset::make([
                    "url" => $url,
                    "type" => $type
        ]);
    }

    function template($path) {
        return app()->makeWith(Template::class, [
                    "path" => "templates/$path",
                    "vars" => $this->getConfig("templates.vars"),
                    "app" => $this
        ]);
    }

    function name() {
        return $this->getConfig("name");
    }

    function title() {
        return $this->getConfig("title");
    }

    function viewsPath($path = null) {
        $vpath = $this->getConfig("views.path") ?: $this->name();
        if (!empty($path)) {
            $vpath .= "/$path";
        }
        return $vpath;
    }

    function assets() {
        return $this->getConfig("assets");
    }

    function templatesUrl() {
        $name = $this->getConfig("name");
        return $this->getConfig("templates.url") ?: route("templates_$name", [
                    "path" => ""
        ]);
    }

    function templatesPath() {
//        return base
    }

    function templatesExtension() {
        return $this->getConfig("templates.extension");
    }

    function dependencies() {
        $dependencies = $this->getConfig("dependencies");
        return collect($dependencies);
    }

    function siteUrl() {
        $name = Route::currentRouteName();
        $url = $this->getConfig("site.url");
        if (!$url && Route::has($name)) {
            $url = route($name);
        }
        return $url;
    }

    function servicesUrl() {
        $name = $this->name();
        return $this->getConfig("services.url") ?: route("services_$name", [
                    "path" => ""
        ]);
    }

}

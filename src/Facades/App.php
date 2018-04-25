<?php

namespace Shridhar\Angular\Facades;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Shridhar\Bower\Bower;
use Shridhar\Bower\Asset;

/**
 * Description of App
 *
 * @author Shridhar
 */
class App {

    protected $index_file = "index", $files_dir = "files", $apps_view_dir, $html;
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

    function asset($name, $type = null) {
        $app = $this->getConfig("name");
        $asset_path = $this->getConfig("assets.$name") ?: $name;

        $base_path = $this->getConfig("assets.path") ?: public_path("assets/$app");
        $base_url = $this->getConfig("assets.url") ?: url("assets/$app");

        $path = "$base_path/$asset_path";
        $url = "$base_url/$asset_path";
        return Asset::make([
                    "path" => $path,
                    "url" => $url,
                    "type" => $type,
        ]);
    }

    function assetGlobal($asset_path, $type = null) {
        $path = public_path($asset_path);
        $url = asset($asset_path);
        return Asset::make([
                    "path" => $path,
                    "url" => $url,
                    "type" => $type,
        ]);
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

    function templatesExtension() {
        return $this->getConfig("templates.extension");
    }

    function dependencies() {
        $dependencies = $this->getConfig("dependencies");
        return collect($dependencies);
    }

    function siteUrl() {
        $name = Route::currentRouteName();
        return $this->getConfig("site.url") ?: route($name);
    }

    function servicesUrl() {
        $name = $this->name();
        return $this->getConfig("services.url") ?: route("services_$name", [
                    "path" => ""
        ]);
    }

}

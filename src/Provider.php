<?php

namespace Shridhar\Angular;

use function foo\func;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Shridhar\Angular\Facades\App;
use Shridhar\Angular\Facades\Html;
use Shridhar\Bower\Bower;
use Shridhar\Bower\Asset;
use Illuminate\Support\Facades\Route;
use Collective\Html\HtmlFacade;

class Provider extends ServiceProvider {

    protected $files = [];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        $this->loadRoutesFrom(__DIR__ . "/routes.php");
        $this->loadViewsFrom(__DIR__ . "/views", "angular");

        \Illuminate\Routing\Route::macro("app", function () {
            $this->where("path", ".*");
        });

        App::macro("templatesUrl", function () {
            $name = $this->getConfig("name");
            return $this->getConfig("templates.url") ?: route("templates_$name", [
                "path" => ""
            ]);
        });

        App::macro("siteUrl", function () {
            $name = Route::currentRouteName();
            $url = $this->getConfig("site.url");
            if (!$url && Route::has($name)) {
                $url = route($name);
            }
            return $url;
        });

        App::macro("servicesUrl", function () {
            $name = $this->name();
            $route_name = "services_$name";
            $url = $this->getConfig("services.url");
            if (Route::has($route_name) && !$url) {
                $url = route($route_name, [
                    "path" => ""
                ]);
            }
            return $url;
        });

        App::macro("jsVars", function () {
            $vars = $this->getConfig("js.vars");
            return collect([
                '$appName' => $this->name(),
                '$appTitle' => $this->title(),
                '$siteUrl' => $this->siteUrl(),
                '$templateUrl' => $this->templatesUrl(),
                '$templateExtension' => $this->templatesExtension(),
                '$servicesUrl' => $this->servicesUrl()
            ])->merge($vars);
        });

        App::macro("templatesExtension", function () {
            return $this->getConfig("templates.extension");
        });

        App::macro("title", function ($title = null) {
            if ($title) {
                $name = $this->name();
                return "$title | $name";
            } else {
                return $this->getConfig("title");
            }
        });

        App::macro("name", function () {
            return $this->getConfig("name");
        });

        App::macro("html", function () {
            return app()->makeWith(Html::class, [
                "app" => $this
            ]);
        });

        App::macro("bower", function () {
            $bower = $this->getConfig("bower") ?: [];
            return Bower::make($bower);
        });

        App::macro("asset", function ($name, $type = null, $baseUrl = null, $basePath = null) {
            $app = $this->getConfig("name");
            $asset_path = $this->getConfig("assets.$name") ?: $name;

            $base_path = $basePath ?: $this->getConfig("assets.path") ?: public_path("assets/$app");
            $base_url = $baseUrl ?: $this->getConfig("assets.url") ?: url("assets/$app");

            $path = "$base_path/$asset_path";
            $url = "$base_url/$asset_path";

            return Asset::make([
                "path" => $path,
                "url" => $url,
                "type" => $type,
            ]);
        });

        App::macro('loadedAssets', function () {
            return $this->assets_loaded;
        });

        Html::macro("title", function ($title) {
            return "<title>" . $this->ng_app->title($title) . "</title>";
        });

        Html::macro("responsive_meta", function () {
            return HtmlFacade::meta("viewport", "width=device-width, height=device-height, initial-scale=1, viewport-fit=cover");
        });

        Html::macro("google_font", function ($name, $params = []) {
            return Asset::googleFont($name, $params)->tag();
        });

        Html::macro("html5Mode", function () {
            $html5Mode = $this->ng_app->getConfig("html5Mode");
        });

        Html::macro("favicon", function ($path = null) {
            $app = $this->ng_app;
            $path = $path ?: $app->getConfig("favicon");
            $url = $app->asset($path)->url();
            return "<link href=\"$url\" rel=\"shortcut icon\">";
        });

        Blade::directive("angular", function ($name) {
            return '<?php $app = ' . App::class . '::getByName("' . $name . '");$app->setConfig("site.url", isset($url) ? $url : url("/")) ?>';
        });

        Blade::directive("title", function ($title) {
            return '<?= $app->html()->title("' . $title . '") ?>';
        });

        Blade::directive("responsive", function ($title) {
            return '<?= $app->html()->responsive_meta() ?>';
        });

        Blade::directive("favicon", function ($path) {
            return '<?php echo $app->html()->favicon("' . $path . '") ?>';
        });

        Blade::directive("mainScript", function ($title) {
            return '<?= view("angular::main-script", ["app"=>$app]) ?>';
        });

        Blade::component("angular::main-script", "mainScript");

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {

    }

}

<?php

namespace Shridhar\Angular;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AngularProvider extends ServiceProvider {

    protected $files = [];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {

        $this->loadViewsFrom(__DIR__ . "/views", "angular");

        App::macro("jsVars", function () {
            /** @var App $this */
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

        Blade::directive("angular", function ($name) {
            return '<?php $app = ' . App::class . '::getByName(' . $name . '); ?>';
        });

        Blade::directive("url", function ($url) {
            return '<?php $app->setUrl(' . $url . '); ?>';
        });

        Blade::directive("route", function ($route) {
            return '<?php $app->setRoute(' . $route . '); ?>';
        });

        Blade::directive("servicesRoute", function ($route) {
            return '<?php $app->setServicesRoute(' . $route . '); ?>';
        });

        Blade::directive("servicesUrl", function ($url) {
            return '<?php $app->setServicesUrl(' . $url . '); ?>';
        });

        Blade::directive("title", function ($title) {
            return '<?php $app->setTitle(' . $title . '); ?>';
        });

        Blade::include("angular::main-script", "vars");

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {

    }

}

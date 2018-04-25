<?php

use Shridhar\Angular\Facades\App;
use Illuminate\Support\Facades\Artisan;

$apps = collect(config("angular.apps"))->sortBy("order");

$apps->each(function($app) {

    $name = array_get($app, "name");
    $route = array_get($app, "site.route");
    $routes = collect(array_get($app, "site.routes"));
    if ($route) {
        $routes->push($route);
    }

    $services_controller = array_get($app, "services.controller") ?: "App\Http\Controllers\\" . ucwords(camel_case($name)) . "Services";
    $services_route = array_get($app, "services.route") ?: "services/$name/{path}";
    $templates_route = array_get($app, "templates.route") ?: "templates/$name/{path}";

    Route::get($templates_route, function($path) use ($app) {
        $app = App::get($app);
        $template = $app->template($path);
        return $template;
    })->where('path', '.*')->name("templates_$name");


    Route::group(['middleware' => ['web']], function () use($services_controller, $services_route, $name) {
        if (class_exists($services_controller)) {
            Route::any($services_route, $services_controller)->where('path', '.*')->name("services_$name");
        }
    });

    $routes->each(function($route, $key) use($app, $name) {
        $route_name = "bootstrap-$name-$key";
        Route::get($route, function() use($app) {
            $app = App::get($app);
            return $app->bootstrap();
        })->where("path", ".*")->name($route_name);
    });
});

Artisan::command("bower:install {name}", function($name) {
    $assets_path = public_path("assets");
    @mkdir($assets_path);
    chdir($assets_path);
    system("bower install $name");
});

Artisan::command("bower:uninstall {name}", function($name) {
    $assets_path = public_path("assets");
    @mkdir($assets_path);
    chdir($assets_path);
    system("bower uninstall $name");
});

# laravel-angular-apps

This plugin provides a great eloquent way of creating angularjs applications, run them in the browser and quickly create an Android and iOS app using cordova.

<b>Installation:</b>
<hr>

Just run the following command in terminal:

<code>composer require shridharkaushik29/laravel-angular-app</code>

<b>Usage:</b>
<hr>

1. Add the provider in <code>config/app.php</code>

<pre>
"providers" => [
    Shridhar\Angular\Provider::class
]
</pre>

2. Create a config file <code>angular.php</code> in <code>app/config</code> directory, like below.

<pre>

return [
    "apps" => [                //Array containing configs for all the individual apps
            [
            "name" => "saving-mantra",
            "html5Mode" => true,
            "dependencies" => [
                "ngMaterial",
                "ui.router",
                "ngCrud",
                "ngRouteTitle",
                "ngFileInput",
                "ngLightgallery",
                "ionic.native"
            ],
            "title" => "Saving Mantra",
            "bower" => [
                "components" => [
                    "jquery",
                    "animate.css",
                    "bootstrap",
                    "font-awesome",
                    "angular",
                    "angular-material",
                    "angular-ui-router",
                    "ng-route-title",
                    "ng-files",
                    "ng-lightgallery",
                    "lg-zoom",
                    "lg-thumbnail",
                    "ng-crud",
                    "ionic-native"
                ]
            ],
            "site" => [
                "routes" => [
                    "{path?}" // Route pattern, which follows standard route pattern in laravel
                ]
            ],
        ]
    ]
]

</pre>

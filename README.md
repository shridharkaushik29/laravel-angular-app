# laravel-angular-apps

This plugin provides a great eloquent way of creating HTML5 applications with AngularJS, Angular, ReactJS, Vue and many more.

Don't go with the name of the package i.e., `laravel-angular-apps`, beacause initially, this package was designed to create only AngularJS applications, but now it's objective is to create, build and load assets in an elegant way in Laravel.

**Installation:**

`composer require shridharkaushik29/laravel-angular-app`

**Usage:**

1. Add the provider in `config/app.php`. Skip this step if you are using Laravel version greater than 5.4.

```
"providers" => [
    Shridhar\Angular\Provider::class
]
```

2. Create a config file <code>angular.php</code> in <code>app/config</code> directory, like below.

```
return [
    "apps" => [                //Array containing configs for all the individual apps
        [
            "name" => "welcome",  //This name will be used to compile and load assets for the application.
            "favicon" => "angular.png",
            "html5Mode" => true,
            "title" => "Laravel"
        ]
    ]
]
```


**Creating multiple apps per Laravel Installation.**

This package offers to create multiple Webpack applications in a single Laravel installation. For example, if you want to create an E-Commerce application, then it would require a separate application to manage the content for the front end application. This is handled very efficiently in this package.

To create multiple apps, just add more app in the `apps` key in the configuration array, like following:

```
return [
    "apps" => [                //Array containing configs for all the individual apps
        [
            "name" => "welcome",  //This name will be used to compile and load assets for the application.
            "favicon" => "angular.png",
            "html5Mode" => true,
            "title" => "Laravel"
        ],
        [
            "name" => "admin",  //This name will be used to compile and load assets for the application.
            "favicon" => "angular.png",
            "html5Mode" => false,
            "title" => "Laravel Admin"
        ]
    ]
]
```

**Creating Blade View**

```
@angular(welcome)
<html>
<head>
    @title
    @favicon
    @responsive
    @include("angular::main-script")
    {!!$app->html()->google_font("Raleway")!!}
    <link href="{{$app->asset("style.css")->url()}}" rel="stylesheet"/>
</head>
<body>
<div class="pre-loader">
    <img class="logo" src="{{url("storage/laravel-logo.png")}}">
    <p>Loading Application</p>
</div>
</body>
<script src="{{$app->asset("runtime.js")->url()}}" async></script>
<script src="{{$app->asset("vendor.js")->url()}}" async></script>
<script src="{{$app->asset("main.js")->url()}}" async></script>
</html>
```

**Loading View**

There's nothing special required to load views, it's as usual.


```
Route::get('/', function () {
    return view('welcome');
});
```

**Creating Assets**

This package assumes a folder with the app name in `public/assets` directory, where all of the assets are placed for that application.

For example, if an app is having name `welcome`, then there should be a directory `public/assets/welcome` containing all the javascript, css, images and other assets required by that application.

**Loading Assets**

`$app->asset("path/for/asset")` will return an instance for `Shridhar\Bower\Asset` class. It contains an `url()` method which return an absolute url for that asset.


```
<link href="{{$app->asset("style.css")->url()}}" rel="stylesheet"/> It will return url for the file located at 'public/assets/welcome/style.css'
<script src="{{$app->asset("runtime.js")->url()}}" async></script>
```
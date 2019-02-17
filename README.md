# laravel-angular-app

This plugin provides a great eloquent way of creating HTML5 applications with AngularJS, Angular, ReactJS, Vue and many more.

Don't go with the name of the package i.e. `laravel-angular-app`, because initially, this package was designed to create only AngularJS applications, but now it's objective is to create, build and load assets in an elegant way in Laravel.

**Installation:**

`composer require shridharkaushik29/laravel-angular-app`

**Usage:**

Add the provider in `config/app.php`. Skip this step if you are using Laravel version greater than 5.4.

```php
"providers" => [
    Shridhar\Angular\Provider::class
]
```


**Creating multiple apps per Laravel Installation.**

This package offers to create multiple webpack applications in a single Laravel installation. For example, if you want to create an E-Commerce application, then it would require a separate application to manage the content for the front end application. This is handled very efficiently in this package because you can create as many applications you want with different blades.

**Creating Blade View**

```php+HTML
@angular("react")
{{--Used to set app name--}}
@route("react")
{{--Used to define site route name--}}
@title("Laravel React")
{{--Used to define application title--}}
@servicesRoute("services")
{{--Used to define services route, when defined a $servicesUrl variable is defined in javascript--}}
<html lang="en">
<head>
    <title>{{$app->title()}}</title>
    <base href="{{$app->url()}}/">
    <link href="{{asset("img/laravel-developer.png")}}" rel="shortcut icon"/>
    <link href="{{$app->asset("style.css")->url()}}" rel="stylesheet"/>
    @vars()
    {{--Used to print <script> tag containing javascript variables--}}
</head>
<body>
<div id="app"></div>
<div class="pre-loader" ng-if="showPreloader">
    <img class="logo" src="{{asset("storage/laravel-logo.png")}}">
    <p>Loading Application</p>
</div>
<script src="{{$app->asset("runtime.js")->url()}}" async></script>
<script src="{{$app->asset("vendor.js")->url()}}" async></script>
<script src="{{$app->asset("main.js")->url()}}" async></script>
</body>
</html>
```

**Loading View**

There's nothing special required to load views, it's as usual.


```php
Route::get('/', function () {
    return view('welcome');
});
```

**Creating Assets**

This package assumes a folder with the app name in `public/assets` directory, where all of the assets are placed for that application.

For example, if an app is having name `welcome`, then there should be a directory `public/assets/welcome` containing all the javascript, css, images and other assets required by that application.

**Loading Assets**

`$app->asset("path/for/asset")` will return an instance for `Shridhar\Bower\Asset` class. 

It contains an `url()` method which return an absolute url for that asset.


```php+HTML
<link href="{{$app->asset("style.css")->url()}}" rel="stylesheet"/> 
<script src="{{$app->asset("runtime.js")->url()}}" async></script>
<img src="{{$app->asset("logo.jpg")->url()}}">
```
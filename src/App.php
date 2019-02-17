<?php

namespace Shridhar\Angular;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Exception;

/**
 * Description of App
 *
 * @author Shridhar
 */
class App {

    use Macroable;

    protected $name;
    protected $url;
    protected $route;
    protected $title;
    protected $assets_path;
    protected $assets_url;
    protected $services_controller;
    protected $services_url;
    protected $services_route;
    /**@var Collection* */
    protected $js_vars;

    /**
     * App constructor.
     * @param $name
     * @param $title
     * @param $assets_url
     * @param $assets_path
     * @param $services_url
     * @param $services_route
     * @param $services_controller
     */
    public function __construct($name, $title = null, $assets_url = null, $assets_path = null, $services_url = null, $services_route = null, $services_controller = null) {
        $this
            ->setName($name)
            ->setTitle($title)
            ->setAssetsUrl($assets_url)
            ->setAssetsPath($assets_path)
            ->setServicesUrl($services_url)
            ->setServicesController($services_controller)
            ->setServicesRoute($services_route);
        $this->js_vars = collect();
    }

    /**
     * @param $data
     * @return App
     */
    static function get($data) {
        $app = app()->makeWith(App::class, $data);
        return $app;
    }

    /**
     * @param $name
     * @return App
     * @throws Exception
     */
    static function getByName($name) {
        $config = static::getAllApps()->where("name", $name)->first() ?: [
            "name" => $name
        ];
        return static::get($config);
    }

    /**
     * @return Collection
     */
    static function getAllApps() {
        return collect(config("angular.apps"));
    }

    /**
     * @param string $assets_path
     * @return App
     */
    public function setAssetsPath($assets_path) {
        $this->assets_path = $assets_path;
        return $this;
    }

    /**
     * @param string $assets_url
     * @return App
     */
    public function setAssetsUrl($assets_url) {
        $this->assets_url = $assets_url;
        return $this;
    }

    /**
     * @param string $services_url
     * @return App
     */
    public function setServicesUrl($services_url) {
        $this->services_url = $services_url;
        return $this;
    }

    /**
     * @param string $services_route
     * @return App
     */
    public function setServicesRoute($services_route) {
        $this->services_route = $services_route;
        return $this;
    }

    /**
     * @param string $title
     * @return App
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $name
     * @return App
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $services_controller
     * @return App
     */
    public function setServicesController($services_controller) {
        $this->services_controller = $services_controller;
        return $this;
    }

    /**
     * @param null $path
     * @return string
     */
    public function getAssetsPath($path = null) {
        return $this->assets_path ?: public_path("assets/$this->name/$path");
    }

    /**
     * @return string
     */
    public function name() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function url() {
        if ($this->route) {
            return route($this->route);
        } else if ($this->url) {
            return $this->url;
        } else {
            return url("");
        }
    }

    /**
     * @return string
     */
    public function getServicesUrl() {
        if ($this->services_route) {
            return route($this->services_route, [
                "path" => ""
            ]);
        } else if ($this->services_url) {
            return $this->services_url;
        }
    }

    /**
     * @return string
     */
    public function getAssetsUrl() {
        return $this->assets_url ?: asset("assets/$this->name");
    }


    /**
     * @param $name
     * @return Asset
     */
    public function asset($name) {
        return app()->makeWith(Asset::class, [
            "name" => $name,
            "base_url" => $this->getAssetsUrl(),
            "base_path" => $this->getAssetsPath()
        ]);
    }

    /**
     * @param mixed $url
     * @return App
     */
    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function title() {
        return $this->title;
    }

    /**
     * @param mixed $js_vars
     * @return App
     */
    public function setJsVar($key, $value) {
        $this->js_vars->put($key, $value);
        return $this;
    }

    /**
     * @return Collection
     */
    public function js_vars(): Collection {
        $this
            ->setJsVar('$appName', $this->name())
            ->setJsVar('$siteUrl', $this->url())
            ->setJsVar('$assetsUrl', $this->getAssetsUrl());

        $services_url = $this->getServicesUrl();
        if ($services_url) {
            $this->setJsVar('$servicesUrl', $services_url);
        }
        return $this->js_vars;
    }

    /**
     * @return mixed
     */
    public function getJsVar($key) {
        return $this->js_vars->get($key);
    }

    /**
     * @param mixed $route
     * @return App
     */
    public function setRoute($route) {
        $this->route = $route;
        return $this;
    }

}

<?php

namespace Shridhar\Angular\Facades;

/**
 * Description of Template
 *
 * @author Shridhar
 */
class Template extends File {

    protected $vars, $viewPath, $app, $base_url;

    public function __construct($path, $app, $vars = null, $url = null) {
        $this->viewPath = $path;
        $path = base_path("resources/views/$path");
        $this->vars = $vars ?: [];
        $this->app = $app;
        $this->base_url = $this->app->templatesUrl();
        parent::__construct($path, $this->url());
    }

    function url() {
        return "$this->base_url/$this->viewPath";
    }

    function view() {
        return $this->app->view($this->viewPath, $this->vars);
    }

    function viewPath(){
        return $this->viewPath;
    }

    public function __toString() {
        return $this->view()->__toString();
    }

}

<?php

namespace Shridhar\Angular\Facades;

/**
 * Description of File
 *
 * @author Shridhar
 */
class File {

    protected $path, $url, $meta, $mode, $type;

    public function __construct($path, $url, $meta = [], $mode = "minified") {
        $this->path = $path;
        $this->url = $url;
        $this->meta = $meta;
        $this->mode = $mode;
    }

    public function url() {
        return $this->url;
    }

    public function path() {
        return $this->path;
    }

    public function exists() {
        return file_exists($this->path);
    }

    public function extension() {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    public function meta($key) {
        return array_get($this->meta, $key);
    }

}

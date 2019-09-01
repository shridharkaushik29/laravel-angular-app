<?php
/**
 * Created by IntelliJ IDEA.
 * User: shrid
 * Date: 16-02-2019
 * Time: 23:43
 */

namespace Shridhar\Angular;


class Asset {

    protected $base_path;
    protected $base_url;
    protected $name;

    /**
     * Asset constructor.
     * @param $name
     * @param $base_path
     * @param $base_url
     */
    public function __construct($name, $base_path, $base_url) {
        $this
            ->setName($name)
            ->setBasePath($base_path)
            ->setBaseUrl($base_url);
    }

    /**
     * @return string
     */
    function path() {
        return "$this->base_path/$this->name";
    }

    /**
     * @return string
     */
    function url() {
        return "$this->base_url/$this->name";
    }

    /**
     * @param string $base_path
     * @return Asset
     */
    public function setBasePath($base_path) {
        $this->base_path = $base_path;
        return $this;
    }

    /**
     * @param mixed $name
     * @return Asset
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $base_url
     * @return Asset
     */
    public function setBaseUrl($base_url) {
        $this->base_url = $base_url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBaseUrl() {
        return $this->base_url;
    }

    /**
     * @return mixed
     */
    public function getBasePath() {
        return $this->base_path;
    }
}

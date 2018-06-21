<?php

namespace Shridhar\Angular\Facades;

use Collective\Html\HtmlFacade;
use Shridhar\Bower\Asset;

/**
 * Description of Html
 *
 * @author Shridhar
 */
class Html extends HtmlFacade {

    protected $ng_app;

    public function __construct($app) {
        $this->ng_app = $app;
    }

    function responsive_meta() {
        return HtmlFacade::meta("viewport", "width=device-width, height=device-height, initial-scale=1, viewport-fit=cover");
    }

    function fav_icon($url = null) {
        return $this->favicon($url);
    }

    function favicon($url = null) {
        $url = $this->ng_app->getConfig("favicon") ?: $url;
        return $this->favicon_external($this->ng_app->asset($url)->url());
    }

    function favicon_external($url) {
        return Asset::make([
                    "url" => $url,
                    "type" => "favicon"
                ])->tag();
    }

    function title($title = null) {
        $text = $this->ng_app->title();
        if ($title) {
            $text = "$title | $text";
        }
        return "<title>$text</title>";
    }

    function html5Mode() {
        $html5Mode = $this->ng_app->getConfig("html5Mode");
    }

    function main_script() {
        return view("angular::main-script", [
            "app" => $this->ng_app
        ]);
    }

    function google_font($name, $params = []) {
        return Asset::googleFont($name, $params)->tag();
    }

}

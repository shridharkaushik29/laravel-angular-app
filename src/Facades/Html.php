<?php

namespace Shridhar\Angular\Facades;

use Collective\Html\HtmlFacade;
use Illuminate\Support\Traits\Macroable;
use Shridhar\Bower\Asset;

/**
 * Description of Html
 *
 * @author Shridhar
 */
class Html extends HtmlFacade {

    use Macroable;

    protected $ng_app;

    public function __construct($app) {
        $this->ng_app = $app;
    }

}

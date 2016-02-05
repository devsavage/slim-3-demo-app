<?php
namespace Savage\Extension;

use Slim\App as Site;

class TwigExtension extends \Twig_Extension
{
    public function getName() {
        return 'savage';
    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction('asset', [$this, 'asset']),
        ];
    }

    /**
     * This is not a great way of loading in assets but it will work. I will change this up to make it work dynamically.
     */
    public function asset($name) {
        return 'http://127.0.0.1/demoapp/' . '/public/assets/' . $name;
    }
}

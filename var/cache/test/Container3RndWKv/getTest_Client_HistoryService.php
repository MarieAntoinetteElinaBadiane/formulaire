<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'test.client.history' service.

include_once $this->targetDirs[3].'/vendor/symfony/browser-kit/History.php';

$this->factories['service_container']['test.client.history'] = function () {
    return new \Symfony\Component\BrowserKit\History();
};

return $this->factories['service_container']['test.client.history']();

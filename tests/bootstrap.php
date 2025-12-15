<?php

use Symfony\Component\ErrorHandler\ErrorHandler;

// Work around https://github.com/symfony/symfony/issues/53812 for the time being (Symfony (6.4-7.1)/PHPUnit 11 issue)
set_exception_handler([new ErrorHandler(), 'handleException']);

require_once __DIR__.'/../vendor/autoload.php';

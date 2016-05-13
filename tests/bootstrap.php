<?php

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->addPsr4(
    'ParityBit\\DependencyResolver\\',
    [
        __DIR__.'/',
    ]
);

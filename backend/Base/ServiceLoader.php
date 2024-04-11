<?php

use App\Base;
use App\Services;
use App\Database;

$container = new Services();

$container->bind('App\Database', function () {
    return new Database(DATABASE);
});

Base::setContainer($container);

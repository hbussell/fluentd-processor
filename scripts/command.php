#!/usr/bin/env php
<?php
// application.php

use Fluent\Apache\Command\GreetCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new AccessCommand);
$application->run();

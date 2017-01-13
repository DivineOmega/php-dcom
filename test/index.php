<?php

require 'vendor/autoload.php';

use \DivineOmega\DCOM\DCOM;

$mysqli = DCOM::getConnection("main");

$result = $mysqli->query('select * from media limit 1');

$row = $result->fetch_assoc();

var_dump($row);
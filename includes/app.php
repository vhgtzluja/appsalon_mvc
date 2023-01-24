<?php 

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::CreateImmutable(__DIR__);
$dotenv->safeLoad();

require 'funciones.php';
require 'database.php';


//Establecer zona horaria
date_default_timezone_set('America/Mexico_City');

// Conectarnos a la base de datos
use Model\ActiveRecord;
ActiveRecord::setDB($db);
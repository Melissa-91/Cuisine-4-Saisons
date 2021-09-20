<?php

//Je définie les constantes en vue de la connexion à la base de donnée


//print_r($_SERVER);exit();
define("SRC", dirname(__FILE__));
define("ROOT", dirname(SRC));
define("SP", DIRECTORY_SEPARATOR);
define("CONFIG", ROOT.SP."config"); 
define("VIEWS", ROOT.SP."views"); 
define("MODEL", ROOT.SP."model");
//Permet de récupérer l'url saisie
define("BASE_URL", dirname(dirname($_SERVER['SCRIPT_NAME'])));

//Je définie la constante pour la TVA du panier
define("TVA", 10);

// import du model
require CONFIG.SP."config.php";
require MODEL.SP."DataLayer.class.php";

$model = new DataLayer();
$category = $model->getCategory();

//$data = $model->getCustomer(2);
//print_r($data);exit();

// les fonctions appelée par le controller
require "functions.php";









?>
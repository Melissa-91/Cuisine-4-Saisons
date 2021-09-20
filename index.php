<?php
    session_start();
    require "include.php";
    verifParams();

//***************************************Gestion de l'écriture d'URL *******************************************// 
//Je  récupère l'url tapé par l'utilisateur//
    $url = trim($_SERVER['PATH_INFO'],'/');
//Avec la mention explode je transforme la chaine d'url en un tableau
    $url = explode('/',$url);
    $url=explode('-',$url[0]);
    $route = array("accueil", "contact","produit","category","details","panier",
                    "supprimer","actionInscription","deconnexion","profil","actionConnexion",
                    "updateProfil","updateAction","validationCommande");
    //print_r($url);

    $action = $url[0];
    
    // controller qui gère l'affichage des pages
    if(!in_array($action,$route)){
        $title = "Page Error";
        $content = "URL introuvable !";
    }else{
        //echo 'Bienvenue sur la page '.$action;
        $function = "display".ucwords($action);
        $title = "Page ".$action;
        $content = $function(); 
    }
    require VIEWS.SP."templates".SP."default.php";



?>
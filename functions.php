<?php

function verifParams(){
  if(isset($_POST) && sizeof($_POST)>0){
    foreach ($_POST as $key => $value) {
      $data = trim($value);
      $data = stripcslashes($data);
      $data = strip_tags($data);
      $data = htmlspecialchars($data);
      $_POST[$key] = $data;
    }
  }
}


///////////////////////////////////////////////////PAGE D'ACCUEIL//////////////////////////////////////////////////


//Affichage de la page d'acceuil
function displayAccueil(){
  $result = '<h1> Bienvenue sur la page d\'Accueil</h1>';
  $result .= '<div class="bg-white shadow-sm rounded p-6">
  <form class="" action="actionInscription" method="post">
    <div class="mb-4">
      <h2 class="h4">INSCRIPTION</h2>
    </div>


    <div class=" mb-3">
      <div class="input-group input-group form">
        <input type="text" name="pseudo" class="form-control " required="" placeholder="Entrer votre Pseudo" aria-label="Entrer votre Pseudo">
      </div>
    </div>

    <div class=" mb-3">
      <div class="input-group input-group form">
        <input type="email" class="form-control " name="email" pattern="[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+.[a-zA-Z.]{2,15}" required="" placeholder="Entrez votre adresse email" aria-label="Entrez votre adresse email">
      </div>
    </div>


    <div class=" mb-3">
      <div class="input-group input-group form">
        <input type="password" class="form-control " name="password" minlength="8" required="" placeholder="Entrez votre mot de passe 8 caractères minimum" aria-label="Entrez votre mot de passe">
      </div>
    </div>


    <button type="submit" class="btn btn-block btn-primary">S\'inscrire</button>
  </form>
</div>';
  return $result;
}

//////////////////////////////////CONNEXION-DECONNEXION-INSCRIPTION-IDENTIFICATION/////////////////////////////////

function displayActionInscription() {
  global $model;
  //print_r($_POST); exit();
  $pseudo = $_POST["pseudo"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  $data = $model->createCustomers($pseudo,$email,$password);
  if($data) {//inscription réussie
    $data_customer = $model->authentifier($email,$password);
    if ($data_customer) {
      $_SESSION["customer"] = $data_customer;
      return '<p class="btn btn-success btn-block">Inscription réussie '.$pseudo.', vous êtes connecté</p>'.displayProduit();
    }
  }else {// inscription échouée
    return '<p class="btn btn-danger btn-block">Votre inscription à échouée</p>'.displayProduit();
  }
}


function displayActionConnexion(){
  global $model;
  //print_r($_POST); exit();
  $email = $_POST["email"];
  $password = $_POST["password"];
  $data_customer = $model->authentifier($email,$password);
  if ($data_customer) {
    $_SESSION["customer"] = $data_customer;
    return '<p class="btn btn-success btn-block">Connexion réussie, vous êtes connecté</p>'.displayProduit();
  }else {// inscription échouée
    return '<p class="btn btn-danger btn-block">Identifiant ou mot de passe invalide</p>'.displayProduit();
  }
}

function displayDeconnexion(){
  unset($_SESSION["customer"]);
  return '<p class="btn btn-success btn-block">Vous avez été déconnecté</p>' .displayProduit();
}

//Affichage du formulaire de contact
function displayContact(){
  $result = '<h1> Bienvenue sur la page de contact</h1>';
  $result .= '
  <h1 class="text-center">Contactez-Nous !</h1>
  <form>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="inputEmail1">Nom : </label>
            <input type="email" class="form-control" id="inputEmail1" required>
          </div>
          <div class="form-group col-md-6">
            <label for="inputPassword2">Prenom : </label>
            <input type="text" class="form-control" id="inputPassword2" required>
          </div>
        </div>
        <div class="form-group">
          <label for="inputAddress">Email : </label>
          <input type="text" class="form-control" id="inputAddress" placeholder="" required>
        </div>
        <div class="form-group">
          <label for="inputAddress2">Message : </label>
          <textarea class="form-control" row="5" col="80" required></textarea>
        </div>

        <div class="form-group">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="">
            <label class="form-check-label" for="">
              Se rappeler de moi
            </label>
          </div>
        </div>
        <button type="submit" class="btn btn-success">Envoyer</button>
      </form>
  ';

  return $result;
}


///////////////////////////////////////////////////PRODUITS/////////////////////////////////////////////////////////


//Affichage de tous produits
function displayProduit(){
  global $model;
  $dataProduct = $model->getProduct();
  //print_r($dataProduct);
  $result = '<h1> Bienvenue sur la page Produits</h1>';
  foreach ($dataProduct as $key => $value) {
      $result .= '<div class="card ml-4 mb-4" style="width: 18rem; display:inline-block;">
      <img src="'.BASE_URL.SP."images".SP."produit".SP.$value["image"].'" class="card-img-top" alt="...">
      <div class="card-body">
        <h5 class="card-title">'.$value["name"] .'</h5>
        <a href="'.BASE_URL.SP."details".'-'.$value["id"].'" class="btn btn-warning">Détails</a>
        <a href="'.BASE_URL.SP."panier".'-'.$value["id"].'" class="btn btn-success">Acheter</a>
      </div>
    </div>';
  }

  return $result;
}

//Fonction qui permet d'afficher les produits par catégorie
function displayCategory(){
  global $model;
  global $url;
  global $category;

  if(isset($url[1]) && is_numeric($url[1]) && $url[1]>0 && $url[1]<= sizeof($category)){
    $result = '<h1> Produit de la catégorie '.$category[$url[1]-1]["name"].'</h1>';
      $dataProduct = $model->getProduct(null,$url[1]);

      foreach ($dataProduct as $key => $value) {
        $result .= '<div class="card ml-4 mb-4" style="width: 18rem; display:inline-block;">
        <img src="'.BASE_URL.SP."images".SP."produit".SP.$value["image"].'" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">'.$value["name"].'</h5>
          <a href="'.BASE_URL.SP."details".'-'.$value["id"].'" class="btn btn-warning">Détails</a>
          <a href="'.BASE_URL.SP."panier".'-'.$value["id"].'" class="btn btn-success">Acheter</a>
        </div>
      </div>';
    }
  }else{
    $result = '<h1> URL incorrecte !</h1>';
  }

return $result;
}


//Fonction qui affiche les détails d'un produit
function displayDetails(){
  global $model;
  global $url;
  global $category;
  $result = '<h1> Bienvenue sur la page de détails produits</h1>';
  $dataProduct = $model->getProduct(null,null,$url[1]);
  //print_r($dataProduct);exit();
  $result .= '
    <div class="row details">
      <div class="col-md-5 col-12">
      <img src="'.BASE_URL.SP."images".SP."produit".SP.$dataProduct[0]["image"].'" class="card-img-top" alt="...">

      </div>
      <div class="col-md-7 col-12">
        <h2>'.$dataProduct[0]["name"].'</h2>
        <p>'.$dataProduct[0]["description"].'</p>
        <p> Categorie : '.$category[$dataProduct[0]["category"]-1]["name"].'</p>
        <a href="'.BASE_URL.SP."panier".'-'.$dataProduct[0]["id"].'" class="btn btn-block btn-success">Ajouter au panier </a>
        <a href="'.BASE_URL.SP."produit".'" class="btn btn-block btn-warning">Retour Page Produits </a>

      </div>

    </div>
  ';
  return $result;
}


//////////////////////////////////////////////////////PANIER///////////////////////////////////////////////////////


//Fonction qui affiche le contenu du panier
function displayPanier(){
  global $model;
  global $url;
  if (isset($url[1])){
    $idProduit = $url[1];
  $dataProduct = $model -> getProduct(null,null,$url[1]);
  $_SESSION["panier"][]= $dataProduct[0];
  }
  if (!isset ($_SESSION["panier"]) || sizeof($_SESSION['panier'])==0) {
    return '<h1>Votre panier est vide !</h1>'.displayProduit();
  }
  $result = '<table class="table mt-5">
  <thead class="table-dark" >
    <tr>
      <th scope="col">#</th>
      <th scope="col">Nom du produit</th>
      <th scope="col">Description</th>
      <th scope="col">Image</th>
      <th scope="col">Prix</th>
      <th scope="col">Quantité</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  ';

 $total_price = 0;
  //print_r($_SESSION); exit();
  foreach ($_SESSION["panier"] as $key => $value) {
    $total_price += $value["price"];
    $result .= '<tr>
    <th scope="row">'.$value["id"].'</th>
    <td>'.$value["name"].'</td>
    <td>'.$value["description"].'</td>
    <td><img src="'.BASE_URL.SP."images".SP."produit".SP.$value["image"].'" alt="..." height="100px" width="150px" /></td>
    <td>'.$value["price"].'€</td>
    <td>1</td>
    <td><a href="'.BASE_URL.SP."supprimer".'-'.$key.'" class="btn btn-block btn-warning"><img src="src\images\accueil\trash.svg" alt="panier" width="25px"></a></td>
  </tr>';
  }
  $total_tva = $total_price*TVA/100;
  $total_ttc = $total_price + $total_tva;
  $result .='<tbody>
  <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>Prix total (HT)</td>
              <td>'.number_format($total_price,2).'€</td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>TVA ('.TVA.'%)</td>
              <td>'.number_format($total_tva,2).'€</td>
              <td></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>Total (TTC)</td>
              <td>'.number_format($total_ttc,2).'€</td>
              <td></td>
            </tr>
            ';

  $result .= '
  </tbody>
</table>';
  $result .= '<a href="'.BASE_URL.SP."validationCommande".'-'.$key.'" class="btn btn-block btn-success">Valider la commande</a>
  <a href="'.BASE_URL.SP."produit".'" class="btn btn-block btn-warning mb-4">Retour vers la page Produits </a>';
  return $result;
}

//La fonction qui supprime les éléments du panier
function displaySupprimer(){
  global $url;

  //print_r($_SESSION["panier"]);
  if (isset ($url[1]) && is_numeric($url[1])){
    $param = $url[1];
    unset($_SESSION["panier"][$param]);
    header("Location:".BASE_URL.SP."panier");
  }
}

//Fonction pour valider une commande
function displayValidationCommande(){
  global $model;
  if(isset($_SESSION["customer"])){//l'utilisateur est connecté
    $dataCustomer = $_SESSION["customer"];
    foreach ($_SESSION["panier"] as $key => $value){
      $r = $model->createOrders($dataCustomer["id"],$value["id"],1,$value["price"]);
      if(!$r){
        $return = "La validation de votre commande à échouée";
      }
    }
    unset($_SESSION["panier"]);
    $result = "Votre commande à bien été validée rendez-vous en boutique dans 30 min !";
  }else{//l'utilisateur n'est pas connecté
    $result = '<p class="btn btn-danger btn-block">Merci de vous connecter ou de créer un compte pour valider votre commande</p>';
    $result .=displayAccueil();
  }
  return $result;
}


///////////////////////////////////////////////////////PROFIL CLT///////////////////////////////////////////////////


//Fonction qui permet d'afficher le profil
function displayProfil(){
  if (isset($_SESSION['customer']['sexe'])){
    if (isset($_SESSION['customer']['sexe'])==1){
      $_SESSION['customer']['sexe'] = " Masculin";
    }else{
      $_SESSION['customer']['sexe'] = " Féminin";
    }
  }
  $result = '
  <ul class="list-group mt-5 mb-2">
  <li class="list-group-item active " aria-current="true">Bienvenue sur votre profil '.$_SESSION['customer']['pseudo'].'</li>
  <li class="list-group-item">Sexe :'.$_SESSION['customer']['sexe'].'</li>
  <li class="list-group-item">Pseudo : '.$_SESSION['customer']['pseudo'].'</li>
  <li class="list-group-item">Nom : '.$_SESSION['customer']['firstname'].'</li>
  <li class="list-group-item">Prénom : '.$_SESSION['customer']['lastname'].'</li>
  <li class="list-group-item">Email : '.$_SESSION['customer']['email'].'</li>
  <li class="list-group-item">Téléphone : '.$_SESSION['customer']['tel'].'</li>
  <li class="list-group-item">Description : '.$_SESSION['customer']['description'].'</li>
  <li class="list-group-item">Adresse de facturation : '.$_SESSION['customer']['adresse_facturation'].'</li>
  <li class="list-group-item">Adresse de livraison : '.$_SESSION['customer']['adresse_livraison'].'</li>
</ul>

<div class="mt-2">
<a href="'.BASE_URL.SP."updateProfil".'" class="btn btn-primary mb-5">Mettre à jours mes informations</a>
</div>
';

  return $result;
}


//Fonction qui permet de mettre à jour le profil
function displayUpdateProfil(){
  $result = '
  <form class="row g-3" action="updateAction" method="post">
    <div class="input-group mb-3">
      <label class="input-group-text" for="inputGroupSelect01">Sexe</label>
      <select class="form-select" id="inputGroupSelect01">
        <option selected>Choose...</option>
        <option value="1">Masculin</option>
        <option value="2">Feminin</option>
      </select>
    </div>
    <div class="col-md-3">
      <label for="inputEmail4" class="form-label">Nom</label>
      <input type="text" name="firstname" value="'.$_SESSION['customer']['firstname'].'" class="form-control" id="inputEmail4">
    </div>
    <div class="col-md-3">
      <label for="inputPassword4" class="form-label">Prénom</label>
      <input type="text" name="lastname" value="'.$_SESSION['customer']['lastname'].'" class="form-control" id="inputPassword4">
    </div>
    <div class="col-md-3">
      <label for="inputPassword4" class="form-label">Email</label>
      <input type="text" name="email" value="'.$_SESSION['customer']['email'].'" class="form-control" id="inputPassword4">
    </div>
    <div class="col-md-3">
      <label for="inputPassword4" class="form-label">Téléphone</label>
      <input type="text" name="tel" value="'.$_SESSION['customer']['tel'].'" class="form-control" id="inputPassword4">
    </div>
    <div class="col-12">
      <label for="inputAddress" class="form-label">Description</label>
      <input type="text" name="description" value="'.$_SESSION['customer']['description'].'" class="form-control" id="inputAddress" placeholder="Tapez ici une courte description de vous !">
    </div>

      <div class="col-6">
        <label for="inputAddress2" class="form-label">Adresse de livraison</label>
        <input type="text" name="adresse_livraison" value="'.$_SESSION['customer']['adresse_livraison'].'" class="form-control" id="inputAddress2" placeholder="Votre adresse de livraison ?">
      </div>
      <div class="col-6">
        <label for="inputAddress2" class="form-label">Adresse de facturation</label>
        <input type="text" name="adresse_facturation" value="'.$_SESSION['customer']['adresse_facturation'].'" class="form-control" id="inputAddress2" placeholder="Votre adresse de facturation">
      </div>
      <div class=" mt-2 col-12">
        <button type="submit" class="btn btn-success">Valider</button>
      </div>

  </form>
  ';
  return $result;
}


//Fonction qui envoie les informations dans la base de données
function displayUpdateAction(){
  global $model;
  $_POST["id"] = $_SESSION["customer"]["id"];
  $r = $model->updateInfosCustomer($_POST);
  if($r){
    $_SESSION["customer"] = $model->getCustomer($_SESSION["customer"]["id"]);
    $result = '<p class="btn btn-success btn-block">Mise à jour réussie</p>';
  }else{
    $result = '<p class="btn btn-danger btn-block">La mise à jour à échouée</p>';
  }

  return $result.displayProfil();
}

<?php 

class DataLayer{
//Connexion à la base de données
    private $connexion;

    function __construct(){
        try {
            $this->connexion = new PDO("mysql:host=".HOST.";dbname=".DB_NAME,DB_USER,DB_PASSWORD);
            //echo "connexion à la base de données réussie";
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }

    /**
     * fonction qui créer un customer en base de données
     * @param pseudo le pseudo du customer
     * @param email l'email du customer
     * @param password le mot de passe du customer
     * @return TRUE sien cas de création avec succès du customer, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function createCustomers($pseudo,$email,$password){
        $sql = "INSERT INTO customer (pseudo,email,password) VALUES (:pseudo,:email,:password)";
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array(
                ':pseudo' => $pseudo,
                ':email' => $email,
                ':password' => sha1($password)
            ));
            if($var){
                return TRUE;
            }else{
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }    

    }

    /**
     * fonction qui permet d'authentifier un customer
     * @param email l'email du customer
     * @param password le mot de passe du customer
     * @return ARRAY tableau contenant les infos du customer si authentification réussie
     * @return FALSE si authentification échouée
     * @return NULL s'il y a une exception déclenchée 
     */
    function authentifier($email,$password){
        $sql = "SELECT * FROM customer WHERE email = :email";
        try {
            $result = $this->connexion->prepare($sql);
            $result->execute(array(':email'=>$email));
            $data = $result->fetch(PDO::FETCH_ASSOC);
            if($data && ($data['password'] == sha1($password))){
                return $data;
            }else{
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
        

    }

    /**
     * fonction qui créer un customers en base de données
     * @param idCustomers l'identifiant du customers
     * @param idPoduct l'identifiant du produt de la commande
     * @param quantity La quantité du produit commandé
     * @param price Le prix de la commande
     * @return TRUE si en cas de commande réalisée avec succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function createOrders($idCustomer,$idPoduct,$quantity,$price){
        $sql = "INSERT INTO `orders`(`id_customer`, `id_product`, `quantity`, `price`) VALUES 
        (:id_customer,:id_product,:quantity,:price)";
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array(
                ':id_customer' => $idCustomer,
                ':id_product' => $idPoduct,
                ':quantity' => $quantity,
                ':price' => $price
            ));
            if($var){
                return TRUE;
            }else{
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }

    }

    /**
     * fonction qui met à jour les information d'un utilisateur
     * en base de données
     * @param newInfos Tableau de associatif "nom_champ_a_mettre_a_jour" => "nouvelle_valeur"
     * @return TRUE si en cas de succès de la mise à jour, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    // exemple de paramètre array('pseudo'=>'jean','firstname'=>'DUPONT')
    function updateInfosCustomer($newInfos){
        $sql = "UPDATE `customer` SET ";
        $id = $newInfos["id"];
        unset ($newInfos["id"]);
        try {
            foreach($newInfos as $key => $value){
                $value = addslashes($value);
                $sql .= " $key = '$value' ,";
            }
            $sql = substr($sql,0,-1);
            $sql .= " WHERE id = :id";
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array('id'=>$id));
            if($var){
                return TRUE;
            }else{
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }

    }

    /**
     * fonction qui sert à récupérer les catégories de produits au sein de la base de données
     * @param rien ne prend pas de paramètre
     * @return array tableau contenant les catégories, en cas de succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function getCategory(){
        $sql = "SELECT * FROM category";
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute();
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            if($data){
                return $data;
            }else{
                return FALSE;
            }

        } catch (PDOException $th) {
            return NULL;
        }


    }

    /**
     * fonction qui sert à récupérer les utilisateurs au sein de la base de données
     * @param id  prend ce paramètre pour identifier l'utilisateur
     * @return array tableau contenant les customers, en cas de succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function getCustomer($id){
        $sql = "SELECT * FROM customer WHERE id=?";
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array($id));
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            if($data){
                unset ($data[0]['password']);
                return $data[0];
            }else{
                return FALSE;
            }

        } catch (PDOException $th) {
            return NULL;
        }


    }


    /**
     * fonction qui sert à récupérer les produits au sein de la base de données
     * @param limit parametre optionnel, permet de définir le nombre max de produits attendu
     * @return array tableau contenant les produits, en cas de succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function getProduct($limit=NULL,$category = NULL,$id=NULL){
        $sql = "SELECT * FROM product ";
        try {
            if(!is_null($id)){
                $sql .= ' WHERE id = '.$id;
            }
            if(!is_null($category)){
                $sql .= ' WHERE category = '.$category;
            }
            if(!is_null($limit)){
                $sql .= ' LIMIT '.$limit;
            }
            //print_r($sql);exit();

            $result = $this->connexion->prepare($sql);
            $var = $result->execute();
            $data = $result->fetchAll(PDO::FETCH_ASSOC);
            if($data){
                return $data;
            }else{
                return FALSE;
            }

        } catch (PDOException $th) {
            return NULL;
        }
    }


    /**
     * fonction qui sert à récupérer les produits au sein de la base de données
     * @param limit parametre optionnel, permet de définir le nombre max de produits attendu
     * @return array tableau contenant les customers, en cas de succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function getAllCustomers($limit=NULL, $id=NULL){
        $sql = "SELECT * FROM customer";
            try {
                if(!is_null($id)){
                    $sql .= ' WHERE id = '.$id;
                }
                if(!is_null($limit)){
                    $sql .= ' LIMIT '.$limit;
                }
                //print_r($sql);exit();
    
                $result = $this->connexion->prepare($sql);
                $var = $result->execute();
                $data = $result->fetchAll(PDO::FETCH_ASSOC);
                if($data){
                    return $data;
                }else{
                    return FALSE;
                }
    
            } catch (PDOException $th) {
                return NULL;
            }
        }



}

?>
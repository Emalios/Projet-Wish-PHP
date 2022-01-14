<?php 

namespace App\Model; 

class Compte extends \Illuminate\Database\Eloquent\Model {
    
    protected $table = 'compte';

    protected $primaryKey = 'id';

    public $timestamps = false;


    /**
     * Methode permettant d'enregistrer un utilisateur dans la base de donnes
     */
    public function createUser($email, $login, $password) : mixed {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            return "L'email n'est pas correct";
        else if (strlen($login) > 12 || strlen($login) < 4)
            return "Le login ne fait pas la bonne taille [entre 4 et 12 attendu]";
        else if(strlen($password) > 16 || strlen($password) < 8)
            return "Le mot de passe ne fait pas la bonne taille [entre 8 et 16 attendu]";
        else 
            return true; 
    }

}
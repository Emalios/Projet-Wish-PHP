<?php 

namespace App\Model; 

class Compte extends \Illuminate\Database\Eloquent\Model {
    
    protected $table = 'compte';

    protected $primaryKey = 'id';

    public $timestamps = false;


    /**
     * Methode permettant d'enregistrer un utilisateur dans la base de donnes
     */
    public static function createUser($email, $login, $password) : mixed {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            return "L'email n'est pas correct";
        else if (strlen($login) > 12 || strlen($login) < 4)
            return "Le login ne fait pas la bonne taille [entre 4 et 12 attendu]";
        else if(strlen($password) > 16 || strlen($password) < 8)
            return "Le mot de passe ne fait pas la bonne taille [entre 8 et 16 attendu]";
        else {
            $c = new Compte();
            $c->login = $login; 
            $c->email = $email; 
            $c->password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
            $c->save();
            return true; 
        }
    }

    /**
     * méthode permettant de savoir si les logins sont corrects
     * @param $email email
     * @param $password mot de passe
     * @return vrai si identifiants correct sinon false
     */
    public static function seConnecter($email, $password) : mixed {
        $c = Compte::where( 'email', '=', $email )->first();
        if($c == null && !password_verify($password, $c->password))
            return false; 
        $_SESSION['login'] = $c['login']; 
        return true; 
    }
}
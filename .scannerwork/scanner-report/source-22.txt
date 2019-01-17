<?php
class Auth{

    private $session;

    public function __construct($session){
        $this->session = $session;
    }

    public function connect($user){
        $this->session->write('auth', $user);
    }

    public function login($db, $username, $password){
        $user = $db->query('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL', ['username' => $username])->fetch();
        if(password_verify($password, $user->password)){
            $this->connect($user);
            return $user;
        }else{
            return false;
        }
    }

    public function restrict(){
        if(!$this->session->read('auth')){
            $this->session->setFlash('danger', "Vous n'avez pas le droit d'accéder à cette page");
            header('Location: login.php');
            exit();
        }
    }
}
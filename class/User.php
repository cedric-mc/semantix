<?php
    class User {
        public $pseudo;
        public $idUser;
        public $email;

        public function __construct($pseudo, $idUser, $email) {
            $this->pseudo = $pseudo;
            $this->idUser = $idUser;
            $this->email = $email;
        }

        public function setEmail($email) {
            $this->email = $email;
        }
    }
?>
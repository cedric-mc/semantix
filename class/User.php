<?php
    class User {
        public string $pseudo;
        public int $idUser;
        public string $email;

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
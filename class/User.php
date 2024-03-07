<?php
    class User {
        public int $idUser;
        public string $pseudo;
        public string $email;
        public int $year;

        public function __construct($idUser, $pseudo, $email, $year) {
            $this->idUser = $idUser;
            $this->pseudo = $pseudo;
            $this->email = $email;
            $this->year = $year;
        }

        public function getIdUser() {
            return $this->idUser;
        }

        public function getPseudo() {
            return $this->pseudo;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getYear() {
            return $this->year;
        }

        public function setEmail($email) {
            $this->email = $email;
        }
    }
?>
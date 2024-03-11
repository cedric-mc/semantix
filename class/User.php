<?php
    class User {
        private int $idUser;
        private string $pseudo;
        private string $email;
        private int $year;

        public function __construct(int $idUser, string $pseudo, string $email, int $year) {
            $this->idUser = $idUser;
            $this->pseudo = $pseudo;
            $this->email = $email;
            $this->year = $year;
        }

        public static function createUserFromUser(User $user) {
            return new self($user->getIdUser(), $user->getPseudo(), $user->getEmail(), $user->getYear());
        }

        public function getIdUser(): int {
            return $this->idUser;
        }

        public function getPseudo(): string {
            return $this->pseudo;
        }

        public function getEmail(): string {
            return $this->email;
        }

        public function getYear(): int {
            return $this->year;
        }

        public function setEmail($email): void {
            $this->email = $email;
        }

        public function isEmailExist(PDO $cnx, string $newEmail, string $emailExists): bool {
            $stmt = $cnx->prepare($emailExists);
            $stmt->bindParam(":email", $newEmail);
            $stmt->execute();
            $stmt = $stmt->fetch();
            $stmt->closeCursor();
            return $stmt;
        }

        public function modifyEmail(PDO $cnx, string $newEmail, string $changeEmail): void {
            $stmt = $cnx->prepare($changeEmail);
            $stmt->bindParam(':email', $newEmail);
            $stmt->bindParam(':pseudo', $this->pseudo);
            $stmt->execute();
            $stmt->closeCursor();

            $this->setEmail($newEmail);
        }
    }
?>
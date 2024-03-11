<?php
    class User {
        private int $idUser;
        private string $pseudo;
        private string $email;
        private int $year;
        // Photo from longblob type in database
        private string $photo;

        public function __construct(int $idUser, string $pseudo, string $email, int $year, string $photo = null) {
            $this->idUser = $idUser;
            $this->pseudo = $pseudo;
            $this->email = $email;
            $this->year = $year;
            $this->photo = $photo;
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

        public function getPhoto(): string {
            return "data:image/jpeg;base64," . base64_encode($this->photo);
        }

        public function setEmail($email): void {
            $this->email = $email;
        }

        public function setPhoto($photo): void {
            $this->photo = $photo;
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

        public function logging(PDO $cnx, int $numAction): void {
            $query = "INSERT INTO sae_traces (utilisateur_id, action, ip_adress) VALUES (:utilisateur_id, :action, :ip_adress)";
            $stmt = $cnx->prepare($query);
            $stmt->bindParam(":utilisateur_id", $userId, PDO::PARAM_INT);
            $stmt->bindParam(":action", $action, PDO::PARAM_STR);
            $stmt->bindParam(":ip_adress", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
        }
    }
?>
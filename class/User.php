<?php
    class User {
        private int $idUser;
        private string $pseudo;
        private string $email;
        private int $year;
        private ?string $imageData;

        public function __construct(int $idUser, string $pseudo, string $email, int $year, ?string $imageData = null) {
            $this->idUser = $idUser;
            $this->pseudo = $pseudo;
            $this->email = $email;
            $this->year = $year;
            $this->imageData = $imageData;
        }

        public static function createUserFromUser(User $user) {
            return new self($user->getIdUser(), $user->getPseudo(), $user->getEmail(), $user->getYear(), $user->getImageData());
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

        public function getImageData(): ?string {
            return $this->imageData;
        }

        public function getImageSrc(): string {
            if ($this->imageData !== null) {
                $base64 = base64_encode($this->imageData);
                return "data:image/jpeg;base64,$base64";
            } else {
                return "../img/profil.webp";
            }
        }

        public function setEmail($email): void {
            $this->email = $email;
        }

        public function setImageData($imageData): void {
            $this->imageData = $imageData;
        }

        public function isEmailExist(PDO $cnx, string $newEmail, string $emailExists): bool {
            $request = $cnx->prepare($emailExists);
            $request->bindParam(":email", $newEmail);
            $request->execute();
            $result = $request->fetch();
            $request->closeCursor();
            return $result;
        }

        public function modifyEmail(PDO $cnx, string $newEmail, string $changeEmail): void {
            $stmt = $cnx->prepare($changeEmail);
            $stmt->bindParam(':email', $newEmail);
            $stmt->bindParam(':pseudo', $this->pseudo);
            $stmt->execute();
            $stmt->closeCursor();
        }

        public function logging(PDO $cnx, int $numAction): void {
            $query = "INSERT INTO sae_traces (utilisateur_id, action, ip_adress) VALUES (:utilisateur_id, :action, :ip_adress)";
            $stmt = $cnx->prepare($query);
            $stmt->bindParam(":utilisateur_id", $this->idUser, PDO::PARAM_INT);
            $stmt->bindParam(":action", $numAction, PDO::PARAM_STR);
            $stmt->bindParam(":ip_adress", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
        }
    }
?>
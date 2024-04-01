<?php
    include_once("User.php");

    class Game {
        public User $user;
        public int $tour;
        public array $wordsArray;
        public string $lastWord;

        public function __construct($user, $tour) {
            $this->user = $user;
            $this->tour = $tour;
            $this->wordsArray = array();
            $this->lastWord = "";
        }

        public static function createGameFromGame($game) {
            return new Game($game->user, $game->tour);
        }

        public function addTour() {
            $this->tour++;
        }

        public function addWord($word) {
            if (!in_array($word, $this->wordsArray)) {
                $this->wordsArray[] = $word;
            }
        }

        public function isWordInArray($word) {
            return in_array($word, $this->wordsArray);
        }

        public function setLastWord($word) {
            $this->lastWord = $word;
        }

        public function isWordInFile($word): bool {
            $file = fopen("partie/game_data_" . $this->user->getPseudo() . ".txt", "r");
            while (!feof($file)) {
                $line = fgets($file);
                if (strpos($line, $word) !== false) {
                    fclose($file);
                    return true;
                }
            }
            fclose($file);
            return false;
        }
    }
?>
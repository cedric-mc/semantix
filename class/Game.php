<?php
    class Game {
        private string $pseudo;
        private int $tour;
        private array $wordsArray;
        private string $lastWord;

        public function __construct($pseudo, $tour, $wordsArray, $lastWord) {
            $this->pseudo = $pseudo;
            $this->tour = $tour;
            $this->wordsArray = $wordsArray;
            $this->lastWord = $lastWord;
        }

        public static function createGameFromGame($game) {
            return new Game($game->pseudo, $game->tour, $game->wordsArray, $game->lastWord);
        }

        public function getPseudo() {
            return $this->pseudo;
        }

        public function getTour() {
            return $this->tour;
        }

        public function getWordsArray() {
            return $this->wordsArray;
        }

        public function getLastWord() {
            return $this->lastWord;
        }

        public function getNumberOfWords() {
            return count($this->wordsArray);
        }

        public function addTour() {
            $this->tour++;
        }

        public function addWord($word) {
            if (!$this->isWordInArray($word)) {
                array_push($this->wordsArray, $word);
                $this->setLastWord($word);
            }
        }

        public function isWordInArray($word) {
            return in_array($word, $this->wordsArray);
        }

        public function setLastWord($word) {
            $this->lastWord = $word;
        }

        public function isWordInFile($word): bool {
            $file = fopen("partie/game_data_" . $this->pseudo . ".txt", "r");
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

        // Méthode pour ajouter plusieurs mots à la fois dans le tableau wordsArray
        public function addWordsFromArray(array $words) {
            foreach ($words as $word) {
                $this->addWord($word);
            }
        }
    }
?>
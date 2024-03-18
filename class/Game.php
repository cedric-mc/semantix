<?php

    require_once('User.php');

    class Game {
        public $user;
        public $tour;
        public $wordsArray;
        public $lastWord;

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
    }
?>
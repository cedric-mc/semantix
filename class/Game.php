<?php

    require_once('User.php');

    class Game {
        public $user;
        public $tour;
        public $wordsArray;

        public function __construct($user, $tour) {
            $this->user = $user;
            $this->tour = $tour;
            $this->wordsArray = array();
        }

            public function getUser(): string {
            return $this->user;
        }

        public function getTour(): int {
            return $this->tour;
        }

        public function getWordsArray(): array {
            return $this->wordsArray;
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
    }
?>
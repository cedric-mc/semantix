<?php
    class Game {
        public $tour;
        public $wordsArray;

        public function __construct($tour) {
            $this->tour = $tour;
            $this->wordsArray = array();
        }

        public function getTour() {
            return $this->tour;
        }

        public function addTour() {
            $this->tour++;
        }

        public function getWordsArray() {
            return $this->wordsArray;
        }

        public function addWord($word) {
            if (!in_array($word, $this->wordsArray)) {
                array_push($this->wordsArray, $word);
            }
        }

        public function isWordInArray($word) {
            return in_array($word, $this->wordsArray);
        }
    }
?>
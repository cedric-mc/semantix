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
            $this->wordsArray[] = $word;
        }
    }
?>
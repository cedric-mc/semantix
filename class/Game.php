<?php
    class Game {
        public $tour;

        public function __construct($tour) {
            $this->tour = $tour;
        }

        public function getTour() {
            return $this->tour;
        }

        public function addTour() {
            $this->tour++;
        }
    }
?>
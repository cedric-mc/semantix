<?php
$score = isset($_POST['currentScore']) ? intval($_POST['currentScore']) : 0;
$newNodeName = isset($_POST['newNodeName']) ? $_POST['newNodeName'] : '';

if ($newNodeName) {
    $randomScore = rand(15, 50);
    $score += $randomScore;
}

echo $score;
?>

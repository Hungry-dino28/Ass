<?php
session_start();
include("db.php");

// GET QUESTIONS
$sql = "SELECT * FROM quiz";
$result = mysqli_query($conn, $sql);

$questions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $questions[] = $row;
}

// INIT SESSION
if (!isset($_SESSION['q_index'])) {
    $_SESSION['q_index'] = 0;
}

if (!isset($_SESSION['answers'])) {
    $_SESSION['answers'] = [];
}

// NEXT BUTTON
if (isset($_POST['next'])) {
    if (isset($_POST['answer'])) {
        $_SESSION['answers'][] = $_POST['answer'];
        $_SESSION['q_index']++;
    }
}

// RESTART BUTTON
if (isset($_POST['restart'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

$current = $_SESSION['q_index'];

// SCORE
$score = 0;
if (!empty($_SESSION['answers'])) {
    for ($i = 0; $i < count($_SESSION['answers']); $i++) {
        if (
            isset($questions[$i]['correct_answer']) &&
            $_SESSION['answers'][$i] == $questions[$i]['correct_answer']
        ) {
            $score++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quiz</title>

<style>
body {
    background-color: #b2d2fd;
    font-family: Arial;
}
.container {
    width: 500px;
    margin: 50px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
}
img {
    width: 120px;
    height: 100px;
}
.sec2{
    margin-bottom: 20px;
}
</style>

</head>
<body>

<div class="container">
    <center>

<form method="POST">

<?php if ($current < count($questions)) { 
    $row = $questions[$current];
?>

    <img src="image/<?= $row["image"] ?>">

    <div class="sec1">
        <input type="radio" name="answer" value="<?= $row["option1"] ?>" required>
        <?= $row["option1"] ?>

        <input type="radio" name="answer" value="<?= $row["option2"] ?>">
        <?= $row["option2"] ?>
    </div>

    <div class="sec2">
        <input type="radio" name="answer" value="<?= $row["option3"] ?>">
        <?= $row["option3"] ?>
    
        <input type="radio" name="answer" value="<?= $row["option4"] ?>">
        <?= $row["option4"] ?>
    </div>

    <input type="submit" name="next" value="Next">

<?php } else { ?>

    <h2>Quiz Finished!</h2>
    <h3>Your Score: <?= $score ?> / <?= count($questions) ?></h3>

    <button type="submit" name="restart">OK</button>

<?php } ?>

</form>
</center>
</div>

</body>
</html>
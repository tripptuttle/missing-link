<?php
session_start();
require_once "_assets/util.php";
$u = new util();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Missing Link: Silly SILS Syllabi Explorer</title>
    <link href="_assets/style.css" rel="stylesheet" media="all">
</head>
<body>
<header>
    <h1>Missing Link: Silly SILS Syllabi Explorer</h1>
</header>
<main class="grid home">
    <section class="column first">
        <a href="instructors"><h2>Instructors</h2></a>
    </section>
    <section class="column second">
        <a href="courses"><h2>Courses</h2></a>



    </section>
    <section class="column third">
        <a href="terms"><h2>Terms</h2></a>
    </section>
</main>
</body>
</html>
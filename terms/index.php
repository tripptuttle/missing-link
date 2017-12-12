<?php
session_start();
require_once "../_assets/util.php";
$u = new util();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Missing Link: Silly SILS Syllabi Explorer</title>
    <link href="../_assets/style.css" rel="stylesheet" media="all">
</head>
<body vocab="http://schema.org/">
<header>
    <h1>Missing Link: Silly SILS Syllabi Explorer</h1>
        <nav><a href="../instructors">Instructors</a><a href="../courses">Courses</a></nav>

</header>
<main>
    <h2>Terms</h2>
    <h3 id="error">This section is coming soon and is out of scope for the assignment.</h3>
    <?php
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == "GET" && !isset($_GET['id'])) {
        echo '<section id="results-list" class="results">';
        foreach ($u::$terms as $term) {
            $id = $term['term_id'];
            $year = $term['year'];
            $semester = $term['semester'];
            echo <<<EOF
                    <div vocab="http://schema.org/" typeof="Person" class="result"><a href="?id=$id">
                        <span property="name">$semester $year</span></a>
                    </div>
EOF;
        }
        echo <<<EOF
                <section class="add-new" rel="subsection">
            <h3>Add New</h3>
            <form class="submit-data" action="index.php" method="post">
                <label for="year">Year <input id="year" name="year"></label>
                <label for="semester">Semester <input id="semester" name="semester"></label>
                <label for="term-id">Term ID <input id="term-id" name="term_id"></label>
                <button type="submit" value="Submit">Submit</button>
            </form>
        </section>
EOF;

    };

    if ($method == "GET" && isset($_GET['id'])) {
        $id = strip_tags($_GET['id']);
        $result = array_filter($u::$terms, function ($d) use ($id) {
            return $d['term_id'] == $id;
        });
        $result = array_values(array_filter($result));
        if (count($result) > 0) {
            $result = $result[0];
            $semester = $result['semester'];
            $year = $result['year'];
            echo <<<EOF
            <a rel="start" class="view-all btn" href="index.php">View All</a>
            <section id="result-detail" class="result-detail">
                <section id="result" class="result" typeof="Person" style="grid-row: 1 / span 2">
                    <h3 property="name">$semester $year</h3>
                    <h4>Courses Offered</h4>
                    <div typeof="Course">
                    <h5><s property="url" href="____"><span property="courseCode"> _______</span> <span property="name">_______</h5></a>
                    </div>
                </section>
            </section>
EOF;
        } else {
            header("HTTP/2.0 404 Not Found", true, 404);
            include "../error.php";
            $message = "Sorry, the resource you tried to GET was not found.";
        }
    }
    if ($method == "POST") {
        try{
            $new = array();
            if (array_key_exists('first', $_POST) &&
                array_key_exists('last', $_POST) &&
                array_key_exists('pid', $_POST) &&
                array_key_exists('website', $_POST)){
                    $new['first'] = strip_tags($_POST['first']);
                    $new['last'] = strip_tags($_POST['last']);
                    $new['pid'] = strip_tags($_POST['pid']);
                    $new['website'] = strip_tags($_POST['website']);
                    $data = $u::$instructors;
                    $data[] = $new;
                    $id = $new['pid'];
                    $name = $new['first'] . " " . $new['last'];
                    file_put_contents('../terms.json', json_encode($data));
                    header("HTTP/2.0 201 Created", true, 201);
                    echo <<<EOF
                    <section id="result-detail" class="result-detail">
                        Success! Instructor <a href="index.php?id=$id">$name</a> added!
                    </section>
EOF;

        } else {
                header("HTTP/1.0 400 Bad Request", true, 400);
                $errorMsg = 'Not all required values were provided, please <a href="index.php">try again!</a>';
                include "../error.php";
            }
    } catch (Exception $e) {
         header("HTTP/2.0 500 Internal Server Error", true, 500);
            include "../error.php";
            $message = "Sorry, we're having some issues!";
        }
    }

    ?>


</main>
</body>
</html>

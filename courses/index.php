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
    <nav><a href="../instructors">Instructors</a><a href="../terms">Terms</a></nav>
</header>
<main>
    <h2>Courses</h2>
    <?php
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == "GET" && !isset($_GET['id'])) {
        echo '<section id="results-list" class="results">';
        foreach ($u::$courses as $course) {
            $id = $course['id'];
            $title = $course['number'] . ": " . $course['name'];
            echo <<<EOF
                    <div vocab="http://schema.org/" typeof="Course" class="result"><a href="?id=$id">$title</a>
             
                    </div>
EOF;
        }
        echo <<<EOF
        </section>
        <section id="add-new" class="add-new">
            <h3>Add New</h3>
            <form class="submit-data" action="index.php" method="post">
                <label for="number">Course Number <input id="number" name="number"></label>
                <label for="section">Course Section <input id="section" name="section"></label>
                <label for="name">Course Name <input id="name" name="name"></label>
                <label for="instructor">Instructor <a class="add-other" href="../instructors">Click Here to Add</a>
                <select id="instructor" name="instructor_pid">
EOF;
        foreach ($u::$instructors as $instructor) {
            $id = $instructor['pid'];
            $nameRev = $instructor['last'] . ', ' . $instructor['first'];
            echo <<<EOF
                        <option value="$id">$nameRev</option>
EOF;
        }
echo <<<EOF
        </select>
                </label>
                 <label for="id">Course ID <input id="id" name="id"></label>
                 <label for="syllabus">Syllabus URL <input id="syllabus" name="syllabus"></label>
                 <label for="description">Course Description <input id="description" name="description"></label>
                 <label for="prereq">Course Pre-Requisites <input id="prereq" name="prereq"></label>
                <button type="submit" value="Submit">Submit</button>
            </form>

        </section>
EOF;

    };

    if ($method == "GET" && isset($_GET['id'])) {
        $id = strip_tags($_GET['id']);
        $result = array_filter($u::$courses, function ($d) use ($id) {
            return $d['id'] == $id;
        });
        $result = array_values(array_filter($result));
        if (count($result) > 0) {
            $result = $result[0];
            $title = $result['name'];
            $number = $result['number'];
            $desc = $result['description'];
            $prereq = $result['prereq'];
            $sylla = $result['syllabus'];
            $instructorPid = $result['instructor_pid'];
            echo <<<EOF
                <a rel="start" class="view-all btn" href="index.php">View All</a>

            <section id="result-detail" class="result-detail">
                <section id="result" class="result" typeof="Course">
                    <h3 property="name">$number: $title</h3>
                    <p class="description" property="description">$desc</p>
                    <p class="prerequisites">Pre-Requisites: <span property="coursePrerequisites">$prereq</span></p>
                    <p class="syllabus">Syllabus: <a property="workExample" href="$sylla">$sylla</a></p>
                    <section class="linked-results">
                    <h4>Taught By</h4>
EOF;
            $instructors = array_filter($u::$instructors, function ($d) use ($id, $instructorPid) {
                return $d['pid'] == $instructorPid;
            });
            if (count($instructors) > 0) {
                foreach ($instructors as $k => $v) {
                    $iPid = $v['pid'];
                    $name = $v['first'] . " " . $v['last'];
                    echo <<<EOF
                    <div property="provider" typeof="Person">
                    <h5><a property="url" href="../instructors?id=$iPid"><span property="name">$name</h5></a>
                    </div>
EOF;
                }
                echo "
                </section>                
                </section>
            </section>
";

            }

        } else {
            header("HTTP/2.0 404 Not Found", true, 404);
            include "../error.php";
            $message = "Sorry, the resource you tried to GET was not found.";
        }
    }

    if ($method == "POST") {
        try {
            $new = array();
            if (array_key_exists('id', $_POST) &&
                array_key_exists('syllabus', $_POST) &&
                array_key_exists('section', $_POST) &&
                array_key_exists('number', $_POST)) {
                $new['number'] = strip_tags($_POST['number']);
                $new['section'] = strip_tags($_POST['section']);
                $new['syllabus'] = strip_tags($_POST['syllabus']);
                $new['name'] = strip_tags($_POST['name']);
                $new['instructor_pid'] = strip_tags($_POST['instructor_pid']);
                $new['description'] = strip_tags($_POST['description']);
                $new['id'] = strip_tags($_POST['id']);
                $new['prereq'] = strip_tags($_POST['prereq']);
                $data = $u::$courses;
                $data[] = $new;
                file_put_contents('../courses.json', json_encode($data));
                header("HTTP/2.0 201 Created", true, 201);
                $id = $new['id'];
                $name = $new['number'] . ": " . $new['name'];
                echo <<<EOF
                    <section id="result-detail" class="result-detail">
                        Success! Course <a href="index.php?id=$id">$name</a> added!
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



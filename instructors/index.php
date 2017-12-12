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
    <nav><a href="../courses">Courses</a><a href="../terms">Terms</a></nav>

</header>
<main>
    <h2>Instructors</h2>
    <?php
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == "GET" && !isset($_GET['id'])) {
        echo '<section id="results-list" class="results">';
        foreach ($u::$instructors as $instructor) {
            $id = $instructor['pid'];
            $first = $instructor['first'];
            $last = $instructor['last'];
            echo <<<EOF
                    <div vocab="http://schema.org" typeof="Person" class="result"><a property="identifier" href="?id=$id">
                        <span property="name">$first $last</span></a>
                    </div>
EOF;
        }
        echo <<<EOF
                <section class="add-new" rel="subsection">
                    <h3>Add New</h3>
                    <form class="submit-data" action="index.php" method="post">
                        <label for="first-name">First Name <input id="first-name" name="first"></label>
                        <label for="last-name">Last Name <input id="last-name" name="last"></label>
                        <label for="pid">PID <input id="pid" name="pid"></label>
            
                        <label for="website">Website <input id="website" name="website"></label>
                        <button type="submit" value="Submit">Submit</button>
                    </form>
                </section>
EOF;

    };

    if ($method == "GET" && isset($_GET['id'])) {
        $id = strip_tags($_GET['id']);
        $result = array_filter($u::$instructors, function ($d) use ($id) {
            return $d['pid'] == $id;
        });
        $result = array_values(array_filter($result));
        if (count($result) > 0) {
            $result = $result[0];
            $name = $result['first'] . " " . $result['last'];
            $web = $result['website'];
            $id = $result['pid'];
            echo <<<EOF
                            <a rel="start" class="view-all btn" href="index.php">View All</a>

            <section id="result-detail" class="result-detail">
                <section id="result" class="result" typeof="Person">
                    <h3 property="name">$name</h3>
                        <p>Homepage: <a href="$web" property="identifier">$web</a></p>
                    <section id="linked-results" class="linked-results">   
                    <h4>Courses Taught</h4>
EOF;
            $coursesTaught = array_values(array_filter($u::$courses, function ($d) use ($id) {
                return $d['instructor_pid'] == $id;
            }));
            foreach ($coursesTaught as $k => $v) {
                $code = $v['number'];
                $title = $v['name'];
                $cid = $v['id'];
                $desc = $v['description'];
                echo <<<EOF
                    <div typeof="Course">
                    <h5><a property="url" href="../courses?id=$cid"><span property="courseCode">$code</span>: <span property="name">$title</h5></a>
                    <meta property="description" content="$desc">
                    <meta property="provider" content="$name">
                    <link rel="author" href="$web">
                    </div>
EOF;
            }
            echo <<<EOF
            </section> 
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
        try {
            $post = $_POST;
            $new = array();
            if (array_key_exists('first', $post) && ($post['first'] != '') &&
                array_key_exists('last', $post) && ($post['last'] != '') &&
                array_key_exists('pid', $post) && ($post['pid'] != '') &&
                array_key_exists('website', $post) && ($post['website'] != '')) {
                $new['first'] = strip_tags($post['first']);
                $new['last'] = strip_tags($post['last']);
                $new['pid'] = strip_tags($post['pid']);
                $new['website'] = strip_tags($post['website']);
                $data = $u::$instructors;
                $data[] = $new;
                $id = $new['pid'];
                $name = $new['first'] . " " . $new['last'];
                file_put_contents('../instructors.json', json_encode($data));
                header("HTTP/2.0 201 Created", true, 201);
                echo <<<EOF
                    <section id="result-detail" class="result-detail">
                        Success! Instructor <a href="index.php?id=$id">$name</a> added!
                    </section>
EOF;

            } else {
                header("HTTP/1.0 400 Bad Request", true, 400);
                $errorMsg = 'Not all required values were provided! Please <a href="index.php">try again!</a>';
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
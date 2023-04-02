<?php
    // Initialize the session
    session_start();

    // Check if the user is logged in, otherwise redirect to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }

    // Include config file
    require_once "config.php";

    // Define variables and initialize with empty values
    $comment = "";
    $comment_err = "";

    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Check if username is empty
        if(empty(trim($_POST["comment"]))){
            $comment_err = "Please enter a comment";
        } else{
            $comment = trim($_POST["comment"]);
        }

        // Validate credentials
        if(empty($comment_err)){
            $query = "SELECT firstName, lastName FROM users WHERE id = " . $_SESSION["id"];

            $result = $link->query($query);

            $row = mysqli_fetch_row($result);
            $name = $row[0] . " " . $row[1];

            // Prepare a select statement
            $sql = "INSERT INTO comments (postID, userName, comment) VALUES (?, ?, ?)";

            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "iss", $param_postID, $param_userName, $param_comment);

                // Set parameters
                $param_postID = $_GET["id"];
                $param_userName = $name;
                $param_comment = $comment;

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    header("location: post.php?id= " . $_GET['id']);
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }

        // Close connection
        mysqli_close($link);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Meme Generator</title>

    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Raleway:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link rel="stylesheet" href="assets/css/main.css">

    <script>
        function likeChange(like) {
            if (like.classList.contains('bi-heart')) {
                like.classList.remove('bi-heart');
                like.classList.add('bi-heart-fill');
            } else {
                like.classList.remove('bi-heart-fill');
                like.classList.add('bi-heart');
            }
        }

        function starChange(star) {
            if (star.classList.contains('bi-star')) {
                star.classList.remove('bi-star');
                star.classList.add('bi-star-fill');
            } else {
                star.classList.remove('bi-star-fill');
                star.classList.add('bi-star');
            }
        }
    </script>
</head>

<body>

<div class="hero d-flex justify-content-center align-items-center">
    <div class="sidebar bg-light">
        <ul class="p-0">
            <li>
                <a href="console.php">
                    <span class="icon"><i class="bi bi-border-all"></i></span>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="feed.php">
                    <span class="icon"><i class="bi bi-eye-fill"></i></span>
                    <span class="title">Feed</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="icon"><i class="bi bi-chat-fill"></i></span>
                    <span class="title">Message</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="icon"><i class="bi bi-info-circle-fill"></i></span>
                    <span class="title">Help</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="icon"><i class="bi bi-gear-fill"></i></span>
                    <span class="title">Settings</span>
                </a>
            </li>
            <li>
                <a href="reset-password.php">
                    <span class="icon"><i class="bi bi-lock-fill"></i></span>
                    <span class="title">Password</span>
                </a>
            </li>
            <li>
                <a href="logout.php">
                    <span class="icon"><i class="bi bi-box-arrow-left"></i></span>
                    <span class="title">Sign Out</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main">
        <div class="topbar bg-light p-3">
            <div class="toggle">
                <i class="bi bi-list"></i>
            </div>
            <!-- Search -->
            <div class="search">
                <label for="search">
                    <input type="text" id="search" placeholder="Search Here">
                    <i class="bi bi-search"></i>
                </label>
            </div>
            <!-- User Image -->
            <div class="user">
                <img src="assets/img/team/team-1.jpg">
            </div>
        </div>

        <div class="template-main">
            <?php

            require_once "config.php";

            $id = $_GET["id"];

            $sql = 'SELECT * FROM `posts` WHERE ID=' . $id;

            $result = mysqli_query($link, $sql);
            $rowcount=mysqli_num_rows($result);

            if ($rowcount == 0) {
                echo "There is not a post with that ID";
            } else {
                for ($i = 0; $i < $rowcount; $i++) {
                    $row = mysqli_fetch_array($result, MYSQLI_NUM);
                    echo "<div class='post-content'>";
                    echo "<img src='$row[1]'>";
                    echo "<p>$row[3]</p>";
                    echo "<p>$row[4]</p>";
                    echo "</div>";
                }
            }
            ?>

            <div class="template-sidebar bg-light" style="height: 100%; width: 600px">
                <div class="show-comments">
                    <?php

                    require_once "config.php";

                    $sql = 'SELECT * FROM `comments` WHERE postID=' . $id;

                    $result = mysqli_query($link, $sql);
                    $rowcount=mysqli_num_rows($result);

                    if ($rowcount == 0) {
                        echo "There are no comments for this post";
                    } else {
                        for ($i = 0; $i < $rowcount; $i++) {
                            $row = mysqli_fetch_array($result, MYSQLI_NUM);
                            echo "<div class='comment'>";
                            echo "<h2 class='comment-user'>$row[2]</h2>";
                            echo "<p class=\"comment-text\">$row[3]</p>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>

                <div class="comment-form bg-light">
                    <form action="" method="POST">
                        <input type="text" name="comment" id="comment">
                        <button type="submit">Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="preloader"></div>

<script>
    // MenuToggle
    let toggle = document.querySelector('.toggle');
    let navigation = document.querySelector('.sidebar');
    let main = document.querySelector('.main');

    toggle.onclick = function(){
        navigation.classList.toggle('active');
        main.classList.toggle('active');
    }
</script>

<!-- Vendor JS Files -->
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>
<?php
    // Initialize the session
    session_start();

    // Check if the user is logged in, otherwise redirect to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
    }

    include("like-dislike.php");
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

            $email = $_SESSION["email"];
            $stmt = $link->prepare("SELECT * FROM `posts`");
            $stmt->execute();

            $result = $stmt->get_result();
            $rowcount=mysqli_num_rows($result);

            if ($rowcount == 0) {
                echo "No posts at this time!";
            } else {
                for ($i = 0; $i < $rowcount; $i++) {
                    $row = mysqli_fetch_array($result, MYSQLI_NUM);
                    echo "<a href='post.php?id=" . $row[0] . "'>";
                    echo "<div class='card'>";
                    echo "<div class='cardColor'><img src='$row[1]'><div class='favorite'><i class='bi bi-star' onclick=\"starChange(this); event.preventDefault();\"></i></div> </div>";
                    echo "<div class='cardDetailsContent'>";
                    echo "<div class='subject'>$row[3]</div>";
                    echo "<div class='team'>$row[4]</div>";
                    echo "<div class='likes' id='likes'>
                            
                            <i class='bi bi-heart like-heart' onclick=\"likeChange(this); event.preventDefault();\"></i><p>$row[2]</p></div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</a>";

//                    <!-- if user likes post, style button differently -->
//      	<i <?php if (userLiked($post['id'])): ?>
<!--            class="fa fa-thumbs-up like-btn"-->
<!--            --><?php //else: ?>
<!--            class="fa fa-thumbs-o-up like-btn"-->
<!--            --><?php //endif ?>
<!--            data-id="--><?php //echo $post['id'] ?><!--"></i>-->
<!--                }-->
<!--            }-->
            ?>
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

    document.getElementById("likes").addEventListener("click", function() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "feed.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
            }
        };
        xhr.send();
    });
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
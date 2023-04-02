<?php
// Initialize the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

function rand_color() {
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>STS</title>

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
</head>

<body>

<header id="header" class="header d-flex align-items-center">

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
            <h1>STS<span>.</span></h1>
        </a>
        <nav id="navbar" class="navbar">
            <ul>
                <li><a href="index.php#hero">Home</a></li>
                <li><a href="index.php#about">About</a></li>
                <li><a href="index.php#services">Services</a></li>
                <li><a href="index.php#testimonials">Testimonials</a></li>
                <li><a href="index.php#team">Team</a></li>
                <li><a href="index.php#contact">Contact</a></li>

                <?php

                if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                    echo "<li><a href='login.php' class='custBtn'>Login</a></li>
                                    <li><a href='register.php' class='custBtn'>Register</a></li>";
                } else {
                    echo "<li><a href='logout.php' class='custBtn'>Logout</a></li>";
                    echo "<li><a href='add-ticket.php' class='custBtn'>Add Ticket</a></li>";
                }

                ?>
            </ul>
        </nav>

        <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
        <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    </div>
</header>

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
                <a href="all-tickets.php">
                    <span class="icon"><i class="bi bi-eye-fill"></i></span>
                    <span class="title">All Tickets</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <span class="icon"><i class="bi bi-people-fill"></i></span>
                    <span class="title">Community</span>
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

        <div class="cardDetails">
            <?php

            require_once "config.php";

            $id = $_GET["id"];

            $sql = 'SELECT * FROM `tickets` WHERE ID=' . $id;

            $result = mysqli_query($link, $sql);
            $rowcount=mysqli_num_rows($result);

            if ($rowcount == 0) {
                echo "There is not a ticket with that ID";
            } else {
                for ($i = 0; $i < $rowcount; $i++) {
                    $row = mysqli_fetch_array($result, MYSQLI_NUM);
                    echo "<div class='cardDetailsSide'>";
                    echo "<div class='cardDetailsSideBox'>";
                    echo "<i class='bi bi-people-fill'></i>";
                    echo "<p>$row[3]</p>";
                    echo "</div>";
                    echo "<div class='cardDetailsSideBox'>";
                    echo "<i class='bi bi-upc'></i>";
                    echo "<p>$row[4]</p>";
                    echo "</div>";
                    echo "<div class='cardDetailsSideBox'>";
                    echo "<i class='bi bi-person-fill'></i>";
                    echo "<p>$row[7]</p>";
                    echo "</div>";
                    echo "</div>";

                    echo "<div class='cardDetailsContent'>";
                    echo "<h2>$row[2]</h2>";
                    echo "<p>$row[5]</p>";
                    echo "<div class='cardDetailsContentBtnGroup'>";
                    echo "<a href='#'>Open Ticket</a>";
                    echo "<a href='#'>Message Client</a>";
                    echo "</div>";
                    echo "</div>";
                }
            }

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
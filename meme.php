<?php
// Initialize the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
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

        <div class="show-meme">
            <?php
                $image_url = $_GET['image_url'];
                echo '<img src="' . $image_url . '" id="myImg">';
            ?>
            <form id="myForm">
                <button type="submit" class="btn-reg" id="imageURL" value="<?php echo $image_url ?>">Generate Meme for this image</button>
            </form>
        </div>

        <div class="template-sidebar bg-light">
            <a href="meme.php?image_url=assets/img/memes/captain-picard.jpg"><img src="assets/img/memes/captain-picard.jpg"</a>
            <a href="meme.php?image_url=assets/img/memes/dogeeeee.jpg"><img src="assets/img/memes/dogeeeee.jpg"</a>
            <a href="meme.php?image_url=assets/img/memes/futurama-fry"><img src="assets/img/memes/futurama-fry.jpg"</a>
            <a href="meme.php?image_url=assets/img/memes/imagination.jpg"><img src="assets/img/memes/imagination.jpg"</a>
            <a href="meme.php?image_url=assets/img/memes/matrix-morpheus.jpg"><img src="assets/img/memes/matrix-morpheus.jpg"</a>
            <a href="meme.php?image_url=assets/img/memes/men-in-black.jpg"><img src="assets/img/memes/men-in-black.jpg"</a>
            <a href="meme.php?image_url=assets/img/memes/ptsd-karate-kyle.jpg"><img src="assets/img/memes/ptsd-karate-kyle.jpg"</a>
            <a href="meme.php?image_url=assets/img/memes/really-stoned-guy.jpg"><img src="assets/img/memes/really-stoned-guy.jpg"</a>
            <a href="meme.php?image_url=assets/img/memes/yo-dawg.jpg"><img src="assets/img/memes/yo-dawg.jpg"</a>
            <a href="meme.php?image_url=assets/img/memes/y-u-no.jpg"><img src="assets/img/memes/y-u-no.jpg"</a>
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

    let arr = ["Captain-Picard", "Dogeeeee", "Futurama-Fry", "Imagination", "Matrix-Morpheus",
               "man-in-black", "Really-Stoned-Guy", "PTSD-Karate-Kyle", "Y-U-No", "Yo-Dawg"];

    const form = document.getElementById("myForm");
    form.addEventListener("submit", (event) => {
        event.preventDefault(); // prevent default form submission behavior
        const input = document.getElementById("imageURL").value.substring(17)
        console.log(input)
        const input2 = input.substring(0, input.lastIndexOf("."));
        let input3;

        if (input == "captain-picard.jpg") {
            input3 = arr[0];
        } else if (input == "dogeeeee.jpg") {
            input3 = arr[1];
        } else if (input == "futurama-fry.jpg") {
            input3 = arr[2];
        } else if (input == "imagination.jpg") {
            input3 = arr[3];
        } else if (input == "matrix-morpheus.jpg") {
            input3 = arr[4];
        } else if (input == "men-in-black.jpg") {
            input3 = arr[5];
        } else if (input == "ptsd-karate-kyle.jpg") {
            input3 = arr[6];
        } else if (input == "really-stoned-guy.jpg") {
            input3 = arr[7];
        } else if (input == "yo-dawg.jpg") {
            input3 = arr[9];
        } else if (input == "y-u-no.jpg") {
            input3 = arr[8];
        }

        fetch("https://77e3-35-204-75-47.ngrok.io/generate?image_id=" + input3, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                "string": input3
            })
        })
            .then(response => response.blob())
            .then(blob => {
                const objectURL = URL.createObjectURL(blob);
                const img = document.getElementById('myImg');
                img.src = objectURL;
            })
            .catch(error => console.error(error));
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
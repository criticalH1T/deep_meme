<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$fname = $lname = $email = $password = $confirm_password = $dob = $phone = "";
$fname_err = $lname_err = $email_err = $password_err = $confirm_password_err = $dob_err = $phone_err =  "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate first name
    if(empty(trim($_POST["fname"]))) {
        $fname_err = "Please enter your first name";
    } elseif(!preg_match("/^[A-Za-z]{1,50}$/", trim($_POST["fname"]))) {
        $fname_err = "Your first name can contain only alphabetic characters";
    } else {
        $fname = trim($_POST["fname"]);
    }

    // Validate last name
    if(empty(trim($_POST["lname"]))) {
        $lname_err = "Please enter your last name";
    } elseif(!preg_match("/^[A-Za-z]{1,50}$/", trim($_POST["lname"]))) {
        $lname_err = "Your last name can contain only alphabetic characters";
    } else {
        $lname = trim($_POST["lname"]);
    }

    // Validate username
    if(empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } elseif(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", trim($_POST["email"]))){
        $email_err = "The email format is not correct!";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "There is already an account with this email";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 5){
        $password_err = "Password must have at least 5 characters.";
    } elseif (!preg_match('/[0-9]+/', trim($_POST["password"])) && !preg_match('/[A-Za-z]+/', trim($_POST["password"]))) {
        $password_err = "Password must contain at least 1 number and 1 letter";
    } elseif (!preg_match('/[0-9]+/', trim($_POST["password"]))) {
        $password_err = "Password must contain at least 1 number";
    } elseif (!preg_match('/[A-Za-z]+/', trim($_POST["password"]))) {
        $password_err = "Password must contain at least 1 letter";
    } elseif (!preg_match('/[A-Za-z]+/', trim($_POST["password"][0]))) {
        $password_err = "Password must start with a letter";
    }
    else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate date and format
    if (empty(trim($_POST["dob"]))) {
        $dob_err = "Please enter your date of birth";
    } else {
        $dob = $_POST['dob'];
        if (preg_match("/^\d{4}-\d{2}-\d{2}$/", $dob)) { // check if the date is in the format of YYYY-MM-DD
            $sql_date = date('Y-m-d', strtotime($dob)); // convert the date to the SQL date format
            $current_date = date('Y-m-d'); // get the current date in SQL date format

            if ($sql_date <= $current_date) { // check if the date of birth is not in the future
                $dob = $_POST['dob'];
            } else {
                $dob_err = "Date of birth cannot be in the future.";
            }
        } else {
            $dob_err = "Invalid date format. Please enter in YYYY-MM-DD format.";
        }
    }

    // Validate phone number
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter your phone number";
    } else {
        if (preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST["phone"])) {
            $phone = $_POST["phone"];
        } else {
            $phone_err = "Invalid phone number format";
        }
    }

    // Check input errors before inserting in database
    if(empty($fname_err) && empty($lname_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($dob_err) && empty($phone_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (firstName, lastName, email, password, dateOfBirth, phoneNumber) VALUES (?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_fname, $param_lname, $param_email, $param_password, $param_dob, $param_phone);

            // Set parameters
            $param_fname = $fname;
            $param_lname = $lname;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_dob = $dob;
            $param_phone = $phone;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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
    <link href="assets/css/main.css" rel="stylesheet">
</head>

<body>

    <section class="bg-color min-vh-100 d-flex justify-content-center align-items-center">
        <div class="bg-white p-5" style="width: 400px; max-width: 100%;">
            <h2 class="form-title mb-4" style="text-align: center">Register</h2>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="fname" name="fname" required class="<?php echo (!empty($fname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fname; ?>">
                    <span class="error" style="color: red;"><?php echo $fname_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname" required class="<?php echo (!empty($lname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lname; ?>">
                    <span class="error" style="color: red;"><?php echo $lname_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required class="<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                    <span class="error" style="color: red;"><?php echo $email_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required class="<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                    <span class="error" style="color: red;"><?php echo $password_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required class="<?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                    <span class="error" style="color: red;"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="text" class="form-control" id="dob" name="dob" required class="<?php echo (!empty($dob_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dob; ?>" placeholder="YYYY-MM-DD">
                    <span class="error" style="color: red;"><?php echo $dob_err; ?></span>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required class="<?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>">
                    <span class="error" style="color: red;"><?php echo $phone_err; ?></span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label><input type="checkbox" required> I agree to the terms and conditions</label>
                </div>

                <button type="submit" class="btnSubmit btn-reg">Register</button>

                <div class="d-flex justify-content-center align-items-center">
                    <p class="secondOption">Already have an account? <a href="login.php" class="login-link">Sign in now</a>.</p>
                </div>
            </form>
        </div>
    </section>

    <div id="preloader"></div>

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
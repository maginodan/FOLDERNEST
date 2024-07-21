<?php
session_start();
if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

include 'connection/config.php';
$msg = "";

if (isset($_GET['verification'])) {
    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE code='{$_GET['verification']}'")) > 0) {
        $query = mysqli_query($conn, "UPDATE users SET code='', is_verified=1 WHERE code='{$_GET['verification']}'");

        if ($query) {
            $msg = "<div class='alert alert-success'>Account verification has been successfully completed.</div>";
        }
    } else {
        header("Location: login.php");
    }
}

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            if ($row['is_verified'] == 1) {
                $_SESSION['SESSION_EMAIL'] = $email;
                $_SESSION['SESSION_ROLE'] = $row['role']; // Store the role in the session
                if ($row['role'] == 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: user_dashboard.php");
                }
                die();
            } else {
                $msg = "<div class='alert alert-info'>First verify your account and try again.</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>Email or password do not match.</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>No account found with that email.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>FOLDER NEST</title>
    <!-- Meta tag Keywords -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords" content="Login Form" />
    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
    <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
</head>
<body>
    <!-- form section start -->
    <section class="w3l-mockup-form">
        <div class="container">
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                    <div class="alert-close">
                        <span class="fas fa-times rounded-circle"></span>
                    </div>
                    <div class="w3l_form align-self">
                        <div class="left_grid_info">
                            <img src="images/tablet.png" alt="">
                        </div>
                    </div>
                    <div class="content-wthree">
                        <div class="logo">
                            <a href="#" class="app-brand-link gap-2">
                                <img src="images/logo3.png" alt="Your Logo Alt Text" class="app-brand-logo">
                                <span class="app-brand-text">Folder Nest</span>
                                <p>"At your service" </p>
                            </a>
                        </div>
                        <?php echo $msg; ?>
                        <form action="" method="post">
                            <input type="email" class="email" name="email" placeholder="Enter Your Email" required>
                            <input type="password" class="password" name="password" placeholder="Enter Your Password" style="margin-bottom: 2px;" required>
                            <p><a href="forgot-password.php" style="margin-bottom: 15px; display: block; text-align: right;">Forgot Password?</a></p>
                            <button name="submit" class="btn" type="submit">Login</button>
                        </form>
                        <div class="social-icons">
                            <p>Create Account! <a href="register.php">Register</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="js/jquery.min.js"></script>
    <script>
        $(document).ready(function (c) {
            $('.alert-close').on('click', function (c) {
                $('.main-mockup').fadeOut('slow', function (c) {
                    $('.main-mockup').remove();
                });
            });
        });
    </script>
</body>
</html>

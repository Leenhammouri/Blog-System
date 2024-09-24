<?php 
include 'config.php';

session_start();
$emailError = $passwordError = $generalError = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($email)) {
        $emailError = 'Email is required';
    }
    if (empty($password)) {
        $passwordError = 'Password is required';
    }

    if (empty($emailError) && empty($passwordError))
     {
        $sql = "SELECT password, role, fname FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1)
         {
            $row = mysqli_fetch_assoc($result);
            $stored_hashed_password = $row['password']; 


            if (password_verify($password, $stored_hashed_password))
             { 
                echo "Password matched!<br>";
                if (trim($row['role']) === 'admin')
                 {
                    echo "Role is admin. Redirecting...<br>";
                    header("Location: admin.php");
                    exit();
                } 
                else if (trim($row['role']) === 'author') 
                {
                    $_SESSION['name'] = $row['fname'];
                    header("Location: home.php?fname=" . urlencode($row['fname']));
                    exit();
                } 
            } 
            else {
                    $generalError =  "Incorrect Email or password"; 
            }
        }
        else
         {
            $generalError = "No user found with this email"; 
        }
    } 
}

mysqli_close($conn);
?>


<!doctype html>
<html lang="en">

<head>
    <title>Life Blog</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="login-wrap py-5">
                        <div class="img d-flex align-items-center justify-content-center"
                            style="background-image: url(images/bg.jpg);"></div>
                        <h3 class="text-center mb-0">Login</h3>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"
                            class="login-form">
                            <div class="form-group">
                                <div class="icon d-flex align-items-center justify-content-center"><span
                                        class="fa fa-user"></span></div>
                                <input type="email" class="form-control" placeholder="Username" required name="email">
                            </div>
                            <div class="form-group">
                                <div class="icon d-flex align-items-center justify-content-center"><span
                                        class="fa fa-lock"></span></div>
                                <input type="password" class="form-control" placeholder="Password" required
                                    name="password">
                            </div>
                            <p class="mb-0" id="emailError"><?php echo $emailError ?></p>
                            <p class="mb-0" id="passwordError"><?php echo $passwordError ?></p>
                            <p class="mb-0" id="generalError"><?php echo $generalError ?></p>

                            <div class="form-group">
                                <button type="submit"
                                    class="btn form-control btn-primary rounded submit px-3">Login</button>
                            </div>
                        </form>
                        <div class="w-100 text-center mt-4 text">
                            <p class="mb-0">Don't have an account?</p>
                            <a href="register.php">Sign Up</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>

</body>

</html>
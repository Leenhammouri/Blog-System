<?php 
    include 'config.php';

    session_start();

    $fnameError = $lnameError = $emailError = $passwordError = $confirmPasswordError = $passwordMatchError = $userAlreadyError = '';
    $email = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $fname = mysqli_real_escape_string($conn, $_POST['fname']);
        $lname = mysqli_real_escape_string($conn, $_POST['lname']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);

        if (empty($fname)) {
            $fnameError = 'First name is required';
        }
        if (empty($lname)) {
            $lnameError = 'Last name is required';
        }
        if (empty($email)) {
            $emailError = 'Email is required';
        }
        if (empty($password)) {
            $passwordError = 'Password is required';
        }
        if (empty($cpassword)) {
            $confirmPasswordError = 'Confirm password is required';
        }
        if ($password !== $cpassword) {
            $passwordMatchError = "Passwords do not match!";
        }

        if (empty($fnameError) && empty($lnameError) && empty($emailError) && empty($passwordError) && empty($confirmPasswordError) && empty($passwordMatchError)) {
            $sql = "SELECT email FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                $userAlreadyError = "A user with this email already exists.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (fname, lname, email, password, role) 
                        VALUES ('$fname', '$lname', '$email', '$hashed_password', 'author')";

                if (mysqli_query($conn, $sql)) {
                    header('Location: home.php');
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
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
                <div class="col-md-6 col-lg-6">
                    <div class="login-wrap py-5">
                        <div class="img d-flex align-items-center justify-content-center"
                            style="background-image: url(images/bg.jpg);"></div>
                        <h3 class="text-center mb-4">Sign Up</h3>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"
                            class="login-form">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="textl" class="form-control" placeholder="First Name" required
                                            name="fname">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" placeholder="Last Name" required
                                            name="lname">
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Email" required name="email"
                                    id="email-signup">
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="password" class="form-control" placeholder="Password" required id="password-signup"
                                            name="password">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="password" class="form-control" placeholder="Confirm Password"
                                            required name="cpassword">
                                    </div>

                                </div>
                            </div>
                            <p class="mb-0" id="passwordMatchError"><?php echo $passwordMatchError; ?></p>
                            <p class="mb-0" id="userAlreadyError"><?php echo $userAlreadyError; ?></p>

                            <div class="form-group">
                                <button type="submit" class="btn form-control btn-primary rounded submit px-3">Create
                                    Account
                                </button>
                            </div>
                        </form>
                        <div class="w-100 text-center mt-4 text">
                            <p class="mb-0">already have account ?</p>
                            <a href="index.php">Login</a>
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
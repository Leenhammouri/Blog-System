<?php

include 'config.php';

session_start();


$post = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['postIdToEdit'])) {
    $postIdToEdit = mysqli_real_escape_string($conn, $_POST['postIdToEdit']);
    
    $sql = "SELECT post_title, post_content, post_tag FROM posts WHERE post_id = '$postIdToEdit'";
    $result = mysqli_query($conn, $sql);
    $post = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updatePost'])) {
    $newTitle = mysqli_real_escape_string($conn, $_POST['postTitle']);
    $newCategory = mysqli_real_escape_string($conn, $_POST['category']);
    $newContent = mysqli_real_escape_string($conn, $_POST['postContent']);
    $postIdToEdit = mysqli_real_escape_string($conn, $_POST['postIdToEdit']);

    $sql = "UPDATE posts SET post_title = '$newTitle', post_tag = '$newCategory', post_content = '$newContent' WHERE post_id = '$postIdToEdit'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: admin.php?success=Post+updated&section=posts-managment-content");
        exit();
    } else {
        echo "Error updating post: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>My Blog</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/admin.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-light  px-3 custom-sky-blue ">
        <a class="navbar-brand" href="admin.php">Life Blog</a>
        <div class="navbar-collapse collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user fa-fw"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <form method="post" action="admin.php">
                                <input class="dropdown-item" value="Home" type="submit">
                            </form>
                        </li>
                        <hr class="dropdown-divider" />
                        <li>
                            <form method="post" action="logout.php">
                                <input class="dropdown-item" value="Logout" type="submit">
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>


    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light sb-sidenav-body" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">
                            <div id="profile" class="text-center mb-3">
                                <img class="img-thumbnail rounded-circle" src="images/profile.png" alt="Profile Image"
                                    style="width: 100px; height: 100px;">
                                <form method="post" action="userPosts.php" class="mt-2">
                                    <input class="btn btn-link text-primary" value="Admin" type="submit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </nav>


        </div>
        <div id="layoutSidenav_content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 mx-auto mt-4">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                            <input type="hidden" name="postIdToEdit"
                                value="<?php echo htmlspecialchars($postIdToEdit); ?>">

                            <div class="mb-3 mt-4">
                                <input type="text" class="form-control" name="postTitle" id="postTitle"
                                    placeholder="caption" required
                                    value="<?php echo htmlspecialchars($post['post_title']); ?>">
                            </div>
                            <div class="mb-3">
                                <select class="form-select" name="category" aria-label="Tags" required>
                                    <option selected disabled>Categories</option>
                                    <option value="Wellness"
                                        <?php if ($post['post_tag'] == 'Wellness') echo 'selected'; ?>>
                                        Wellness
                                    </option>
                                    <option value="Home & Decor"
                                        <?php if ($post['post_tag'] == 'Home & Decor') echo 'selected'; ?>>Home &
                                        Decor</option>
                                    <option value="Food & Recipes"
                                        <?php if ($post['post_tag'] == 'Food & Recipes') echo 'selected'; ?>>Food
                                        & Recipes</option>
                                    <option value="Fashion & Beauty"
                                        <?php if ($post['post_tag'] == 'Fashion & Beauty') echo 'selected'; ?>>
                                        Fashion & Beauty</option>
                                    <option value="Personal Growth"
                                        <?php if ($post['post_tag'] == 'Personal Growth') echo 'selected'; ?>>
                                        Personal Growth</option>
                                    <option value="Travel" <?php if ($post['post_tag'] == 'Travel') echo 'selected'; ?>>
                                        Travel</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" id="postContent" name="postContent" rows="4"
                                    placeholder="what's in your mind ..!"
                                    required><?php echo nl2br($post['post_content']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-dark" name="updatePost">update post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="js/admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>

</html>

<?php
mysqli_close($conn);
?>
<?php

include 'config.php';

session_start();

if (!isset($_SESSION['name'])) {
    header("Location: index.php");
    exit();
}

$name = $_SESSION['name'];


$sql = "SELECT user_id FROM users WHERE fname = '$name'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $user_id = $row['user_id'];

    $sql = "SELECT post_id,post_title, post_content, post_tag, created_at, CONCAT(fname, ' ', lname) AS author_name  , author_id
            FROM posts 
            JOIN users ON posts.author_id = users.user_id 
            WHERE users.user_id = '$user_id'
            ORDER BY created_at DESC";

    $result = mysqli_query($conn, $sql);
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
                            <form method="post" action="home.php">
                                <input class="dropdown-item" value="Home" type="submit">
                            </form>
                        </li>
                        <li>
                            <form method="post" action="userPosts.php">
                                <input class="dropdown-item" value="<?php echo htmlspecialchars($name); ?>"
                                    type="submit">
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
                                    <input class="btn btn-link text-primary"
                                        value="<?php echo htmlspecialchars($name); ?>" type="submit">
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
                        <?php foreach ($posts as $post): ?>
                        <article class="mb-4 p-3 border rounded shadow-sm">
                            <header class="mb-2">
                                <h2 class="article-title"><?php echo htmlspecialchars($post['post_title']); ?></h2>
                                <div class="text-muted">
                                    <i>Posted by
                                        <?php echo htmlspecialchars($post['author_name']); ?> on
                                        <?php echo $post['created_at']; ?> -
                                        <?php echo htmlspecialchars($post['post_tag']); ?>
                                    </i>
                                </div>
                            </header>
                            <section>
                                <p><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></p>
                            </section>
                            <div class="post-buttons mt-2">
                                <?php if ($post['author_id'] == $user_id): ?>
                                <form method="post" action="edit.php" class="d-inline">
                                    <input type="hidden" name="postIdToEdit"
                                        value="<?php echo htmlspecialchars($post['post_id']); ?>">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                </form>
                                <form method="post" action="delete.php" class="d-inline">
                                    <input type="hidden" name="postIdToDelete"
                                        value="<?php echo htmlspecialchars($post['post_id']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </article>
                        <?php endforeach; ?>
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
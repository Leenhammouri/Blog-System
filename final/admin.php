<?php
include 'config.php';

session_start();


$sql = "SELECT count(*) as users_count FROM users";
$result = mysqli_query($conn, $sql);
$usersCount = ($result) ? mysqli_fetch_assoc($result)['users_count'] : 0;

$sql = "SELECT count(*) as posts_count FROM posts";
$result = mysqli_query($conn, $sql);
$postsCount = ($result) ? mysqli_fetch_assoc($result)['posts_count'] : 0;

$sql = "SELECT user_id, fname, lname, email, role FROM users where email != 'admin@admin.com'";
$result = mysqli_query($conn, $sql);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (isset($_POST['delete'])) {
    $user_id = $_POST['user_id'];

    $sql = "DELETE FROM posts WHERE author_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $sql = "DELETE FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $user_id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            header("Location: admin.php?success=User+and+posts+deleted");
            exit();
        } else {
            echo "Error deleting user: " . mysqli_error($conn);
        }
    } else {
        echo "Error deleting user's posts: " . mysqli_error($conn);
    }
}

if (isset($_POST['edit_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];

    if ($new_role === 'author' || $new_role === 'admin') {
        $sql = "UPDATE users SET role = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'si', $new_role, $user_id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            header("Location: admin.php?success=Role+updated");
            exit();
        } else {
            echo "Error updating role: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid role selected.";
    }
}


$sql = "SELECT post_id, post_title, post_content, post_tag, created_at, posts.author_id, CONCAT(fname, ' ', lname) AS author_name 
        FROM posts 
        JOIN users ON posts.author_id = users.user_id 
        ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
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
    <nav class="sb-topnav navbar navbar-expand navbar-dark custom-sky-blue">
        <a class="navbar-brand ps-3" href="admin.php">Life Blog</a>
        <div class="navbar-collapse collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
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
            <nav class="sb-sidenav accordion sb-sidenav-body-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">

                        <div class="sb-sidenav-menu-heading">System Management</div>
                        <a class="nav-link active" href="#" id="user-management">
                            Users Management
                        </a>
                        <a class="nav-link" href="#" id="post-management">
                            Posts Management
                        </a>

                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Admin
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main id="users-managment-content">
                <div class="container-fluid px-4">
                    <h1 class="mt-4 mb-4">Dashboard</h1>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="card bg-light text-dark mb-4">
                                <div class="card-body">System Users Number</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <span><?php echo htmlspecialchars($usersCount); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="card bg-light text-dark mb-4">
                                <div class="card-body">System Posts Number</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <span><?php echo htmlspecialchars($postsCount); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Users Table
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['fname']); ?></td>
                                            <td><?php echo htmlspecialchars($user['lname']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                                            <td>
                                                <form action="admin.php" method="POST" style="display:inline;">
                                                    <input type="hidden" name="user_id"
                                                        value="<?php echo htmlspecialchars($user['user_id']); ?>">
                                                    <button class="btn btn-danger" type="submit" name="delete">
                                                        <i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="admin.php" method="POST"
                                                    style="display:inline-flex; align-items: center;">
                                                    <input type="hidden" name="user_id"
                                                        value="<?php echo htmlspecialchars($user['user_id']); ?>">
                                                    <select name="role" class="form-select me-2" style="width: auto;"
                                                        required>
                                                        <option value="author"
                                                            <?php if($user['role'] == 'author') echo 'selected'; ?>>
                                                            Author</option>
                                                        <option value="admin"
                                                            <?php if($user['role'] == 'admin') echo 'selected'; ?>>Admin
                                                        </option>
                                                    </select>
                                                    <button class="btn btn-success" type="submit" name="edit_role"> <i
                                                            class="fa-solid fa-pen-to-square"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </main>
            <main id="posts-managment-content">
                <div class="container-fluid px-4">
                    <h1 class="mt-4 mb-4">Dashboard</h1>

                    <div class="row">
                        <div class="col-xl-6 col-md-6">
                            <div class="card bg-light text-dark mb-4">
                                <div class="card-body">System Users Number</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <span><?php echo htmlspecialchars($usersCount); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6">
                            <div class="card bg-light text-dark mb-4">
                                <div class="card-body">System Posts Number</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <span><?php echo htmlspecialchars($postsCount); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Posts Table
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Post Title</th>
                                            <th>Post Content</th>
                                            <th>Post Tag</th>
                                            <th>Create Time</th>
                                            <th>Post Author</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($posts as $post): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($post['post_title']); ?></td>
                                            <td class="truncate">
                                                <?php echo htmlspecialchars($post['post_content']); ?></td>
                                            <td><?php echo htmlspecialchars($post['post_tag']); ?></td>
                                            <td><?php echo htmlspecialchars($post['created_at']); ?></td>
                                            <td><?php echo htmlspecialchars($post['author_name']); ?></td>
                                            <td>
                                                <form action="delete-post.php" method="POST" style="display:inline;">
                                                    <input type="hidden" name="post_id"
                                                        value="<?php echo htmlspecialchars($post['post_id']); ?>">
                                                    <button class="btn btn-danger" type="submit" name="delete"><i
                                                            class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="admin-edit-post.php" method="POST"
                                                    style="display:inline-flex; align-items: center;">
                                                    <input type="hidden" name="postIdToEdit"
                                                        value="<?php echo htmlspecialchars($post['post_id']); ?>">

                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fa-solid fa-pen-to-square"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </main>
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
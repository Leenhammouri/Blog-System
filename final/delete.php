<?php

include 'config.php';

session_start();

if (!isset($_SESSION['name'])) {
    header("Location: index.php");
    exit();
}

$name = $_SESSION['name'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = mysqli_real_escape_string($conn, $_POST['postIdToDelete']);
    
    $sql = "SELECT user_id FROM users WHERE fname = '$name'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['user_id'];

        $sql = "DELETE FROM posts WHERE post_id = '$post_id' AND author_id = '$user_id'";

        if (mysqli_query($conn, $sql)) {
            header("Location: home.php");
        exit();
        } else {
            echo "Error deleting post: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>
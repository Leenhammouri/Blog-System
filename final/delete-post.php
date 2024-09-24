<?php

include 'config.php';

session_start();


$name = $_SESSION['name'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    
     $sql = "DELETE FROM posts WHERE post_id = '$post_id'";

        if (mysqli_query($conn, $sql)) {
            header("Location: admin.php");
        exit();
        } else {
            echo "Error deleting post: " . mysqli_error($conn);
        }
    
}

mysqli_close($conn);
?>
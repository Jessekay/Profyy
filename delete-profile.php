<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: signup.html");
    exit();
}

$username = $_SESSION['username'];
try {
    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    if ($stmt->execute([$username])) {
        session_destroy();
        $_SESSION['success'] = "Profile deleted successfully.";
        header("Location: index.html");
        exit();
    } else {
        $_SESSION['errors'] = ["Error deleting profile."];
        header("Location: profile.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['errors'] = ["Database error: " . $e->getMessage()];
    header("Location: profile.php");
    exit();
}
?>
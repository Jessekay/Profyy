<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: signup.html");
    exit();
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
if ($stmt->execute([$username])) {
    session_destroy();
    header("Location: index.html");
    exit();
} else {
    $_SESSION['errors'] = ["Error deleting profile."];
    header("Location: profile.php");
    exit();
}
?>
<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: signup.html");
    exit();
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $username = $_SESSION['username'];

    if (empty($full_name) || strlen($full_name) < 2) {
        $errors[] = "Full name must be at least 2 characters long.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND username != ?");
    $stmt->execute([$email, $username]);
    if ($stmt->fetch()) {
        $errors[] = "Email already exists.";
    }

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE username = ?");
            if ($stmt->execute([$full_name, $email, $username])) {
                $_SESSION['success'] = "Profile updated successfully.";
                header("Location: profile.php");
                exit();
            } else {
                $errors[] = "Error updating profile.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }

    $_SESSION['errors'] = $errors;
    header("Location: profile.php");
    exit();
}
?>
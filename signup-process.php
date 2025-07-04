<?php
session_start();
require_once 'db_connect.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($full_name) || strlen($full_name) < 2) {
        $errors[] = "Full name must be at least 2 characters long.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($username) || strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters long.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email or username exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    if ($stmt->fetch()) {
        $errors[] = "Email or username already exists.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, username, password) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$full_name, $email, $username, $hashed_password])) {
            $_SESSION['username'] = $username;
            header("Location: profile.php");
            exit();
        } else {
            $errors[] = "Error creating account. Please try again.";
        }
    }

    // Store errors in session and redirect back to signup
    $_SESSION['errors'] = $errors;
    header("Location: signup.html");
    exit();
}
?>
<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: signup.html");
    exit();
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT full_name, email, username, created_at FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: signup.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <img src="logo.png" alt="User Management System Logo" class="logo">
        <h1>User Profile</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="signup.html">Sign Up</a></li>
            <li><a href="profile.php">Profile</a></li>
        </ul>
    </nav>
    <main>
        <section>
            <img src="profile-image.png" alt="User Profile Image" class="profile-image">
            <article>
                <h2>Welcome, <?php echo htmlspecialchars($user['full_name']); ?>!</h2>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Joined:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>
                <form action="update-profile.php" method="post">
                    <h3>Update Profile</h3>
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    
                    <button type="submit">Update</button>
                </form>
                <form action="delete-profile.php" method="post" onsubmit="return confirm('Are you sure you want to delete your profile?');">
                    <button type="submit" class="delete-btn">Delete Profile</button>
                </form>
            </article>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 User Management System. All rights reserved.</p>
    </footer>
</body>
</html>
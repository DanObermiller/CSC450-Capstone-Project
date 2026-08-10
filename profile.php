<?php

require_once __DIR__ . "/init.php";
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['user_id'];
$stmt = $pdo->prepare(
    "SELECT full_name, display_name, description, email, role, created_at
     FROM users
     WHERE user_id = ?"
);
$stmt->execute([$userId]);
$user = $stmt->fetch();   // one row as an array, or false if none

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<html>
<head>
  <meta charset="utf-8">
  <title>My Profile</title>
  <link rel="stylesheet" type="text/css" href="home.css">
  <style>
    body a { color: #0056b3; }
  </style>
</head>
<body>
  <h1>My Profile</h1>
  <p><strong>Name:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
  <p><strong>Display name:</strong> <?= htmlspecialchars($user['display_name'] ?? $user['full_name']) ?></p>
  <p><strong>About:</strong> <?= htmlspecialchars($user['description'] ?? '') ?></p>
  <p><strong>Member since:</strong> <?= htmlspecialchars($user['created_at']) ?></p>


  <p><a href="edit_profile.php">Edit profile</a></p>
  <p><a href="index.php">Back to store</a></p>
  
  <?php if ($user['role'] === 'admin'): ?>
    <p><em>You have admin access.</em></p>
  <?php endif; ?>

</body>
</html>

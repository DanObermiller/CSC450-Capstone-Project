<?php
require_once __DIR__ . "/init.php";
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}
$userId = $_SESSION['user']['user_id'];
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $displayName = trim($_POST['display_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    if ($displayName === '') {
        $error = "Display name is required.";
    } elseif (strlen($displayName) > 50) {
        $error = "Display name must be 50 characters or less.";
    } elseif (strlen($description) > 500) {
        $error = "Description must be 500 characters or less.";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET display_name = ?, description = ? WHERE user_id = ?");
        $stmt->execute([$displayName, $description, $userId]);
        header("Location: profile.php");
        exit;
    }
}
$stmt = $pdo->prepare("SELECT display_name, description FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Edit Profile</title></head>
<body>
<h1>Edit Profile</h1>
<p style="color:red;"><?= htmlspecialchars($error) ?></p>
<form method="post">
  <input name="display_name" value="<?= htmlspecialchars($user['display_name'] ?? '') ?>"><br>
  <textarea name="description" rows="4" cols="40"><?= htmlspecialchars($user['description'] ?? '') ?></textarea><br>
  <button type="submit">Save</button>
</form>
<p><a href="profile.php">Back to profile</a></p>
</body>
</html>

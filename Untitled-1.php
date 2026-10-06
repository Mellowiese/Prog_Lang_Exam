<?php
require 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([trim($_POST['username'] ?? '')]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($_POST['password'] ?? '', $row['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = $row['username'];
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Wrong credentials. Try again.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
<link href="https://fonts.googleapis.com/css2?family=Inria+Serif&display=swap" rel="stylesheet">
<style>
  body { margin:0; min-height:100vh; display:grid; place-items:center; background:#f4f4f5; font-family:'Inria Serif', serif; }
  .card {
    width:min(768px, 94vw); aspect-ratio:3/2; border-radius:10px; overflow:hidden;
    background:#a7f3d0 url('bg.png') center/cover no-repeat;  /* your Figma leaf art */
    display:flex; justify-content:flex-end; align-items:center; position:relative;
  }
  .logo { position:absolute; top:14px; left:16px; display:flex; align-items:center; gap:6px; color:#115e59; font-size:1rem; letter-spacing:.08em; }
  .panel {
    width:42%; margin-right:5%; padding:6%; box-sizing:border-box;
    background:rgba(214,211,209,.4); border:1px solid #71717a; border-radius:6px; text-align:center;
  }
  h2 { margin:0 0 12px; font-weight:normal; font-size:1.1rem; }
  label { display:block; text-align:left; font-size:.75rem; margin-top:8px; }
  input { width:100%; box-sizing:border-box; padding:6px; border:1px solid rgba(113,113,122,.4); border-radius:4px; background:linear-gradient(to right, rgba(115,115,115,.2), #e5e5e5); }
  button { width:100%; margin-top:14px; padding:8px; border:0; border-radius:4px; color:#fff; font:inherit; cursor:pointer; background:linear-gradient(to right, #115e59, #0f766e, #2dd4bf); }
  .err { color:#b91c1c; font-size:.75rem; min-height:1em; }
  a { font-size:.7rem; color:#000; display:block; margin-top:10px; }
</style>
</head>
<body>
<div class="card">
  <form class="panel" method="post">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#115e59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/>
      <line x1="16" y1="8" x2="2" y2="22"/>
      <line x1="17.5" y1="15" x2="9" y2="15"/>
    </svg>
    <h2>USER LOGIN</h2>
    <div class="err"><?= htmlspecialchars($error) ?></div>
    <label for="u">Enter Username:</label>
    <input id="u" name="username" required>
    <label for="p">Enter Password:</label>
    <input id="p" name="password" type="password" required>
    <button>LOGIN</button>
    <a href="#">Forgot Password?</a>
    <a href="register.php">Need an account?</a>
  </form>
</div>
</body>
</html>
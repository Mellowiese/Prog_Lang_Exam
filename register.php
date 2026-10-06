<?php
require 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user    = trim($_POST['username'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $pass    = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($user === '' || strlen($pass) < 6) {
        $error = 'Username required, password 6+ chars.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'That email looks fake.';
    } elseif ($pass !== $confirm) {
        $error = "Passwords don't match.";
    } else {
        try {
            $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$user, $email, password_hash($pass, PASSWORD_DEFAULT)]);
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Username taken. Someone beat you to it.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register</title>
<link href="https://fonts.googleapis.com/css2?family=Inria+Serif&display=swap" rel="stylesheet">
<style>
  * { box-sizing:border-box; }
  body { margin:0; min-height:100vh; display:grid; place-items:center; background:#f4f4f5; font-family:'Inria Serif', serif; }
  .card {
    width:min(768px, 94vw); aspect-ratio:3/2; border-radius:10px; overflow:hidden;
    display:grid; place-items:center; padding:3%;
    background:
      url('bg.png') center/cover no-repeat,
      linear-gradient(to bottom, #dcebe4 0%, #d3e9de 60%, #a7e8c8 100%);
  }
  .panel {
    width:100%; padding:3% 5%; text-align:center;
    background:rgba(214,211,209,.35); border:1px solid rgba(113,113,122,.5); border-radius:14px;
    backdrop-filter:blur(3px);
  }
  h2 { margin:2px 0 8px; font-weight:normal; font-size:1.3rem; letter-spacing:.02em; }
  .grid { display:grid; grid-template-columns:1fr 1fr; gap:12px 30px; text-align:left; padding:0 3%; }
  label { display:block; font-size:.7rem; margin-bottom:3px; }
  input { width:100%; padding:6px; border:1px solid rgba(113,113,122,.4); border-radius:4px; background:linear-gradient(to right, rgba(115,115,115,.45), #e5e5e5); font:inherit; }
  button { margin-top:16px; padding:7px 30px; border:1px solid rgba(0,0,0,.2); border-radius:4px; color:#fff; font:inherit; font-size:.95rem; letter-spacing:.03em; cursor:pointer; background:linear-gradient(to right, #115e59, #0f766e, #2dd4bf); }
  .err { color:#b91c1c; font-size:.75rem; min-height:1em; }
  a { display:block; margin-top:8px; font-size:.7rem; color:#000; }
  @media (max-width:600px) { .card { aspect-ratio:auto; } .grid { grid-template-columns:1fr; gap:10px; } }
</style>
</head>
<body>
<div class="card">
  <form class="panel" method="post">
    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#0f766e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/>
      <line x1="16" y1="8" x2="2" y2="22"/>
      <line x1="17.5" y1="15" x2="9" y2="15"/>
    </svg>
    <h2>CREATE USER ACCOUNT</h2>
    <div class="err"><?= htmlspecialchars($error) ?></div>
    <div class="grid">
      <div><label for="u">Create Username:</label><input id="u" name="username" required></div>
      <div><label for="p">Create Password:</label><input id="p" name="password" type="password" minlength="6" required></div>
      <div><label for="e">Enter Email Address:</label><input id="e" name="email" type="email" required></div>
      <div><label for="c">Confirm Password:</label><input id="c" name="confirm" type="password" required></div>
    </div>
    <button>CREATE ACCOUNT</button>
    <a href="login.php">Already have an account?</a>
  </form>
</div>
</body>
</html>
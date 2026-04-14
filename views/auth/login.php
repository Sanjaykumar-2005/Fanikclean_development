<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - FanikClean</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/login.css">
</head>
<body>

<div class="container">
  <h2>Welcome Back</h2>
  <p class="subtitle">Login to your account</p>

  <?php if(isset($_SESSION['error'])): ?>
    <div style="color:red; margin-bottom: 15px;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>
  <?php if(isset($_SESSION['success'])): ?>
    <div style="color:green; margin-bottom: 15px;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>

  <form method="POST" action="/login">
    <div class="input-group">
      <input type="email" name="email" id="email" placeholder="Email" required>
    </div>
    <div class="input-group">
      <input type="password" name="password" id="password" placeholder="Password" required>
    </div>
    <button type="submit">Login</button>
  </form>

  <div class="link">
    Don't have an account? <a href="/signup">Sign Up</a>
  </div>
</div>

</body>
</html>

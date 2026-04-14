<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Signup - FanikClean</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/signup.css">
</head>
<body>

<div class="container">
  <h2>Create Account</h2>

  <?php if(isset($_SESSION['error'])): ?>
    <div style="color:red; margin-bottom: 15px;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form method="POST" action="/signup">
    <div class="input-group">
      <input type="text" name="full_name" id="name" placeholder="Full Name" required>
    </div>
    <div class="input-group">
      <input type="email" name="email" id="email" placeholder="Email" required>
    </div>
    <div class="input-group">
      <input type="password" name="password" id="password" placeholder="Password" required>
    </div>
    <div class="input-group">
      <input type="password" name="confirm" id="confirm" placeholder="Confirm Password" required>
    </div>
    <button type="submit">Sign Up</button>
  </form>

  <div class="link">
    Already have an account? <a href="/login">Login</a>
  </div>
</div>

</body>
</html>

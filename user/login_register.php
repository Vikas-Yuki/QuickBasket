<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login/Register | QuickBasket</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet" />
</head>
<body>
  <div class="container" id="container">
    <div class="form-container sign-up-container">
      <form action="register.php" method="POST">
        <h1>Create Account</h1>
        <input type="text" placeholder="Name" name="name" required />
        <input type="email" placeholder="Email" name="email" required />
        <input type="password" placeholder="Password" name="password" required />
        <button type="submit">Register</button>
      </form>
    </div>
    <div class="form-container sign-in-container">
      <form action="login.php" method="POST">
        <h1>Sign in</h1>
        <input type="email" placeholder="Email" name="email" required />
        <input type="password" placeholder="Password" name="password" required />
        <button type="submit">Login</button>
      </form>
    </div>
    <div class="overlay-container">
      <div class="overlay">
        <div class="overlay-panel overlay-left">
          <h1>Welcome Back!</h1>
          <p>To stay connected, please login with your credentials</p>
          <button class="ghost" id="signIn">Login</button>
        </div>
        <div class="overlay-panel overlay-right">
          <h1>Hello, Friend!</h1>
          <p>Enter your details and start your shopping journey with us</p>
          <button class="ghost" id="signUp">Register</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const container = document.getElementById('container');
    document.getElementById('signUp').addEventListener('click', () => {
      container.classList.add("right-panel-active");
    });
    document.getElementById('signIn').addEventListener('click', () => {
      container.classList.remove("right-panel-active");
    });
  </script>
</body>
</html>

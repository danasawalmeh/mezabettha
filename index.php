<?session_start();
if (!isset($_SESSION['email'])) {
    header("Location: home.php");
    exit();
}?>


<!DOCTYPE html>
<html lang="en">
<head>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Arial', sans-serif;
    }
    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #fff;
      padding: 20px 60px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .logo {
      font-size: 24px;
      font-weight: bold;
      font-family: 'Cairo', sans-serif; /* for Arabic-friendly font */
    }
    .nav-links {
      display: flex;
      gap: 30px;
    }
    .nav-links a {
      text-decoration: none;
      color: #000;
      font-size: 16px;
      font-weight: 500;
    }
    .nav-links a:hover {
      color: #4486ad;
    }
  </style>
  <nav>
  <div class="logo">منزبطها</div>
  <div class="nav-links">
    <a href="home.php">Home</a>
    <a href="about.php">About</a>
    <a href="package.php">Package</a>
    <a href="book.php">Book</a>
    <a href="index.php">Login</a>

  </div>
</nav> 
<meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register & Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
  
</head>
<body>

  

  <!-- Register Form -->
  <div class="container" id="signup" style="display: none;">
    <h1 class="form-title">Register</h1>
    <form method="post" action="register.php">
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" name="fName" id="fName" placeholder="First Name" required />
        <label for="fName">First Name</label>
      </div>
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" name="lName" id="lName" placeholder="Last Name" required />
        <label for="lName">Last Name</label>
      </div>
      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" id="email" placeholder="Email" required />
        <label for="email">Email</label>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="password" placeholder="Password" required />
        <label for="password">Password</label>
      </div>
      <input type="submit" class="btn" value="Sign Up" name="signUp" />
    </form>
    <p class="or">----------or--------</p>
    <div class="icons">
      <a href="google_auth.php"><i class="fab fa-google"></i></a>
      <a href="facebook_auth.php"><i class="fab fa-facebook"></i></a>
    </div>
    <div class="links">
      <p>Already have an account?</p>
      <button id="signInButton">Sign In</button>
    </div>
  </div>

  <!-- Login Form -->
  <div class="container" id="signIn">
    <h1 class="form-title">Sign In</h1>
    <form method="post" action="register.php">
      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" id="email" placeholder="Email" required />
        <label for="email">Email</label>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="password" placeholder="Password" required />
        <label for="password">Password</label>
      </div>
      <p class="recover"><a href="forgot.php">Recover Password</a></p>
      <input type="submit" class="btn" value="Sign In" name="signIn" />
    </form>
    <p class="or">----------or--------</p>
    <div class="icons">
      <a href="google_auth.php"><i class="fab fa-google"></i></a>
      <a href="facebook_auth.php"><i class="fab fa-facebook"></i></a>
    </div>
    <div class="links">
      <p>Don't have an account?</p>
      <button id="signUpButton">Sign Up</button>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const signUpButton = document.getElementById("signUpButton");
      const signInButton = document.getElementById("signInButton");
      const signUpForm = document.getElementById("signup");
      const signInForm = document.getElementById("signIn");

      signUpButton.addEventListener("click", function () {
        signUpForm.style.display = "block";
        signInForm.style.display = "none";
      });

      signInButton.addEventListener("click", function () {
        signUpForm.style.display = "none";
        signInForm.style.display = "block";
      });
    });
  </script>
</body>
</html>

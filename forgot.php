<?php 
session_start();
$error = array();

require "mail.php";

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "menzabetha";

$con = mysqli_connect($host, $user, $pass, $db);
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Determine mode
$mode = "enter_email";
if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($mode) {
        case 'enter_email':
            $email = $_POST['email'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error[] = "Please enter a valid email";
            } elseif (!valid_email($email)) {
                $error[] = "That email was not found";
            } else {
                $_SESSION['forgot']['email'] = $email;
                send_email($email);
                header("Location: forgot.php?mode=enter_code");
                exit;
            }
            break;

        case 'enter_code':
            $code = $_POST['code'];
            $result = is_code_correct($code);
            if ($result === "the code is correct") {
                $_SESSION['forgot']['code'] = $code;
                header("Location: forgot.php?mode=enter_password");
                exit;
            } else {
                $error[] = $result;
            }
            break;

        case 'enter_password':
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            if ($password !== $password2) {
                $error[] = "Passwords do not match";
            } elseif (!isset($_SESSION['forgot']['email']) || !isset($_SESSION['forgot']['code'])) {
                header("Location: forgot.php");
                exit;
            } else {
                save_password($password);
                unset($_SESSION['forgot']);
                header("Location: home.php");
                exit;
            }
            break;
    }
}

// Helper functions
function send_email($email) {
    global $con;
    $expire = time() + (60 * 5); // 5 minutes
    $code = rand(10000, 99999);
    $email = addslashes($email);
    $query = "INSERT INTO codes (email, code, expire) VALUES ('$email', '$code', '$expire')";
    mysqli_query($con, $query);
    send_mail($email, 'Password reset', "Your code is: " . $code);
}

function save_password($password) {
    global $con;
    $email = addslashes($_SESSION['forgot']['email']);
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE codes SET password = '$hashed' WHERE email = '$email' LIMIT 1";
    mysqli_query($con, $query);
}

function valid_email($email) {
    global $con;
    $email = addslashes($email);
    $query = "SELECT * FROM codes WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($con, $query);
    return ($result && mysqli_num_rows($result) > 0);
}

function is_code_correct($code) {
    global $con;
    $email = addslashes($_SESSION['forgot']['email']);
    $code = addslashes($code);
    $now = time();
    $query = "SELECT * FROM codes WHERE email = '$email' AND code = '$code' ORDER BY id DESC LIMIT 1";
    $result = mysqli_query($con, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return ($row['expire'] > $now) ? "the code is correct" : "the code is expired";
    }
    return "the code is incorrect";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="forgot.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="form-container">
  <?php if (!empty($error)): ?>
    <div class="error-box">
      <?php foreach ($error as $err): ?>
        <p><?= htmlspecialchars($err) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="form-header">
    <h2>
      <?php 
        echo $mode === 'enter_email' ? 'Forgot Password' : 
             ($mode === 'enter_code' ? 'Enter Verification Code' : 'Set New Password');
      ?>
    </h2>
    <p>
      <?php 
        echo $mode === 'enter_email' ? 'Enter your email to receive a reset code' : 
             ($mode === 'enter_code' ? 'Check your email for the verification code' : 'Create a new password for your account');
      ?>
    </p>
  </div>

  <?php switch ($mode): case 'enter_email': ?>
    <form method="post" action="forgot.php?mode=enter_email">
      <div class="form-group">
        <label>Email Address</label>
        <input type="email" class="form-control" name="email" placeholder="your@email.com" required>
      </div>
      <button type="submit" class="btn btn-primary">Send Reset Code</button>
      <div class="form-footer">
        Remember your password? <a href="index.php">Sign in</a>
      </div>
    </form>

  <?php break; case 'enter_code': ?>
    <form method="post" action="forgot.php?mode=enter_code">
      <div class="form-group">
        <label>Verification Code</label>
        <input type="text" class="form-control" name="code" placeholder="Enter 5-digit code" required>
      </div>
      <div class="btn-group">
        <a href="forgot.php" class="btn btn-secondary">Start Over</a>
        <button type="submit" class="btn btn-primary">Verify Code</button>
      </div>
      <div class="form-footer">
        Didn't receive code? <a href="forgot.php">Resend</a>
      </div>
    </form>

  <?php break; case 'enter_password': ?>
    <form method="post" action="forgot.php?mode=enter_password">
      <div class="form-group">
        <label>New Password</label>
        <input type="password" class="form-control" name="password" placeholder="Create new password" required>
      </div>
      <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" class="form-control" name="password2" placeholder="Retype your password" required>
      </div>
      <div class="btn-group">
        <a href="forgot.php" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Reset Password</button>
      </div>
    </form>
  <?php break; endswitch; ?>
</div>

</body>
</html>
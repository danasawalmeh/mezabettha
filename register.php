<?php
include 'connect.php';
session_start();

if (isset($_POST['signUp'])) {
    $firstName = trim($_POST['fName']);
    $lastName  = trim($_POST['lName']);
    $email     = trim($_POST['email']);
    $password  = $_POST['password'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email address already exists!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insert = $conn->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
        $insert->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

        if ($insert->execute()) {
            header("Location: index.php?register=success");
            exit();
        } else {
            echo "Error: " . $insert->error;
        }
    }
    $stmt->close();
}

if (isset($_POST['signIn'])) {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email']   = $user['email'];
            header("Location: home.php");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No account found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>

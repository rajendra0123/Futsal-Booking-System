<!DOCTYPE html>
<html lang="en">
<?php
include 'conn.php';
?>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    .signup-form {
      height: fit-content;
      width: 300px;
      margin-left: 500px;
      margin-top: 60px;
      padding: 20px;
      background: ##fff;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .signup-form h2 {
      text-align: center;
      margin-bottom: 20px;
      font-family: Arial, sans-serif;
      font-size: 30px;
    }

    .signup-form input[type="text"],
    .signup-form input[type="password"],
    .signup-form input[type="email"] {
      width: 90%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid black;
      border-radius: 5px;

    }

    .signup-form p {
      margin-bottom: 10px;
      font-family: Arial, sans-serif;
      font-size: 17px;
    }

    .signup-form .btn-container {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }

    .signup-form button {
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      border-radius: 3px;
      background-color: #0077c0;
      color: #fff;
      cursor: pointer;


    }

    .signup-form button:hover {
      background-color: #005e9d;

    }

    .error {
      color: red;
    }
  </style>
</head>

<body>
  <div class="signup-form">
    <h2>Sign up</h2>
    <form name="myForm" method="POST">
      <div class='error' id="fullnameError"></div>
      <input type="text" id="fullname" name="fullname" placeholder="Full Name">
      <div class='error' id="emailError"></div>
      <input type="text" id="email" name="email" placeholder="Email">
      <div class='error' id="numError"></div>
      <input type="text" id="number" name="contact" placeholder="Contact">
      <div class='error' id="pwdError"></div>
      <input type="password" id="password" name="password" placeholder="Password">
      <div class='error' id="cpwdError"></div>
      <input type="password" id="confirmPassword" name="cpassword" placeholder="Confirm Password">
      <p>Sign up as:</p>
      <div style="display: flex; align-items: center;">
        <p><input type="radio" name="role" value="player" required>Player</p>
        <p><input type="radio" name="role" value="owner" style="margin-left: 45px;" required>Owner</p>
      </div>
      <div class="btn-container">
        <button id="btnSubmit" name="signup" type="submit">Sign up</button>
      </div>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
  </div>
  </div>

  <!-- validation -->
  <script>
    btnSubmit = document.getElementById('btnSubmit');
    btnSubmit.addEventListener('click', (event) => {

      const fullname = document.getElementById("fullname").value;
      const email = document.getElementById("email").value;
      const number = document.getElementById("number").value;
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirmPassword").value;

      //fullname validation
      const fullnameError = document.getElementById("fullnameError");
      if (fullname === "") {
        event.preventDefault();
        fullnameError.textContent = "*required";
      }
      else if (fullname.length < 4) {
        event.preventDefault();
        fullnameError.textContent = "Fullname must contain more than 5 characters";
      }
      else if (/\d/.test(fullname)) {
        event.preventDefault();
        fullnameError.textContent = "Fullname cannot contain numbers";
      }
      else {
        fullnameError.textContent = "";
      }

      //email validation
      const emailError = document.getElementById("emailError");
      const emailPattern = /^[A-Za-z]+[0-9]*@gmail\.com$/i;
      if (email === "") {
        event.preventDefault();
        emailError.textContent = "*required";
      }
      else if (!emailPattern.test(email)) {
        event.preventDefault();
        emailError.textContent = "Email must be in proper Email format";
      }
      else if (email.length < 5) {
        event.preventDefault();
        emailError.textContent = "Email must be more than 5 characters";
      }
      else {
        emailError.textContent = "";
      }

      //number vlidation
      const numError = document.getElementById("numError");
      var regexPattern = (/^\d+$/);
      if (number === "") {
        event.preventDefault();
        numError.textContent = "*required";
      } else if (!regexPattern.test(number)) {
        event.preventDefault();
        numError.textContent = "Contact number must contain only digits";
      }
      else if (number.length != 10) {
        event.preventDefault();
        numError.textContent = "Contact number must be 10 digits";
      }
      else {
        numError.textContent = "";
      }

      //password validation
      const pwdError = document.getElementById("pwdError");
      var regexPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d!@#$%^&*()\-_=+{}[\]|;:'",.<>/?\\]+$/;
      if (password === "") {
        event.preventDefault();
        pwdError.textContent = "*required";
      } else if (password.length < 8) {
        event.preventDefault();
        pwdError.textContent = "Password must be at least 8 characters";
      } else if (!regexPattern.test(password)) {
        event.preventDefault();
        pwdError.textContent = "Password must contain at least one lowercase letter, one uppercase letter, and one digit";
      } else {
        pwdError.textContent = "";
      }



      //confirm pssword validation
      const cpwdError = document.getElementById("cpwdError");
      if (password !== confirmPassword) {
        event.preventDefault();
        cpwdError.textContent = "Passwords do not match";
      }
      else {
        cpwdError.textContent = "";
      }
    })
  </script>
  <?php

  // Check if the form is submitted
  if (isset($_POST['signup'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    // Hash the password
    $hash = password_hash($password, PASSWORD_DEFAULT);

    if ($role == 'player') {
      $sql = "INSERT INTO player (fullname, email, contact, password) 
            VALUES ('$fullname', '$email', '$contact', '$hash')";

      if ($con->query($sql) === TRUE) {

        echo '<script>window.location.href="login.php?signup=success"</script>';
        exit();
      } else {
        echo '<script>alert("Email or Contact already exists!")</script>';
      }
    } else if ($role == 'owner') {
      $sql = "INSERT INTO owner (fullname, email, contact, password) 
            VALUES ('$fullname', '$email', '$contact', '$hash')";

      if ($con->query($sql) === TRUE) {

        echo '<script>window.location.href="login.php?signup=success"</script>';
        exit();
      } else {
        echo '<script>alert("Email or Contact already exists!")</script>';
      }
    } else {
      echo "error";
    }
  }

  ?>

</body>

</html>
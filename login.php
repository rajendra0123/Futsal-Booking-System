<!DOCTYPE html>
<html>
<?php
include 'conn.php';
// session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  $loggedin = true;
} else {
  $loggedin = false;
}
?>

<head>

  <style>
    body {

      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
    }

    .success-message {
      /* background-color: pink; */
      height: 40px;
    }

    .success-message h4 {
      color: Black;
      margin-left: 100px;


    }

    .right a {

      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
      margin-left: 1220px;
      font-size: larger;
      text-decoration: none;
      color: #000;

    }

    .container {
      width: 80%;
      margin: 0 auto;
      /* padding: 50px 0; */
      display: flex;
      justify-content: center;
      align-items: stretch;
    }

    .login-container {
      flex: 1;
      margin-right: 10px;
    }

    .image-container {
      height: auto;
      flex: 1;
      /* position: relative; */
      margin-left: 10px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .image-container img {
      border-radius: 10px;
    }

    h1 {
      text-align: center;
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }

    .login {
      background-color: #fff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-top: 30px;
      text-align: center;
    }

    form {
      display: inline-block;
      text-align: left;
    }

    label {
      display: block;
      margin-bottom: 5px;
      color: black;
    }

    input[type="text"],
    input[type="password"],
    select {
      display: block;
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: none;
      border-radius: 3px;
      font-size: 16 px;
      /* color: #333; */
    }

    input[type="submit"] {
      background-color: #0077c0;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 3px;
      font-size: 20px;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #005e9d;
    }

    a {
      color: #0077c0;

    }

    img {
      max-width: 100%;

    }

    .radio-div {
      display: flex;
    }

    .login-page {
      text-align: center;
    }

    .error-login {
      color: red;
      text-align: center;
    }
  </style>
</head>

<body>


  <h1>Welcome</h1>
  <div class="right">
    <?php
    if (!$loggedin) {
      echo '
    <a href="homepage.php">HOME</a>
  </div>';
    }
    ?>
    <?php
    if (isset($_GET['signup']) && $_GET['signup'] === "success") {
      echo " <div class= 'success-message'><h4>Now you can login.</h4> </div>";
    }
    ?>
    <script>
      setTimeout(function () {
        var successMessage = document.querySelector('.success-message');
        if (successMessage) {
          successMessage.style.display = 'none';
        }
      }, 3000); 
    </script>
  </div>

  <div class="container">
    <div class="login-container">

      <div class="login">
        <h2>Login</h2>

        <form name="myForm" method="post">

          <label for="em">Email:
            <input type="text" id="em" name="email" autocomplete="email">
          </label>

          <label for="pass">Password:
            <input type="password" id="pass" name="password" autocomplete="current-password">
          </label>
          <div class="form-group">
            <label for="">Log in as:</label>
            <select name="role" id="role" class="" style="width:100%; padding:10px; font-size:16px;border-radius:5px">
              <option value="player">Player</option>
              <option value="owner">Owner</option>
            </select>
          </div>
          <input type="submit" value="login" name="login">

        </form>
        <p>Don't have an account? <a href="signup.php">Sign up</a> </p>
      </div>
    </div>

    <div class="image-container">
      <img src="login.jfif" alt="Futsal Image">
    </div>
  </div>


  <!-- login php -->
  <?php
  if (isset($_POST['login'])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST['role'];


    // Determine the role
    $role = $_POST['role'];

    // Set a unique session name based on the role
    if ($role === 'player') {
      session_name("player_session");
    } elseif ($role === 'owner') {
      session_name("owner_session");
    }
    session_start();

    if ($role == 'player') {
      // Perform database query for player table
      $playerQuery = "SELECT * FROM player WHERE email = '$email'";
      $playerResult = mysqli_query($con, $playerQuery);
      if (mysqli_num_rows($playerResult) > 0) {
        $row = mysqli_fetch_assoc($playerResult);
        if (password_verify($password, $row['password'])) {
          $_SESSION['loggedin'] = true;
          $_SESSION['fullname'] = $row['fullname'];
          $_SESSION['player_id'] = $row['player_id'];
          $_SESSION['email'] = $email;
          header("Location: playerhomepage.php");
          exit();
        } else {
          echo "<div class='error-login'><h4>Incorrect password or email</h4></div>";
        }
      } else {
        echo "<div class='error-login'><h4>Player doesn't exist</h4></div>";
      }
    } else if ($role == 'owner') {
      // Perform database query for owner table
      $ownerQuery = "SELECT * FROM owner WHERE email = '$email'";
      $ownerResult = mysqli_query($con, $ownerQuery);

      if (mysqli_num_rows($ownerResult) > 0) {
        $row = mysqli_fetch_assoc($ownerResult);
        if (password_verify($password, $row['password'])) {
          $_SESSION['loggedin'] = true;
          $_SESSION['fullname'] = $row['fullname'];
          $_SESSION['owner_id'] = $row['owner_id'];
          $_SESSION['email'] = $email;
          header("Location: futsalregister.php");
          exit();
        } else {
          echo "<div class='error-login'><h4>Incorrect password or email</h4></div>";
        }
      } else {
        echo "<div class='error-login'><h4>Owner doesn't exist</h4></div>";
      }
    } else {
      echo "";
    }
  }
  ?>

</body>

</html>
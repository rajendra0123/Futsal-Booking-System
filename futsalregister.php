<!DOCTYPE html>
<html>
<script src="ckeditor/ck/ckeditor.js"></script>
<?php
session_name("owner_session");
session_start();
include 'conn.php';
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  $loggedin = true;
} else {
  $loggedin = false;
}
?>
<?php
if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
  $email = $_SESSION['email'];
  $query = "SELECT * FROM `owner` WHERE email='$email'";
  $result = mysqli_query($con, $query);
  if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: login.php");
    exit();
  }
  $row = mysqli_fetch_assoc($result);
  $fullname = $row['fullname'];
}
?>


<head>
  <title>Your Webpage Title</title>
  <style>
    .title {
      margin-right: 50px;
      margin-left: 30px;
      width: 50%;
      color: rgb(7, 7, 7);
      font-size: larger;
    }

    header {
      background-color: #f6e2e2;
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .welcome {
      display: flex;
      align-items: center;
      background-color: grey;
      height: 50px;
      border-radius: 10px;
      padding: 8px;
    }

    .welcome p {
      margin-right: 10px;
      font-size: larger;
      font-weight: bold;
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
      color: white;
    }

    .user-image {
      height: 60px;
      margin-right: 10px;
    }

    .header-links {
      font-size: larger;
      margin-left: 200px;
      display: flex;
    }

    .header-links a {
      text-decoration: none;
      color: #000;
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }

    .dropdown {

      /* align-items: center; */
      /* margin-right: 5px; */
    }

    .dropdown a {
      display: flex;
      /* align-items: center; */
      text-decoration: none;
      color: black;

    }

    .navigation {
      display: flex;
      align-items: center;
    }

    .navigation a {
      text-decoration: none;
      margin-right: 10px;
      color: black;
      font-size: larger;
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }

    .details {
      margin-right: 20px;
    }

    .details a {
      text-decoration: none;
      margin-right: 10px;
      color: black;
      font-size: larger;
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }


    .form-section {
      max-width: 600px;
      margin: 20px auto;
      padding: 20px;
      background-color: #f9f9f9;
      border-radius: 5px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

    }

    .form-section h2 {
      font-size: 24px;
      margin-bottom: 20px;
    }

    .form-section input,
    .form-section textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .form-section .btn-container button {
      padding: 8px 16px;
      font-size: 14px;
      background-color: #333;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      margin-left: 10px;

    }

    .form-section .btn-container [type="submit"] {

      /* justify-content: space-between; */
      padding: 8px 16px;
      font-size: 14px;
      background-color: #333;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      width: 100px;

    }

    .navigation {
      display: flex;
      align-items: center;
    }

    .navigation a {
      text-decoration: none;
      margin-right: 10px;
      color: black;
      font-size: larger;
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
      margin-left: 100px;
    }

    .details {
      margin-right: 20px;
    }

    .details a {
      text-decoration: none;
      margin-right: 10px;
      color: black;
      font-size: larger;
      font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    }

    .error {
      color: red;
    }
  </style>
</head>

<body>
  <header>
    <div class="title">
      <h1>FUTSOL
      </h1>
    </div>

    <div class="navigation">
      <!-- <a href="groundlist.php">BOOKNOW</a>&nbsp;&nbsp;&nbsp; -->
      <?php
      $sql = "SELECT * FROM ground";
      $result = mysqli_query($con, $sql);
      while ($row = mysqli_fetch_assoc($result)) {
        $ground_id = $row['ground_id'];
      }
      echo '<a href="myground.php?ground_id=' . $ground_id . '">MYGROUND</a>&nbsp;&nbsp;&nbsp;';
      ?>
    </div>

    <?php

    if ($loggedin) {
      $owner_id = $_SESSION['owner_id'];
      $sql = "SELECT * FROM owner WHERE  owner_id = $owner_id";
      $result = mysqli_query($con, $sql);
      if ($result) {
        if ($row = mysqli_fetch_assoc($result)) {
          $owner_id = $row['owner_id'];
          echo '<div class="details">
      <a href="ownerdetails.php?owner_id=' . $owner_id . '">DETAILS</a>
    </div>';
        }
      }

      echo '
    <div class="dropdown">
      <img src="loginimage.png" alt="owner Image" class="owner-image" height="55px">

      <a href="logout.php">Logout</a>
    </div>
    <div class="welcome">';
      if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $_SESSION['email'] = $email;
        $fullname = $_SESSION['fullname'];

        echo "<p>$fullname</p>";
      } else {
        header("Location: login.php");
        exit;
      }
      echo '
    </div>';
    }
    ?>
  </header>

  <section class="form-section">
    <h2>Add Your Futsal's Details</h2>
    <form id="futsal-form" method="POST" enctype="multipart/form-data" action="">


      <label>Futsal Logo:</label>
      <input type="file" id="logo" name="futsal_logo">

      <label>Ground Image:</label>
      <input type="file" id="image" name="ground_image" required>

      <div class='error' id="nameError"></div>
      <label>Futsal Name:</label>
      <input type="text" id="name" name="ground_name">

      <div class='error' id="locationError"></div>
      <label>Location:</label>
      <input type="text" id="location" name="ground_location">

      <!-- <div class='error' id="locationError"></div> -->
      <label>Map URL:</label>
      <input type="text" id="map" name="map">

      <div class='error' id="numberError"></div>
      <label>Contact Number:</label>
      <input type="text" id="contact" name="contact">

      <div class='error' id="amountError"></div>
      <label>Amount per hour:</label>
      <input type="number" id="amount" name="amount">

      <label>QR for Payment:</label>
      <input type="file" id="qr" name="qr_code" required>

      <div class='error' id="descriptionError"></div>
      <label>Description:</label>
      <textarea id="description" name="ground_description" rows="4"></textarea>
      <div class="btn-container">
        <button type="button" onclick="cancelForm()">Cancel</button>
        <input type="submit" id="btnSubmit" value="Register" name="register">
      </div>
    </form>
    <script>
      CKEDITOR.replace('description');
    </script>
  </section>

  <script>
    btnSubmit = document.getElementById('btnSubmit');
    btnSubmit.addEventListener('click', (event) => {

      const name = document.getElementById("name").value;
      const location = document.getElementById("location").value;
      const contact = document.getElementById("contact").value;
      const description = document.getElementById("description").value;
      const amount = document.getElementById("amount").value;

      // Initialize CKEditor instance
      const editor = CKEDITOR.instances['description'];
      const description = editor.getData();

      // Futsal Name validation
      const nameError = document.getElementById("nameError");
      if (name === "") {
        event.preventDefault();
        nameError.textContent = "*required";
      } else if (name.length < 5) {
        event.preventDefault();
        nameError.textContent = "Futsal Name must contain more than 4 characters";
      } else if (/\d/.test(name)) {
        event.preventDefault();
        nameError.textContent = "Futsal Name cannot contain numbers";
      } else {
        nameError.textContent = "";
      }

      // Location validation
      const locationError = document.getElementById("locationError");
      if (location === "") {
        event.preventDefault();
        locationError.textContent = "*required";
      } else {
        locationError.textContent = "";
      }

      //Contact vlidation
      const numberError = document.getElementById("numberError");
      var regexPattern = (/^\d+$/);
      if (contact === "") {
        event.preventDefault();
        numberError.textContent = "*required";
      } else if (!regexPattern.test(contact)) {
        event.preventDefault();
        numberError.textContent = "Contact Number must contain only digits";
      }
      else if (contact.length != 10) {
        event.preventDefault();
        numberError.textContent = "Contact Number must be 10 digits";
      }
      else {
        numberError.textContent = "";
      }

      //Amount vlidation
      const amountError = document.getElementById("amountError");

      if (amount === "") {
        event.preventDefault();
        amountError.textContent = "*required";
      }
      else {
        amountError.textContent = "";
      }

      // Description validation
      const descriptionError = document.getElementById("descriptionError");
      if (description.trim() === "") {
        event.preventDefault();
        descriptionError.textContent = "*required";
      } else {
        descriptionError.textContent = "";
      }
    });
  </script>


  <?php
  if (isset($_POST['register'])) {
    $futsal_logo = $_FILES['futsal_logo'];
    $ground_name = $_POST['ground_name'];
    $ground_location = $_POST['ground_location'];
    $contact = $_POST['contact'];
    $qr_code = $_FILES['qr_code'];
    $ground_description = $_POST['ground_description'];
    $amount = $_POST['amount'];
    $map = $_POST['map'];
    //file extension validity
    $isValid = true;

    //for image
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];

    $ground_image_name = $_FILES['ground_image']['name'];
    $ground_image_tmp = $_FILES['ground_image']['tmp_name'];
    $ground_image_folder = "registerimage/" . $ground_image_name;

    $fileExtension = strtolower(pathinfo($ground_image_name, PATHINFO_EXTENSION));


    if (!in_array($fileExtension, $allowedExtensions)) {
      echo '<script>alert("Only JPG, JPEG, PNG,GIF and Jfif files are allowed")</script>';
      $isValid = false;
    } else {
      move_uploaded_file($ground_image_tmp, $ground_image_folder);

    }


    //for qr
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];

    $qr_code_name = $_FILES['qr_code']['name'];
    $qr_code_tmp = $_FILES['qr_code']['tmp_name'];
    $qr_code_folder = "registerimage/" . $qr_code_name;
    move_uploaded_file($qr_code_tmp, $qr_code_folder);

    $fileExtension = strtolower(pathinfo($qr_code_name, PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
      echo '<script>alert("Only JPG, JPEG, PNG,GIF and Jfif files are allowed")</script>';
      $isValid = false;
    } else {

      move_uploaded_file($qr_code_tmp, $qr_code_folder);

    }

    //for logo
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];

    $futsal_logo_name = $_FILES['futsal_logo']['name'];
    $futsal_logo_tmp = $_FILES['futsal_logo']['tmp_name'];
    $futsal_logo_folder = "registerimage/" . $futsal_logo_name;
    move_uploaded_file($futsal_logo_tmp, $futsal_logo_folder);

    $fileExtension = strtolower(pathinfo($futsal_logo_name, PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
      echo '<script>alert("Only JPG, JPEG, PNG,GIF and Jfif files are allowed")</script>';
      $isValid = false;
    } else {

      move_uploaded_file($futsal_logo_tmp, $futsal_logo_folder);

    }

    // Check if the owner has already added a ground
    $sql = "SELECT * FROM ground WHERE owner_id = $owner_id";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
      echo '<script>alert("You have already added a ground");';
      echo 'window.location.href = "futsalregister.php";</script>';
      exit();
    }

    if ($isValid) {
      $sql = "INSERT INTO ground (futsal_logo,ground_name, ground_location, contact,amount, ground_description, ground_image, qr_code,owner_id,status)
         VALUES (' $futsal_logo_folder','$ground_name', '$ground_location', '$contact','$amount', '$ground_description', '$ground_image_folder', '$qr_code_folder', '$owner_id','pending')";

      if (mysqli_query($con, $sql)) {

        echo '<script>alert("Futsal to be added sent")</script>';
        echo '<script>window.location.href = "futsalregister.php"</script>';
        $ground_id = mysqli_insert_id($con);

        $_SESSION['ground_id'] = $ground_id;
        exit();
      } else {
        echo "Error: " . mysqli_error($con);
      }
    }
  }
  ?>

  <script>
    function cancelForm() {
      // Reset the form fields
      document.getElementById("futsal-form").reset();

    }


  </script>
</body>

</html>
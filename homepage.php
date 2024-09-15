<!DOCTYPE html>
<html>

<?php include 'conn.php';
//session_name("player_session");
session_start();
//signup php
$signupResult = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Check if email or contact already exists
    $checkQuery = "SELECT * FROM player WHERE email='$email' OR contact='$contact' 
                   UNION 
                   SELECT * FROM owner WHERE email='$email' OR contact='$contact'";
    $result = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        $signupResult = 'exists';
    } else {
        if ($role == 'player') {
            $sql = "INSERT INTO player (fullname, email, contact, password) VALUES ('$fullname', '$email', '$contact', '$hash')";
        } else if ($role == 'owner') {
            $sql = "INSERT INTO owner (fullname, email, contact, password) VALUES ('$fullname', '$email', '$contact', '$hash')";
        }

        if ($con->query($sql) === TRUE) {
            $signupResult = 'success';
        } else {
            $signupResult = 'error';
        }
    }
}



// if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
//     header("Location: homepage.php");
//     $loggedin = true;
// } else {
//     $loggedin = false;
// }

?>

<!-- login php -->
<?php

$loginResult = '';

if (isset($_POST['login'])) {
    $email = $_POST["loginemail"];
    $password = $_POST["loginpassword"];
    $role = $_POST['user_type'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    //$role = $_POST['role'];

    // Set a unique session name based on the role
    if ($role === 'player') {
        session_name("player_session");
    } elseif ($role === 'owner') {
        session_name("owner_session");
    }
    //session_start();

    // Determine the role
    if ($role == 'player') {
        // Perform database query for player table
        $playerQuery = "SELECT * FROM player WHERE email = '$email'";
        $playerResult = mysqli_query($con, $playerQuery);

        if (mysqli_num_rows($playerResult) > 0) {
            $row = mysqli_fetch_assoc($playerResult);
            if (password_verify($password, $row['password'])) {

                $updateQuery = "UPDATE player SET latitude='$latitude', longitude='$longitude' WHERE player_id='{$row['player_id']}'";
                mysqli_query($con, $updateQuery);

                $_SESSION['loggedin'] = true;
                $_SESSION['fullname'] = $row['fullname'];
                $_SESSION['player_id'] = $row['player_id'];
                $_SESSION['email'] = $email;
                header("Location: playerhomepage.php");
                exit();
            } else {
                $loginResult = 'incorrect';
            }
        } else {
            $loginResult = 'no_player';
        }
    } else if ($role == 'owner') {
        // Perform database query for owner table
        $ownerQuery = "SELECT * FROM owner WHERE email = '$email'";
        $ownerResult = mysqli_query($con, $ownerQuery);

        if (mysqli_num_rows($ownerResult) > 0) {
            $row = mysqli_fetch_assoc($ownerResult);
            if (password_verify($password, $row['password'])) {


                $updateQuery = "UPDATE owner SET latitude='$latitude', longitude='$longitude' WHERE owner_id='{$row['owner_id']}'";
                mysqli_query($con, $updateQuery);
                //   session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['fullname'] = $row['fullname'];
                $_SESSION['owner_id'] = $row['owner_id'];
                $_SESSION['email'] = $email;

                header("Location: futsalregister.php");
                exit();
            } else {
                $loginResult = 'incorrect';
            }
        } else {
            $loginResult = 'no_owner';
        }
    }
}
?>

<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #282c34;
            padding: 35px;
            /* border-radius: 0 0 10px 10px; */
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .title h1 {
            color: #ffffff;
            font-size: 40px;
            margin: 0;
        }

        .title a {
            text-decoration: none;
            color: #ffffff;
        }

        .mid {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            margin-right: 415px;
        }

        .mid form {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-right: 10px;
        }

        .mid input[type="search"] {
            border: none;
            padding: 10px;
            width: 300px;
            outline: none;
        }

        .mid button {
            background-color: #ff6b6b;
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        .mid button img {
            height: 20px;
        }

        .navigation {
            display: flex;
            align-items: center;
        }

        .navigation a {
            text-decoration: none;
            color: #ffffff;
            margin: 0 15px;
            font-size: 20px;
            transition: color 0.3s;
        }

        .navigation button {
            background: none;
            border: none;
            color: #ffffff;
            margin: 0 15px;
            font-size: 20px;
            transition: color 0.3s;
            cursor: pointer;
            padding: 0;
        }

        .navigation button:hover {
            color: #ff6b6b;

        }


        .navigation a:hover {
            color: #ff6b6b;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
            padding: 30px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            z-index: 10;
            width: 350px;
        }

        .popup h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #282c34;
        }

        .popup input[type="text"],
        .popup input[type="email"],
        .popup input[type="password"],
        .popup select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .popup button[type="submit"] {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 30%;
            margin-top: 15px;
            margin-left: 120px;
        }

        .popup button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .popup .toggle-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
            cursor: pointer;
        }

        .popup .toggle-link:hover {
            text-decoration: underline;
        }

        .popup .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            font-size: 18px;
            color: #999;
        }

        .popup .close-btn:hover {
            color: #333;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9;
        }

        .add-futsal {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            margin-left: 15px;
            transition: background-color 0.3s;
        }

        .add-futsal:hover {
            background-color: #0056b3;
        }

        .hero-section {
            background-image: url('login.jfif');
            background-size: cover;
            background-position: center;
            height: 400px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 48px;
            margin: 0;
            z-index: 2;
            margin-right: 220px;
        }

        .hero-section .mid {
            position: absolute;
            bottom: 110px;
            z-index: 2;

        }

        .hero-section::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .futsal-section {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            padding: 30px;
            justify-content: center;
        }

        .futsal-box {
            width: 350px;
            background-color: #333;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .futsal-box:hover {
            transform: translateY(-10px);
        }

        .futsal-box img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 15px;
        }

        .futsal-box h3 {
            font-size: 22px;
            margin: 0 0 12px 0;
            color: white;
        }

        .futsal-box .btn-container {
            display: flex;
            justify-content: space-between;

        }

        .futsal-box button {
            padding: 12px 18px;
            font-size: 18px;
            background-color: blue;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .futsal-box button:hover {
            background-color: #ff6b6b;
        }


        .futsol {
            margin-left: 150px;
        }

        .info-section {
            background-color: #999;
            padding: 40px 20px;
            text-align: center;
            margin-bottom: -100px;
        }

        .info-section h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: black;
        }

        .info-section p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #666;
            max-width: 800px;
            margin: 0 auto 30px;

        }

        .footer {
            background-color: #282c34;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 97%;
            margin-top: 50px;
        }

        .error {
            color: red;
        }

        .popup-message {
            display: none;
            background-color: #ff6b6b;
            color: #ffffff;
            padding: 30px;
            font-size: 20px;
            border-radius: 5px;
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 1000;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 10px;
            z-index: 1000;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        .overlay {
            display: none;

        }

        .bottom-popup {
            display: none;
            background-color: #999;
            color: #ffffff;
            padding: 20px;
            font-size: 18px;
            border-radius: 5px;
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 1000;
        }

        .bottom-popup button {
            background-color: #282c34;
            color: #ff6b6b;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 5px;
        }
    </style>
</head>

<header>
    <div class="title">
        <h1>FUTSOL</h1>

    </div>

    <div id="bottomPopup" class="bottom-popup" style="display: none;">
        <p>Please Login First to Add</p>
        <button onclick="hideBottomPopup()">OK</button>
    </div>

    <div class="navigation">
        <a href="#" id="addFutsalBtn" class="add-futsal">ADD FUTSAL</a>
        <a href="homepage.php">HOME</a>
        <button id="login-btn" class="login-btn">LOGIN</button>
    </div>


    <script>
        const addFutsalBtn = document.getElementById('addFutsalBtn');
        const bottomPopup = document.getElementById('bottomPopup');

        function showBottomPopup() {
            bottomPopup.style.display = 'block';
        }

        function hideBottomPopup() {
            bottomPopup.style.display = 'none';
        }

        addFutsalBtn.addEventListener('click', (event) => {
            event.preventDefault();
            const isLoggedIn = false;
            if (!isLoggedIn) {
                showBottomPopup();
            }
        });
    </script>



</header>

<body>
    <div class="hero-section">
        <h1>Searching For A Futsal?</h1>
        <div class="mid">
            <form method="GET" action="search.php">
                <input type="search" name="search" placeholder="Search By Name or Location" size="37" />
                <button type="submit" class="search-button">
                    <img src="searchlogo.png" class="search-logo">
                </button>
            </form>
        </div>
    </div>

    <?php

    $sql = "SELECT * FROM ground LIMIT 5";
    $result = mysqli_query($con, $sql);
    echo '<section class="futsal-section">';
    while ($row = mysqli_fetch_assoc($result)) {
        $ground_name = $row['ground_name'];
        $ground_id = $row['ground_id'];
        $futsal_logo = $row['futsal_logo'];
        $status = $row['status'];
        if ($status === 'Verified') {
            echo '
        <div class="futsal-box">
            <img src="' . $futsal_logo . '" alt="Futsal logo">
            <h3>' . $ground_name . '</h3>
            <div class="btn-container">
                <button onclick="redirectLogin()">Book Now</button>
                <button onclick="viewDetails(' . $ground_id . ')">View Details</button>
            </div>
        </div>';
        }
    }
    echo '</section>';
    ?>
    <!-- View Details redirect -->

    <script>
        function viewDetails(ground_id) {
            window.location.href = "futsaldetail.php?ground_id=" + ground_id;
        }

    </script>
    <!-- Booking redirect -->
    <script>
        function redirectLogin() {
            alert("Please Login First");
            window.location.href = ("homepage.php");

        }
    </script>
    <div class="info-section">
        <h2>Welcome to FUTSOL </h2>
        <p>Futsol is a cutting-edge platform designed to provide users with easy access to nearby futsal venues,
            eliminating the need to wander around searching for the perfect match. Our platform connects futsal
            enthusiasts with a wide range of venues, ensuring that players find a location that meets their expectations
            in terms of quality, amenities, and convenience.</p>
    </div>

    <div class="footer">
        &copy; 2024 FUTSOL. All Rights Reserved.
    </div>




    <!-- For Login -->

    <div class="overlay"></div>
    <div class="popup login-popup">
        <span class="close-btn">&times;</span>
        <h2>Login</h2>
        <form action="" method="POST" onsubmit="getLocation()">
            <input type="email" name="loginemail" placeholder="Email" required>
            <input type="password" name="loginpassword" placeholder="Password" required>
            <select name="user_type" required>
                <option value="" disabled selected>Login as...</option>
                <option value="player">Player</option>
                <option value="owner">Owner</option>
            </select>
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
            <button type="submit" name="login">Login</button>
        </form>
        <a class="toggle-link" id="signup-link">Sign up for Registration</a>
    </div>

    <!-- Geo Locaiton API for Current latitude and longitude -->
    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    document.getElementById('latitude').value = latitude;
                    document.getElementById('longitude').value = longitude;
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            getLocation();
        });
    </script>




    <?php if ($loginResult == 'no_player'): ?>
        <div id="noPlayerPopup" class="popup-message" style="display:block">Player doesn't exist!</div>
    <?php elseif ($loginResult == 'no_owner'): ?>
        <div id="noOwnerPopup" class="popup-message" style="display:block">Owner doesn't exist!</div>
    <?php elseif ($loginResult == 'incorrect'): ?>
        <div id="incorrectPopup" class="popup-message" style="display:block">Incorrect email or password!</div>
    <?php endif; ?>

    <script>
        const noPlayerPopup = document.getElementById('noPlayerPopup');
        const noOwnerPopup = document.getElementById('noOwnerPopup');
        const incorrectPopup = document.getElementById('incorrectPopup');

        if (noPlayerPopup) {
            setTimeout(() => {
                noPlayerPopup.style.display = 'none';
            }, 3000);
        }

        if (noOwnerPopup) {
            setTimeout(() => {
                noOwnerPopup.style.display = 'none';
            }, 3000);
        }

        if (incorrectPopup) {
            setTimeout(() => {
                incorrectPopup.style.display = 'none';
            }, 3000);
        }
    </script>

    <!-- For signup -->

    <div class="popup signup-popup">
        <span class="close-btn">&times;</span>
        <h2>Sign Up</h2>
        <form action="" method="POST" id="signupForm">
            <input type="hidden" name="signup" value="true">
            <div class='error' id="fullnameError"></div>
            <input type="text" id="fullname" name="fullname" placeholder="Full Name">
            <div class='error' id="emailError"></div>
            <input type="text" id="email" name="email" placeholder="Email">
            <div class='error' id="numError"></div>
            <input type="text" id="contact" name="contact" placeholder="Contact">
            <div class='error' id="pwdError"></div>
            <input type="password" id="password" name="password" placeholder="Password">
            <div class='error' id="cpwdError"></div>
            <input type="password" id="confirmPassword" name="cpassword" placeholder="Confirm Password">
            <p>Sign up as:</p>
            <div style="display: flex; align-items: center;">
                <p><input type="radio" name="role" value="player" required>Player</p>
                <p><input type="radio" name="role" value="owner" style="margin-left: 45px;" required>Owner</p>
            </div>
            <button type="submit" id="btnSubmit">Sign Up</button>
            <a class="toggle-link" id="login-link">Login if Registered</a>
        </form>
    </div>

    <?php if ($signupResult == 'success'): ?>
        <div id="successPopup" class="popup-message" style="display:block">Account has been created!</div>
    <?php elseif ($signupResult == 'exists'): ?>
        <div id="errorPopup" class="popup-message" style="display:block">Email or Contact already exists!</div>
    <?php endif; ?>

    <script>
        const successPopup = document.getElementById('successPopup');
        const errorPopup = document.getElementById('errorPopup');
        const loginPopup = document.querySelector('.login-popup');
        const signupPopup = document.querySelector('.signup-popup');
        const overlay = document.querySelector('.overlay');
        const loginBtn = document.getElementById('login-btn');
        const loginLink = document.getElementById('login-link');
        const signupLink = document.getElementById('signup-link');
        const closeBtns = document.querySelectorAll('.close-btn');

        function showLoginPopup() {
            signupPopup.style.display = 'none';
            loginPopup.style.display = 'block';
            overlay.style.display = 'block';
        }

        function hidePopups() {
            loginPopup.style.display = 'none';
            signupPopup.style.display = 'none';
            overlay.style.display = 'none';
        }



        if (successPopup) {
            setTimeout(() => {
                successPopup.style.display = 'none';
                showLoginPopup(); // Show login popup after success message
            }, 3000);
        }

        if (errorPopup) {
            setTimeout(() => {
                errorPopup.style.display = 'none';
            }, 3000);
        }

        function showLoginPopup() {
            loginPopup.style.display = 'block';
            overlay.style.display = 'block';
        }

        function hidePopups() {
            loginPopup.style.display = 'none';
            signupPopup.style.display = 'none';
            overlay.style.display = 'none';
        }
        loginBtn.addEventListener('click', showLoginPopup);


        closeBtns.forEach(btn => {
            btn.addEventListener('click', hidePopups);
        });

        overlay.addEventListener('click', hidePopups);

        loginLink.addEventListener('click', () => {
            signupPopup.style.display = 'none';
            loginPopup.style.display = 'block';
            overlay.style.display = 'block';
        });

        signupLink.addEventListener('click', () => {
            loginPopup.style.display = 'none';
            signupPopup.style.display = 'block';
            overlay.style.display = 'block';
        });
    </script>

    <!-- Validation -->
    <script>
        const btnSubmit = document.getElementById('btnSubmit');
        btnSubmit.addEventListener('click', (event) => {
            const fullname = document.getElementById("fullname").value;
            const email = document.getElementById("email").value;
            const number = document.getElementById("contact").value;
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirmPassword").value;

            // Fullname validation
            const fullnameError = document.getElementById("fullnameError");
            if (fullname === "") {
                event.preventDefault();
                fullnameError.textContent = "*required";
            } else if (fullname.length < 4) {
                event.preventDefault();
                fullnameError.textContent = "Fullname must contain more than 5 characters";
            } else if (/\d/.test(fullname)) {
                event.preventDefault();
                fullnameError.textContent = "Fullname cannot contain numbers";
            } else {
                fullnameError.textContent = "";
            }

            // Email validation
            const emailError = document.getElementById("emailError");
            const emailPattern = /^[A-Za-z]+[0-9]*@gmail\.com$/i;
            if (email === "") {
                event.preventDefault();
                emailError.textContent = "*required";
            } else if (!emailPattern.test(email)) {
                event.preventDefault();
                emailError.textContent = "Email must be in proper Email format";
            } else if (email.length < 5) {
                event.preventDefault();
                emailError.textContent = "Email must be more than 5 characters";
            } else {
                emailError.textContent = "";
            }

            // Number validation
            const numError = document.getElementById("numError");
            const regexPattern = /^\d+$/;
            if (number === "") {
                event.preventDefault();
                numError.textContent = "*required";
            } else if (!regexPattern.test(number)) {
                event.preventDefault();
                numError.textContent = "Contact number must contain only digits";
            } else if (number.length != 10) {
                event.preventDefault();
                numError.textContent = "Contact number must be 10 digits";
            } else {
                numError.textContent = "";
            }

            // Password validation
            const pwdError = document.getElementById("pwdError");
            const pwdPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d!@#$%^&*()\-_=+{}[\]|;:'",.<>/?\\]+$/;
            if (password === "") {
                event.preventDefault();
                pwdError.textContent = "*required";
            } else if (password.length < 8) {
                event.preventDefault();
                pwdError.textContent = "Password must be at least 8 characters";
            } else if (!pwdPattern.test(password)) {
                event.preventDefault();
                pwdError.textContent = "Password must contain at least one lowercase letter, one uppercase letter, and one digit";
            } else {
                pwdError.textContent = "";
            }

            // Confirm password validation
            const cpwdError = document.getElementById("cpwdError");
            if (password !== confirmPassword) {
                event.preventDefault();
                cpwdError.textContent = "Passwords do not match";
            } else {
                cpwdError.textContent = "";
            }
        });
    </script>

</html>
<html>
<?php
//session_name("player_session");
session_start();
include 'conn.php';

if (!isset($_SESSION['player_id']) || $_SESSION['loggedin'] !== true) {
    header('Location: homepage.php');
    exit;
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {

    $loggedin = true;
} else {
    $loggedin = false;
}
?>

<head>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .signup-form {
            background-color: #fff;
            padding: 20px;
            margin: 30px auto;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .signup-form h2 {
            margin-top: 0;
            color: #333;
            font-size: 24px;
        }

        .signup-form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #666;
        }

        .signup-form input[type="text"],
        .signup-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .error {
            color: #e74c3c;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .btn-container {
            text-align: center;
        }

        .signup-form button {
            background-color: blue;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .signup-form button:hover {
            background-color: #ff6b6b;
        }
    </style>
</head>
<header>

</header>

<body>
    <?php include 'nav.php'; ?>
    <?php
    // fetch the data
    $player_id = $_GET['player_id'];
    $sql = "SELECT *from `player` where player_id=$player_id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $fullname = $row['fullname'];
    $email = $row['email'];
    $contact = $row['contact'];
    //  $password = $row['password'];
    
    ?>

    <div class="signup-form">
        <h2>Edit Your Details</h2>
        <form name="myForm" method="POST">
            <div class='error' id="fullnameError"></div>FullName:
            <input type="text" id="fullname" name="fullname" value="<?php echo $fullname; ?>">
            <div class='error' id="emailError"></div>Email:
            <input type="text" id="email" name="email" value="<?php echo $email; ?>">
            <div class='error' id="numError"></div>Contact:
            <input type="text" id="number" name="contact" value="<?php echo $contact; ?>">
            <div class='error' id="pwdError"></div>Password:
            <input type="text" id="password" name="password">
            <div class="btn-container">
                <button id="btnSubmit" name="update" type="submit">Update</button>

            </div>
        </form>
    </div>
    </div>

    <script>
        btnSubmit = document.getElementById('btnSubmit');
        btnSubmit.addEventListener('click', (event) => {

            const fullname = document.getElementById("fullname").value;
            const email = document.getElementById("email").value;
            const number = document.getElementById("number").value;
            const password = document.getElementById("password").value;

            //fullname validation
            const fullnameError = document.getElementById("fullnameError");
            if (fullname === "") {
                event.preventDefault();
                fullnameError.textContent = "*required";
            }
            else if (fullname.length < 4) {
                event.preventDefault();
                fullnameError.textContent = "Fullname must contain more than 4 characters";
            }
            else if (/\d/.test(fullname)) {
                event.preventDefault();
                fullnameError.textContent = "Fullname cannot contain numbers";
            }
            else {
                fullnameError.textContent = "";
            }

            // Email validation
            const emailError = document.getElementById("emailError");
            const emailPattern = /^[A-Za-z]+[0-9]*@gmail\.com$/i;

            if (email === "") {
                event.preventDefault();
                emailError.textContent = "*required";
            } else if (email.length < 5) {
                event.preventDefault();
                emailError.textContent = "Email must be more than 5 characters";
            } else if (!emailPattern.test(email)) {
                event.preventDefault();
                emailError.textContent = "Email must be in proper Email format";
            } else {
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

            // password validation
            const pwdError = document.getElementById("pwdError");
            const passwordField = document.getElementById("password").value;
            // const password = passwordField.value;
            var regexPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d!@#$%^&*()\-_=+{}[\]|;:'",.<>/?\\]+$/;

            if (password !== "") {
                if (password.length < 8) {
                    event.preventDefault();
                    pwdError.textContent = "Password must be at least 8 characters";
                } else if (!regexPattern.test(password)) {
                    event.preventDefault();
                    pwdError.textContent =
                        "Password must contain at least one lowercase letter, one uppercase letter, and one digit";
                } else {
                    pwdError.textContent = "";
                }
            }

        });
    </script>

    <?php
    if (isset($_POST['update'])) {
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $password = $_POST['password'];

        // Check for duplication with other players
        $checkQuery = "SELECT * FROM player WHERE (email='$email' OR contact='$contact') AND player_id <> '$player_id'";
        $checkResult = mysqli_query($con, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            echo '<script>alert("Email or Contact already exists!")</script>';
        } else {
            // Update the player's details
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE player SET fullname='$fullname', email='$email', contact='$contact', password='$hash' WHERE player_id='$player_id'";
            } else {
                $sql = "UPDATE player SET fullname='$fullname', email='$email', contact='$contact' WHERE player_id='$player_id'";
            }

            $result = mysqli_query($con, $sql);
            if ($result) {
                echo '<script>alert("Updated Successfully")</script>';
                echo '<script>window.location.href = "logout.php"</script>';
                exit;
            } else {
                echo '<script>alert("Update Failed: ' . mysqli_error($con) . '")</script>';
            }
        }
    }
    ?>
    <?php include 'footer.php'; ?>
</body>

</html>
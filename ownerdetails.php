<html>
<?php
include 'conn.php';
session_name("owner_session");
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $loggedin = true;
} else {
    $loggedin = false;
}
?>

<head>
    <style>
        header {
            background-color: #f6e2e2;
            padding: 20px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .title {
            margin-right: 50px;
            margin-left: 30px;
            width: 50%;
            color: rgb(7, 7, 7);
            font-size: larger;
        }

        .title a {
            text-decoration: none;
            color: #000;
        }

        .mid {
            margin-left: 300px;
            width: 100%;
            align-items: center;
            display: flex;
            position: relative;
            height: 50px;
        }

        .mid img {
            height: 20px;
            margin-left: 8.5px;
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
            margin-right: 30px;
            margin-bottom: 20px;
            margin-top: 20px;
            margin-left: 20px;
            font-size: larger;
            font-weight: bold;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            color: white;
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

        .dropdown a {
            text-decoration: none;
            color: black;
            display: flex;
        }

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
<header>
    <div class="title">

        <h1>FUTSOL</h1>

    </div>



    <div class="navigation">
        <a href="futsalregister.php">HOME</a>&nbsp;&nbsp;&nbsp;
        <?php
        $owner_id = $_SESSION['owner_id'];
        $sql = "SELECT * FROM owner WHERE owner_id = $owner_id";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $owner_id = $row['owner_id'];
        $fullname = $row['fullname'];

        if (!$loggedin) {
            echo '
        <a href="login.php">LOGIN</a>
    </div>';
        } else {
            echo '
            
        <div class="dropdown">
            <img src="loginimage.png" alt="owner Image" class="owner-image" height="55px">
        
        <a href="logout.php">Logout</a>
    </div>';

        }
        ?>


        <?php
        if ($loggedin) {
            echo '
    <div class="welcome">';
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                // $fullname = $_SESSION['fullname'];
                echo "<p>$fullname</p>";
            } else {
                header("Location: login.php");
                exit;
            }
            echo '
    </div>
    </div>';
        }
        ?>
</header>

<body>
    <?php
    // fetch the data
    $owner_id = $_GET['owner_id'];
    $sql = "SELECT *from `owner` where owner_id=$owner_id";
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

    <!--  Submit the form -->
    <?php
    if (isset($_POST['update'])) {
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $password = $_POST['password'];

        // Check for duplication with other owners
        $checkQuery = "SELECT * FROM owner WHERE (email='$email' OR contact='$contact') AND owner_id <> '$owner_id'";
        $checkResult = mysqli_query($con, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            echo '<script>alert("Email or Contact already exists!")</script>';
        } else {
            // Update the owner's details
            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE owner SET fullname='$fullname', email='$email', contact='$contact', password='$hash' WHERE owner_id='$owner_id'";
            } else {
                $sql = "UPDATE owner SET fullname='$fullname', email='$email', contact='$contact' WHERE owner_id='$owner_id'";
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
</body>

</html>
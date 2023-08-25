<?php
session_start();
include 'conn.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve the hashed password from the admin table
    $sql = "SELECT password FROM admin WHERE username = '$username'";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $hash = $row['password'];

        // Verify the entered password against the hashed password
        if (password_verify($password, $hash)) {
            $_SESSION['admin_loggedin'] = true;
            header("Location: adminpage.php");
            exit;
        } else {
            echo "<script>alert('Invalid username or password')</script>";
        }

    } else {
        echo "Query execution failed: " . mysqli_error($con);
    }

}
?>

<html>

<head>
    <h1 align="center">Admin Login!</h1>
    <style>
        h1 {
            margin-top: 80px;
        }

        form {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 8px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            opacity: 0.8;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>




    <!-- Create the login form for the admin -->
    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Login" name="login">
    </form>

    <!-- Display error message if login fails
        <?php if (isset($error)) { ?>
            <p>
                <?php echo $error; ?>
            </p>
        <?php } ?> 
        
    </body>

    </html>
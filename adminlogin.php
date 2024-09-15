<?php
session_start();
include 'conn.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = $_POST['password'];

    // Retrieve the hashed password from the admin table
    $sql = "SELECT id, password FROM admin_users WHERE username = '$username'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hash = $row['password'];
        $admin_id = $row['id'];

        // Verify the entered password against the hashed password
        if (password_verify($password, $hash)) {
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_loggedin'] = true;
            header("Location: adminpage.php");
            exit;
        } else {
            echo "<script>alert('Invalid username or password');</script>";
        }

    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }

    if (!$result) {
        echo "Query execution failed: " . mysqli_error($con);
    }
}
?>


<html>

<head>
    <h1 align="center">Admin Login!</h1>
    <style>
        /* body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        } */

        h1 {
            margin-top: 80px;
            color: #333;
            text-align: center;

        }

        form {
            width: 320px;
            margin: 20px auto;
            padding: 30px;
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
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


    <?php if (isset($error)) { ?>
        <p>
            <?php echo $error; ?>
        </p>
    <?php } ?>

</body>

</html>
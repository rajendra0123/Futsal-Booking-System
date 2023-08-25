<html>
<?php
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
        }
    </style>
</head>
<header>
    <div class="title">
        <a href="homepage.php">
            <h1>FUTSOL</h1>
        </a>
    </div>

    <div class="mid">
        <input type="search" name="search" placeholder="Search By Name or Location" size="37" />
        <a href="#">
            <img src="searchlogo.png" class="search-logo">
        </a>
    </div>


    <div class="navigation">
        <a href="homepage.php">HOME</a>&nbsp;&nbsp;&nbsp;
        <?php
        if (!$loggedin) {
            echo '
        <a href="groundlist.php">GROUNDS</a>&nbsp;&nbsp;&nbsp;
        <a href="login.php">LOGIN</a>
    </div>';
        } else {
            echo '
    <div class="navigation">
        <a href="groundlist.php">GROUNDS</a>&nbsp;&nbsp;&nbsp;
    </div>
    <div class="navigation">
    <a href="playerdetails.php">DETAILS</a>
    </div>
        <div class="dropdown">
            <img src="loginimage.png" alt="User Image" class="user-image" height="55px">
        
        <a href="logout.php">Logout</a>
    </div>';

        }
        ?>


        <?php
        if ($loggedin) {
            echo '
    <div class="welcome">';
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                $fullname = $_SESSION['fullname'];
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

</html>
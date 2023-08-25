<!DOCTYPE html>
<html>


<head>
    <html>
    <?php
    include 'conn.php';
    session_start();
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

            .left {
                margin-left: 600px;
            }

            .home {
                margin-top: 120px;
            }

            .home a {
                text-decoration: none;
                color: #000;
                font-size: large;
            }

            .container {
                display: flex;
                margin-left: 200px;
            }

            .content {
                width: 70%;
            }

            ul {
                list-style-type: none;
                padding: 0;
            }

            li {
                cursor: pointer;
                padding: 10px;
                background-color: #f1f1f1;
                margin-bottom: 5px;
            }

            table {
                width: 100%;
            }

            .content {
                margin-top: 80px;
                /* margin-left: 1px; */
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #f2f2f2;
            }

            form {
                display: inline-block;
                margin: 0;
                padding: 0;
            }

            input[type="submit"] {
                background-color: red;
                color: white;
                border: none;
                padding: 5px 10px;
                cursor: pointer;
            }

            input[type="submit"]:hover {
                background-color: black;
            }
        </style>
    </head>
    <header>
        <div class="title">

            <h1>FUTSOL</h1>
            </a>
            <div class="left">
                <h1 align="center">Welcome!</h1>
            </div>

        </div>
        <div class="home">
            <a href="adminpage.php">HOME</a>
        </div>
    </header>

<body>
    <?php
    // Check if the admin is logged in
    if (isset($_SESSION['admin_loggedin']) && $_SESSION['admin_loggedin'] == true) {
        ?>
        <div class="container">
            <div class="content">
                <!-- player table -->
                <h3 align="center">Player Details</h3>
                <table id="table-player" class="hidden">
                    <thead>
                        <tr>
                            <th>Player ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Contact</th>

                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch player data with role "owner" from the database and populate the table dynamically
                        $sql = "SELECT * FROM player";
                        $result = mysqli_query($con, $sql);
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $player_id = $row['player_id'];
                                $fullName = $row['fullname'];
                                $email = $row['email'];
                                $contact = $row['contact'];

                                echo '
              <form method="POST">  
              <tr>
              
              <td>' . $player_id . '</td>
               <td>' . $fullName . '</td>
              <td>' . $email . '</td>
               <td>' . $contact . '</td>
              
               <td> <input type="submit" value="delete" name="delete"/></td>
               </tr>
              </form>';
                            }
                        } else {
                            // Display an error message if the query execution fails
                            echo "Error: " . mysqli_error($con);
                        }

                        if (isset($_POST['delete'])) {
                            // Perform the deletion query
                            $deleteQuery = "DELETE FROM `player` WHERE player_id='$player_id'";
                            $deleteResult = mysqli_query($con, $deleteQuery);

                            if ($deleteResult) {
                                echo '<script>alert("player deleted successfully")</script>';
                                echo '<script>window.location.href = "adminplayerdetails.php"</script>';
                                exit;
                            } else {
                                echo '<script>alert("Failed to delete ground: ' . mysqli_error($con) . '")</script>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
    ?>
</body>

</html>
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

            .home {
                margin-top: 120px;
            }

            .home a {
                text-decoration: none;
                color: #000;
                font-size: large;
            }

            .left {
                margin-left: 600px;
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

            /* .hidden {
                display: none;
            } */

            .content {
                margin-top: 80px;
                margin-left: 50px;
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
                <h3 align="center">Futsal Details</h3>
                <table id="table-futsal" class="hidden">
                    <thead>
                        <tr>
                            <th>Ground ID</th>
                            <th>Owner Name</th>
                            <th>Futsal Name</th>
                            <th>Location</th>
                            <th>Contact</th>
                            <th>Futsal logo</th>
                            <th>Ground Image</th>
                            <th>Verify</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $sql = "SELECT ground.*, owner.* FROM ground INNER JOIN owner ON 
                        ground.owner_id = owner.owner_id";
                        $result = mysqli_query($con, $sql);
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $owner_id = $row['owner_id'];
                                $fullname = $row['fullname'];
                                $ground_id = $row['ground_id'];
                                $ground_name = $row['ground_name'];
                                $ground_location = $row['ground_location'];
                                $contact = $row['contact'];
                                $ground_image = $row['ground_image'];
                                $futsal_logo = $row['futsal_logo'];
                                $status = $row['status'];

                                echo '
              <form method="POST">  
              <tr>
             
              <td>' . $ground_id . '</td>
              <td>' . $fullname . '</td>
               <td>' . $ground_name . '</td>
              <td>' . $ground_location . '</td>
               <td>' . $contact . '</td>
               <td><img src="' . $futsal_logo . '" height="100px" width="120px"></td>
               <td><img src="' . $ground_image . '" height="100px" width="120px" ></td>
<td>';
                                if ($status === 'Verified') {
                                    echo 'Verified';
                                } else {
                                    echo '
    <form method="POST">
        <input type="hidden" name="ground_id" value="' . $row['ground_id'] . '">';

                                    if ($status === 'pending') {
                                        echo '
        <input type="submit" value="Verify" name="verify">';
                                    } else {
                                        echo '
        <input type="submit" value="Verify" name="verify" disabled>';
                                    }

                                    echo '
    </form>';
                                }

                                echo '
</td>
<td>
    <form method="POST">
        <input type="hidden" name="ground_id" value="' . $row['ground_id'] . '">
        <input type="submit" value="Delete" name="delete">
    </form>
</td>
</tr>';
                            }
                        } else {
                            // Display an error message if the query execution fails
                            echo "Error: " . mysqli_error($con);
                        }
    } else {

        exit;
    }
    ?>
                    <?php
                    if (isset($_POST['verify'])) {
                        $ground_id = $_POST['ground_id'];

                        // Perform the verification logic here
                    
                        // Update the ground status to "Verified" in the database
                        $updateQuery = "UPDATE ground SET status = 'Verified' WHERE ground_id = $ground_id";
                        $updateResult = mysqli_query($con, $updateQuery);

                        if ($updateResult) {
                            // Show the confirmation message
                            echo '<script>alert("Futsal verified successfully");</script>';
                            echo '<script>window.location.href = "adminfutsaldetails.php"</script>';
                        } else {
                            // Show the error message if the update fails
                            echo '<script>alert("Failed to verify booking: ' . mysqli_error($con) . '");</script>';
                        }
                    }

                    if (isset($_POST['delete'])) {
                        $ground_id = $_POST['ground_id'];

                        // Delete the ground from the database
                        $deleteQuery = "DELETE FROM ground WHERE ground_id = $ground_id";
                        $deleteResult = mysqli_query($con, $deleteQuery);

                        if ($deleteResult) {
                            // Show the confirmation message
                            echo '<script>alert("Futsal deleted successfully");</script>';
                            echo '<script>window.location.href = "adminfutsaldetails.php"</script>';
                        } else {
                            // Show the error message if the deletion fails
                            echo '<script>alert("Failed to delete booking: ' . mysqli_error($con) . '");</script>';
                        }
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>

</body>

</html>
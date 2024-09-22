<!DOCTYPE html>
<html>

<head>
    <?php
    include 'conn.php';
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('Location: adminlogin.php');
        exit;
    }
    ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #343a40;
            color: #fff;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .title {
            display: flex;
            align-items: center;
        }

        .title h1 {
            margin: 0;
            font-size: 40px;
        }

        .title a {
            text-decoration: none;
            color: #fff;
            margin-left: 30px;
            font-weight: bold;
            font-size: 20px;
        }

        .right a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            padding: 10px 20px;
            background-color: #dc3545;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .right a:hover {
            background-color: #c82333;
        }

        .container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .content {
            width: 80%;
            max-width: 1200px;
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
        }

        tr:hover {
            background-color: #ddd;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .action-btns {
            display: flex;
            gap: 5px;
        }

        .action-btns form {
            margin: 0;
        }

        .action-btns input[type="submit"] {
            background-color: #f44336;
        }

        .action-btns input[type="submit"]:hover {
            background-color: #e53935;
        }

        .action-btns .verify-btn {
            background-color: #4CAF50;
        }

        .action-btns .verify-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <header>
        <div class="title">
            <h1>FUTSOL</h1>
            <a href="adminpage.php">HOME</a>
        </div>
    </header>
    <div class="container">
        <div class="content">
            <h3 align="center">Futsal Details</h3>
            <table>
                <thead>
                    <tr>
                        <th>Ground ID</th>
                        <th>Owner Name</th>
                        <th>Futsal Name</th>
                        <th>Location</th>
                        <th>Contact</th>
                        <th>Futsal Logo</th>
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

                            echo "<tr>
                                  <td>{$ground_id}</td>
                                  <td>{$fullname}</td>
                                  <td>{$ground_name}</td>
                                  <td>{$ground_location}</td>
                                  <td>{$contact}</td>
                                  <td><img src='{$futsal_logo}' height='100px' width='120px'></td>
                                  <td><img src='{$ground_image}' height='100px' width='120px'></td>";

                            // Verify button
                            if ($status === 'Verified') {
                                echo "<td>Verified</td>";
                            } else {
                                echo "<td>
                                        <form method='POST' style='display:inline;'>
                                            <input type='hidden' name='ground_id' value='{$ground_id}'>
                                            <input type='submit' name='verify' value='Verify'>
                                        </form>
                                      </td>";
                            }

                            // Delete button
                            echo "<td>
                                    <form method='POST' style='display:inline;'>
                                        <input type='hidden' name='ground_id' value='{$ground_id}'>
                                        <input type='submit' name='delete' value='Delete'>
                                    </form>
                                  </td>
                              </tr>";
                        }
                    } else {
                        echo "Error: " . mysqli_error($con);
                    }

                    // Verify action
                    if (isset($_POST['verify'])) {
                        $ground_id = mysqli_real_escape_string($con, $_POST['ground_id']);
                        $updateQuery = "UPDATE ground SET status = 'Verified' WHERE ground_id = '$ground_id'";
                        if (mysqli_query($con, $updateQuery)) {
                            echo '<script>alert("Futsal verified successfully."); window.location.href="adminfutsaldetails.php";</script>';
                        } else {
                            echo '<script>alert("Verification failed: ' . mysqli_error($con) . '");</script>';
                        }
                    }

                    // Delete action
                    if (isset($_POST['delete'])) {
                        $ground_id = mysqli_real_escape_string($con, $_POST['ground_id']);
                        $deleteQuery = "DELETE FROM ground WHERE ground_id = '$ground_id'";
                        if (mysqli_query($con, $deleteQuery)) {
                            echo '<script>alert("Futsal deleted successfully."); window.location.href="adminfutsaldetails.php";</script>';
                        } else {
                            echo '<script>alert("Deletion failed: ' . mysqli_error($con) . '");</script>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
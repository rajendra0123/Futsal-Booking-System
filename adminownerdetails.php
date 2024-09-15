<!DOCTYPE html>
<html>

<head>
    <html>
    <?php
    include 'conn.php';
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header('Location: adminlogin.php');
        exit;
    }
    ?>

    <head>
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
                margin-top: 20px;
            }

            .sidebar {
                width: 20%;
                background-color: #fff;
                padding: 20px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .sidebar a {
                display: block;
                text-decoration: none;
                color: #343a40;
                font-weight: bold;
                padding: 10px 0;
                border-bottom: 1px solid #e9ecef;
                transition: color 0.3s ease;
            }

            .sidebar a:hover {
                color: #007bff;
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
                /* margin-left: 50px; */
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
                <!-- Owner table -->
                <h3 align="center">Owner Details</h3>
                <table id="table-owner" class="hidden">
                    <thead>
                        <tr>
                            <th>Owner ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Contact</th>

                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch owner data with role "owner" from the database
                        $sql = "SELECT * FROM owner";
                        $result = mysqli_query($con, $sql);
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $owner_id = $row['owner_id'];
                                $fullName = $row['fullname'];
                                $email = $row['email'];
                                $contact = $row['contact'];

                                echo '
                            <form method="POST">  
                                <tr>
                                    <td>' . $owner_id . '</td>
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
                            $deleteQuery = "DELETE FROM `owner` WHERE owner_id='$owner_id'";
                            $deleteResult = mysqli_query($con, $deleteQuery);

                            if ($deleteResult) {
                                echo '<script>alert("owner deleted successfully")</script>';
                                echo '<script>window.location.href = "adminownerdetails.php"</script>';
                                exit;
                            } else {
                                echo '<script>alert("Failed to delete owner: ' . mysqli_error($con) . '")</script>';
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
    <script src="script.js"></script>
    <?php include 'adminfooter.php' ?>
</body>

</html>
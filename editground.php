<!DOCTYPE html>
<html>
<script src="ckeditor/ck/ckeditor.js"></script>
<?php
session_name("owner_session");
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $loggedin = true;
} else {
    $loggedin = false;
}
include 'conn.php';
?>

<head>
    <title>Your Webpage Title</title>
    <style>
        /* Add your CSS styles for the header and form section here */
        .title {
            margin-right: 50px;
            margin-left: 30px;
            width: "50%";
            color: rgb(7, 7, 7);
            font-size: larger;

        }

        header {
            background-color: #f6e2e2;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dropdown a {
            text-decoration: none;
            color: black;
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

        .header-links {
            font-size: larger;
            margin-left: 200px;
            display: flex;
        }

        .header-links a {
            text-decoration: none;
            color: #000;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            /* position: absolute; */
        }

        .dropdown {
            margin-left: 870px;
        }

        .dropdown a {
            display: flex;
            text-decoration: none;
            color: black;

        }

        .form-section {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

        }

        .form-section textarea {
            white-space: pre-line;
        }

        .form-section h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-section input,
        .form-section textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-section .btn-container button {
            padding: 8px 16px;
            font-size: 14px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;

        }

        .form-section .btn-container [type="submit"] {

            /* justify-content: space-between; */
            padding: 8px 16px;
            font-size: 14px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100px;
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

        .error {
            color: red;
        }
    </style>
</head>

<body>
    <header>
        <div class="title">
            <h1>FUTSOL
            </h1>
        </div>

        <?php
        $owner_id = $_SESSION['owner_id'];
        $sql = "SELECT * FROM owner WHERE owner_id = $owner_id";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $owner_id = $row['owner_id'];
        $fullname = $row['fullname'];
        if ($loggedin) {
            echo '
        <div class="dropdown">
            <img src="loginimage.png" alt="User Image" class="user-image" height="55px">
        
        <a href="logout.php">Logout</a>
        </div>
    <div class="welcome">';
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                // $fullname = $_SESSION['fullname'];
                echo "<p>$fullname</p>";
            } else {
                header("Location: login.php");
                exit;
            }
            echo '
    </div>';
        }
        ?>
    </header>
    <?php
    // fetch the data
    $ground_id = $_GET['ground_id'];
    $sql = "SELECT *from `ground` where ground_id=$ground_id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $ground_name = $row['ground_name'];
    $ground_location = $row['ground_location'];
    $ground_description = $row['ground_description'];
    $ground_image = $row['ground_image'];
    $contact = $row['contact'];
    $qr_code = $row['qr_code'];
    $amount = $row['amount'];
    $map = $row['map'];

    // $ground_id = $row['ground_id'];
    ?>
    <section class="form-section">
        <h2>EDIT Your Ground's Details</h2>
        <form id="futsal-form" method="POST" enctype="multipart/form-data" action="">
            <label>Futsal Logo:</label>
            <input type="file" id="image" name="futsal_logo">

            <label>Ground Image:</label>
            <input type="file" id="image" name="ground_image">

            <div class='error' id="nameError"></div>
            <label>Futsal Name:</label>
            <input type=" text" id="name" name="ground_name" value="<?php echo $ground_name; ?>" required>

            <div class='error' id="locationError"></div>
            <label>Location:</label>
            <input type="text" id="location" name="ground_location" value="<?php echo $ground_location; ?>" required>

            <label>Map URL:</label>
            <textarea id="map" name="map" rows="8" cols="40"> <?php echo $map; ?>
            </textarea>

            <div class='error' id=" numberError"></div>
            <label>Contact Number:</label>
            <input type="text" id="contact" name="contact" value="<?php echo $contact; ?>" required>

            <div class='error' id="amountError"></div>
            <label>Amount per hour:</label>
            <input type="number" id="amount" name="amount" value="<?php echo $amount; ?>" required>

            <label>QR for Payment:</label>
            <input type="file" id="qr" name="qr_code">

            <div class='error' id="descriptionError"></div>
            <label>Description:</label>
            <textarea id="description" name="ground_description" required><?php echo $ground_description; ?></textarea>
            <div class="btn-container">
                <input type="submit" id="btnSubmit" value="Update" name="edit">
            </div>
        </form>
        <script>
            CKEDITOR.replace('description', {
                enterMode: CKEDITOR.ENTER_P,
                shiftEnterMode: CKEDITOR.ENTER_BR
            });
        </script>
    </section>

    <script>
        btnSubmit = document.getElementById('btnSubmit');
        btnSubmit.addEventListener('click', (event) => {

            const name = document.getElementById("name").value;
            const location = document.getElementById("location").value;
            const contact = document.getElementById("contact").value;
            const description = document.getElementById("description").value;
            const amount = document.getElementById("amount").value;

            // Initialize CKEditor instance
            const editor = CKEDITOR.instances['description'];
            const description = editor.getData();

            // Futsal Name validation
            const nameError = document.getElementById("nameError");
            if (name === "") {
                event.preventDefault();
                nameError.textContent = "*required";
            } else if (name.length < 5) {
                event.preventDefault();
                nameError.textContent = "Futsal Name must contain more than 4 characters";
            } else if (/\d/.test(name)) {
                event.preventDefault();
                nameError.textContent = "Futsal Name cannot contain numbers";
            } else {
                nameError.textContent = "";
            }

            // Location validation
            const locationError = document.getElementById("locationError");
            if (location === "") {
                event.preventDefault();
                locationError.textContent = "*required";
            } else {
                locationError.textContent = "";
            }

            //Contact vlidation
            const numberError = document.getElementById("numberError");
            var regexPattern = (/^\d+$/);
            if (contact === "") {
                event.preventDefault();
                numberError.textContent = "*required";
            } else if (!regexPattern.test(contact)) {
                event.preventDefault();
                numberError.textContent = "Contact Number must contain only digits";
            }
            else if (contact.length != 10) {
                event.preventDefault();
                numberError.textContent = "Contact Number must be 10 digits";
            }
            else {
                numberError.textContent = "";
            }
            //Amount vlidation
            const amountError = document.getElementById("amountError");

            if (amount === "") {
                event.preventDefault();
                amountError.textContent = "*required";
            }
            else {
                amountError.textContent = "";
            }
            // Description validation
            const descriptionError = document.getElementById("descriptionError");
            if (description.trim() === "") {
                event.preventDefault();
                descriptionError.textContent = "*required";
            } else {
                descriptionError.textContent = "";
            }
        });
    </script>

    <?php
    if (isset($_POST['edit'])) {
        $futsal_logo = $_FILES['futsal_logo'];
        $ground_name = $_POST['ground_name'];
        $ground_location = $_POST['ground_location'];
        $contact = $_POST['contact'];
        $qr_code = $_FILES['qr_code'];
        $ground_description = $_POST['ground_description'];
        $amount = $_POST['amount'];
        $map = $_POST['map'];
        // Check for the image fields are empty
        $updateImage = false;
        //file extension validity
        $isValid = true;

        //for ground image
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];

        if (!empty($_FILES['ground_image']['name'])) {
            $ground_image_name = $_FILES['ground_image']['name'];
            $ground_image_tmp = $_FILES['ground_image']['tmp_name'];
            $ground_image_folder = "registerimage/" . $ground_image_name;
            move_uploaded_file($ground_image_tmp, $ground_image_folder);

            $fileExtension = strtolower(pathinfo($ground_image_name, PATHINFO_EXTENSION));


            if (!in_array($fileExtension, $allowedExtensions)) {
                echo '<script>alert("Only JPG, JPEG, PNG,GIF and Jfif files are allowed")</script>';
                $isValid = false;
            } else {
                $updateImage = true;

            }
        }


        //for qr
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];

        if (!empty($_FILES['qr_code']['name'])) {
            $qr_code_name = $_FILES['qr_code']['name'];
            $qr_code_tmp = $_FILES['qr_code']['tmp_name'];
            $qr_code_folder = "registerimage/" . $qr_code_name;
            move_uploaded_file($qr_code_tmp, $qr_code_folder);

            $fileExtension = strtolower(pathinfo($qr_code_name, PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                echo '<script>alert("Only JPG, JPEG, PNG,GIF and Jfif files are allowed")</script>';
                $isValid = false;
            } else {

                $updateImage = true;
            }

        }

        //for logo
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];

        if (!empty($_FILES['futsal_logo']['name'])) {
            $futsal_logo_name = $_FILES['futsal_logo']['name'];
            $futsal_logo_tmp = $_FILES['futsal_logo']['tmp_name'];
            $futsal_logo_folder = "registerimage/" . $futsal_logo_name;
            move_uploaded_file($futsal_logo_tmp, $futsal_logo_folder);

            $fileExtension = strtolower(pathinfo($futsal_logo_name, PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                echo '<script>alert("Only JPG, JPEG, PNG,GIF and Jfif files are allowed")</script>';
                $isValid = false;
            } else {

                $updateImage = true;
            }
        }

        if ($isValid) {

            // Check for duplication with other users
            $checkQuery = "SELECT * FROM ground WHERE contact='$contact' AND ground_id <> '$ground_id'";
            $checkResult = mysqli_query($con, $checkQuery);
            if ($checkResult) {
                if (mysqli_num_rows($checkResult) > 0) {
                    echo '<script>alert("Contact already exists!")</script>';
                } else {
                    // Update the ground's details
                    $sql = "UPDATE ground SET ground_name='$ground_name',map='$map', ground_location='$ground_location', contact='$contact', ground_description='$ground_description',amount='$amount'";

                    if (!empty($qr_code_name)) {
                        $sql .= ", qr_code='$qr_code_folder'";
                    }

                    if (!empty($ground_image_name)) {
                        $sql .= ", ground_image='$ground_image_folder'";
                    }

                    if (!empty($futsal_logo_name)) {
                        $sql .= ", futsal_logo='$futsal_logo_folder'";
                    }

                    $sql .= " WHERE ground_id='$ground_id'";

                    $result = mysqli_query($con, $sql);
                    if ($result) {
                        echo '<script>alert("Updated Successfully")</script>';
                        echo '<script>window.location.href = "myground.php?ground_id=' . $ground_id . '"</script>';
                        exit;
                    } else {
                        echo '<script>alert("Update Failed: ' . mysqli_error($con) . '")</script>';
                    }
                }

            } else {
                echo '<script>alert("Error executing query: ' . mysqli_error($con) . '")</script>';
            }
        }
    }

    ?>
</body>

</html>
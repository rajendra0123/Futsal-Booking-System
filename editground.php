<!DOCTYPE html>
<html>
<script src="ckeditor/ckeditor/ckeditor.js"></script>
<?php
//session_name("owner_session");
session_start();

if (isset($_SESSION['owner_id']) && $_SESSION['loggedin'] === true) {
} else {
    header("Location: homepage.php");
    exit();
}

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
        .form-section {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;

        }

        .form-section h2 {
            font-size: 26px;
            color: #444;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-section label {
            font-size: 16px;
            color: #444;
            margin-bottom: 8px;
            display: block;
        }

        .form-section input[type="text"],
        .form-section input[type="file"],
        .form-section input[type="number"],
        .form-section textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;


        }

        .form-section input[type="text"]:focus,
        .form-section input[type="file"]:focus,
        .form-section input[type="number"]:focus,
        .form-section textarea:focus {
            border-color: #333;
        }

        .form-section .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .form-section .btn-container button,
        .form-section .btn-container input[type="submit"] {
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            cursor: pointer;

        }

        .form-section .btn-container button {
            background-color: #333;
            color: #fff;
        }

        .form-section .btn-container input[type="submit"] {
            background-color: blue;
            color: #fff;
            width: 120px;
        }

        .form-section .btn-container input[type="submit"]:hover {
            background-color: #ff6b6b;
        }

        .form-section button[type="button"] {
            background-color: blue;
            padding: 8px;
            color: white;
            border-radius: 5px;
            margin-right: 10px;
            cursor: pointer;
        }

        .form-section button[type="button"]:hover {
            background-color: #ff6b6b;
        }

        .error {
            color: red;
            font-size: 18px;
            margin-bottom: 10px;
        }

        #mapPopup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            border-radius: 10px;
        }

        #mapPopup button {
            padding: 10px 20px;
            background-color: blue;
            margin-top: 10px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .notification {
            display: none;

            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;

            color: white;
            padding: 20px;
            border-radius: 5px;
            z-index: 1000;
            font-size: 16px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .notification.error {
            background-color: #f44336;

        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>

<body>
    <?php include 'nav.php' ?>
    <header>

    </header>
    <?php
    // fetch the data
    $ground_id = $_GET['ground_id'];
    $sql = "SELECT *from `ground` where ground_id=$ground_id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $ground_name = $row['ground_name'];
    $ground_location = $row['ground_location'];
    $ground_latitude = $row['ground_latitude'];
    $ground_longitude = $row['ground_longitude'];
    $ground_description = $row['ground_description'];
    $ground_image = $row['ground_image'];
    $contact = $row['contact'];
    $amount = $row['amount'];

    ?>
    <section class="form-section">
        <h2>Add Your Futsal's Details</h2>
        <form id="futsal-form" method="POST" enctype="multipart/form-data" action="">

            <div class='error' id="logoError"></div>
            <label>Futsal Logo:</label>
            <input type="file" id="logo" name="futsal_logo">

            <div class='error' id="imageError"></div>
            <label>Ground Image:</label>
            <input type="file" id="image" name="ground_image">

            <div class='error' id="nameError"></div>
            <label>Futsal Name:</label>
            <input type="text" id="name" name="ground_name" value="<?php echo $ground_name; ?>">

            <div class='error' id=" locationError"></div>
            <label>Location:</label>
            <input type="text" id="location" name="ground_location" value="<?php echo $ground_location; ?>">

            <div class='error' id="latError"></div>
            <label>Latitude:</label>
            <input type="text" id="latitude" name="latitude" value="<?php echo $ground_latitude; ?>" readonly>

            <div class='error' id="longError"></div>
            <label>Longitude:</label>
            <input type="text" id="longitude" name="longitude" readonly value="<?php echo $ground_longitude; ?>">

            <button type="button" onclick="openMapPopup()">Set on Map</button>
            <button type="button" id="getLocationBtn">Get Current Location</button>

            <div class='error' id="numberError"></div>
            <label>Contact Number:</label>
            <input type="text" id="contact" name="contact" value="<?php echo $contact; ?>">

            <div class='error' id="amountError"></div>
            <label>Amount per hour:</label>
            <input type="number" id="amount" name="amount" value="<?php echo $amount; ?>">

            <div class='error' id="descriptionError"></div>
            <label>Description:</label>
            <textarea id="description" name="ground_description" rows="4"><?php echo $ground_description; ?></textarea>

            <div class="btn-container">
                <input type="submit" id="btnSubmit" value="Update" name="edit">
            </div>
        </form>
        <script>
            CKEDITOR.replace('description');
        </script>
    </section>


    <script>
        btnSubmit = document.getElementById('btnSubmit');
        btnSubmit.addEventListener('click', (event) => {

            const name = document.getElementById("name").value;
            const logo = document.getElementById("logo").value;
            const image = document.getElementById("image").value;
            const latitude = document.getElementById("latitude").value;
            const longitude = document.getElementById("longitude").value;
            const location = document.getElementById("location").value;
            const contact = document.getElementById("contact").value;
            // const description = document.getElementById("description").value;
            const amount = document.getElementById("amount").value;

            // Initialize CKEditor instance
            const editor = CKEDITOR.instances['description'];
            const description = editor.getData();

            // //logo validation
            // const logoError = document.getElementById("logoError");
            // if (logo === "") {
            //     event.preventDefault();
            //     logoError.textContent = "*required";
            // }

            // //image validation

            // const imageError = document.getElementById("imageError");
            // if (image === "") {
            //     event.preventDefault();
            //     imageError.textContent = "*required";
            // }


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

            //lat validation
            const latError = document.getElementById("latError");
            if (latitude === "") {
                event.preventDefault();
                latError.textContent = "*required";
            }

            //long validation
            const longError = document.getElementById("longError");
            if (longitude === "") {
                event.preventDefault();
                longError.textContent = "*required";
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
        $ground_description = $_POST['ground_description'];
        $amount = $_POST['amount'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        //file extension validity
        $isValid = true;

        // Handle ground image only if uploaded
        if (!empty($_FILES['ground_image']['name'])) {
            $ground_image_name = $_FILES['ground_image']['name'];
            $ground_image_tmp = $_FILES['ground_image']['tmp_name'];
            $ground_image_folder = "registerimage/" . $ground_image_name;

            $fileExtension = strtolower(pathinfo($ground_image_name, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];

            if (!in_array($fileExtension, $allowedExtensions)) {
                echo '<script>alert("Only JPG, JPEG, PNG, GIF, and Jfif files are allowed")</script>';
                $isValid = false;
            } else {
                move_uploaded_file($ground_image_tmp, $ground_image_folder);
            }
        }

        // Handle futsal logo only if uploaded
        if (!empty($_FILES['futsal_logo']['name'])) {
            $futsal_logo_name = $_FILES['futsal_logo']['name'];
            $futsal_logo_tmp = $_FILES['futsal_logo']['tmp_name'];
            $futsal_logo_folder = "registerimage/" . $futsal_logo_name;

            $fileExtension = strtolower(pathinfo($futsal_logo_name, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];

            if (!in_array($fileExtension, $allowedExtensions)) {
                echo '<script>alert("Only JPG, JPEG, PNG, GIF, and Jfif files are allowed")</script>';
                $isValid = false;
            } else {
                move_uploaded_file($futsal_logo_tmp, $futsal_logo_folder);
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
                    // Prepare the update query
                    $sql = "UPDATE ground 
    SET ground_name='$ground_name', 
        ground_location='$ground_location', 
        ground_latitude='$latitude', 
        ground_longitude='$longitude', 
        contact='$contact', 
        ground_description='$ground_description', 
        amount='$amount'";

                    if (!empty($ground_image_name)) {
                        $sql .= ", ground_image='$ground_image_folder'";
                    }

                    if (!empty($futsal_logo_name)) {
                        $sql .= ", futsal_logo='$futsal_logo_folder'";
                    }

                    $sql .= " WHERE ground_id='$ground_id'";

                    // Execute the query and check for errors
                    $result = mysqli_query($con, $sql);
                    if ($result) {
                        echo '<script>alert("Updated Successfully")</script>';
                        echo '<script>window.location.href = "myground.php?ground_id=' . $ground_id . '"</script>';
                        exit;
                    } else {
                        echo '<script>alert("Update Failed: ' . mysqli_error($con) . '")</script>';
                    }
                }

            }
        }
    }
    ?>
    <!-- window.location.href = "futsalregister.php"; -->
    <div id="mapPopup" style="display:none;">
        <div id="map" style="width: 600px; height: 400px;"></div>
        <button onclick="confirmLocation()">Confirm Location</button>
    </div>

    <script>
        function openMapPopup() {
            document.getElementById('mapPopup').style.display = 'block';

            // Initialize map
            var map = L.map('map').setView([27.700769, 85.300140], 13); // Default to Kathmandu

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            var marker;

            function onMapClick(e) {
                if (marker) {
                    map.removeLayer(marker);
                }
                marker = L.marker(e.latlng).addTo(map);
                document.getElementById('latitude').value = e.latlng.lat;
                document.getElementById('longitude').value = e.latlng.lng;
            }

            map.on('click', onMapClick);
        }

        function confirmLocation() {
            document.getElementById('mapPopup').style.display = 'none';

        }

        document.getElementById('getLocationBtn').addEventListener('click', function () {
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition, showError);
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            }

            function showPosition(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                document.getElementById('latitude').value = latitude;
                document.getElementById('longitude').value = longitude;
            }

            function showError(error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        alert("User denied the request for Geolocation.");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("Location information is unavailable.");
                        break;
                    case error.TIMEOUT:
                        alert("The request to get user location timed out.");
                        break;
                    case error.UNKNOWN_ERROR:
                        alert("An unknown error occurred.");
                        break;
                }
            }

            getLocation();
        });
    </script>
    <?php include 'footer.php'; ?>
</body>

</html>
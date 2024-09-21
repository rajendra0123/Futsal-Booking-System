<!DOCTYPE html>
<html lang="en">
<?php
include 'conn.php';
//session_start(); // Ensure session is started


$userName = '';
$role = '';

// Determine user role and fetch name
if (isset($_SESSION['owner_id'])) {
    $role = 'owner';
    $owner_id = $_SESSION['owner_id'];

    // Fetch owner's name
    $query = "SELECT fullname FROM owner WHERE owner_id = '$owner_id'";
    $result = mysqli_query($con, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userName = $row['fullname'];
    }
} elseif (isset($_SESSION['player_id'])) {
    $role = 'player';
    $player_id = $_SESSION['player_id'];

    // Fetch player's name
    $query = "SELECT fullname FROM player WHERE player_id = '$player_id'";
    $result = mysqli_query($con, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $userName = $row['fullname'];
    }
} else {
    $role = 'guest'; // No user logged in
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation Bar</title>
    <link rel="stylesheet" href="nav.css">
</head>

<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-title">
                <h1>FUTSOL</h1>
            </div>
            <div class="nav-links">
                <?php
                // Get the current page name
                $current_page = basename($_SERVER['PHP_SELF']);

                if ($role == 'owner') {
                    // Display navigation items for owners
                    if ($current_page == 'futsalregister.php') {
                        echo '<a href="ownerdetails.php?owner_id=' . $owner_id . '">Edit DETAILS</a>';
                        if (isset($status) && $status === 'Verified' && isset($ground_id) && !empty($ground_id)) {
                            echo '<a href="myground.php">MYGROUND</a>';
                        }
                    } elseif ($current_page == 'ownerdetails.php') {
                        echo '<a href="futsalregister.php">HOME</a>';
                    } elseif ($current_page == 'myground.php') {
                        echo '<a href="futsalregister.php">HOME</a>';
                    } elseif ($current_page == 'bookingtimeslots.php') {
                        echo '<a href="myground.php">My Ground</a>';
                    }

                } elseif ($role == 'player') {
                    // Display navigation items for players
                    if ($current_page == 'playerdetails.php') {
                        echo '<a href="playerhomepage.php">HOME</a>';
                        echo '<a href="groundlist.php">GROUNDS</a>';
                    } elseif ($current_page == 'groundlist.php') {
                        echo '<a href="playerhomepage.php">HOME</a>';
                    } elseif ($current_page == 'mybooking.php') {
                        echo '<a href="playerhomepage.php">HOME</a>';
                        echo '<a href="groundlist.php">GROUNDS</a>';
                    } elseif ($current_page == 'booking.php') {
                        echo '<a href="playerhomepage.php">HOME</a>';
                    } elseif ($current_page == 'nearfutsal.php') {
                        echo '<a href="mybooking.php">BOOKINGS</a>';
                        echo '<a href="groundlist.php">GROUNDS</a>';
                    } elseif ($current_page == 'timeslot.php') {

                    } else {
                        echo '<a href="mybooking.php">BOOKINGS</a>';
                        echo '<a href="groundlist.php">GROUNDS</a>';
                        echo '<a href="playerdetails.php?player_id=' . $player_id . '">Edit DETAILS</a>';
                    }
                } elseif ($role == 'guest') {
                    // // Display only the name FUTSOL when not logged in
                    // echo '<div class="nav-title"><h1>FUTSOL</h1></div>';
                }
                ?>

                <?php if ($role != 'guest'): ?>
                    <div class="user-info">
                        <img src="login.png" alt="User Image" class="owner-image" id="ownerImage">
                        <div class="dropdown">
                            <span class="user-name" id="userName">
                                <?php echo htmlspecialchars($userName); ?>
                            </span>
                            <div class="dropdown-content" id="dropdownContent">
                                <a href="logout.php">Logout</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ownerImage = document.getElementById('ownerImage');
            var dropdownContent = document.getElementById('dropdownContent');

            if (ownerImage) {
                ownerImage.addEventListener('click', function () {
                    dropdownContent.classList.toggle('show');
                });

                // Close the dropdown if the user clicks outside of it
                window.addEventListener('click', function (event) {
                    if (!event.target.matches('.owner-image')) {
                        if (dropdownContent.classList.contains('show')) {
                            dropdownContent.classList.remove('show');
                        }
                    }
                });
            }
        });
    </script>

</body>

</html>
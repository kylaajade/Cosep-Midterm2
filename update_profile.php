<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include 'dbconnection.php';

// Get user information from the database
$email = $_SESSION['email'];
$query = "SELECT student_id, first_name, last_name, email, gender, course, user_address, birthdate, profile_image 
          FROM css_tb WHERE email = :email";
$stmt = $connection->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get current date
$currentDate = date('D, F j');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your custom CSS file -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- FontAwesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
        <div class="top-right d-flex justify-content-between align-items-center mb-4">
            <a href="dash.php" class="btn btn-secondary">Back</a>
            <div class="date">
                <i class="fa-solid fa-calendar-day"></i> <?= $currentDate ?>
            </div>
            <a href="logout.php" class="btn btn-danger">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
            </a>
        </div>

        <h1>Update Profile</h1>

        <form id="updateProfileForm" enctype="multipart/form-data" method="POST" action="process_update.php">
            <input type="hidden" name="student_id" value="<?= $user['student_id'] ?>">
            <div class="form-group">
                <label for="ProfileImage">Profile Image</label>
                <input type="file" class="form-control" id="ProfileImage" name="profileImage" accept="image/*">
                <?php if (!empty($user['profile_image'])): ?>
                    <img src="<?= $user['profile_image'] ?>" alt="Profile Image" width="100" class="mt-2">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= $user['first_name'] ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= $user['last_name'] ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" readonly required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="Male" <?= $user['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $user['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                    <option value="Other" <?= $user['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="course">Course</label>
                <input type="text" class="form-control" id="course" name="course" value="<?= $user['course'] ?>" required>
            </div>
            <div class="form-group">
                <label for="user_address">Address</label>
                <input type="text" class="form-control" id="user_address" name="user_address" value="<?= $user['user_address'] ?>">
            </div>
            <div class="form-group">
                <label for="birthdate">Birthdate</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?= $user['birthdate'] ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>
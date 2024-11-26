<?php
session_start();
include "db.php";

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if ($username != "" && $password != "") {
        $sql_query = "SELECT * FROM `teachers` WHERE username='$username'";
        $result = mysqli_query($conn, $sql_query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);

            // Check if the password is correct
            if ($row['password'] == $password) {
                $_SESSION['username'] = $row['username'];
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['error'] = "Invalid username or password.";
            }
        } else {
            $_SESSION['error'] = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h3 class="text-center">Teacher Login</h3>

            <!-- Form for login -->
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <!-- Display error message in red -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="text-danger mt-3"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

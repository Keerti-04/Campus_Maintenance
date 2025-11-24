<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Maintenance Complaint Form</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, Helvetica, sans-serif;
        }
        .page-title {
            text-align: center;
            background-color: #4f7885ff;
            color: white;
            padding: 15px 0;
            width: 80%;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0px 3px 6px rgba(0,0,0,0.2);
        }
        .custom-card {
            box-shadow: 0px 3px 6px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
    </style>
</head>
<body>

<?php
// ------------------ WHEN FORM SUBMITTED ------------------
if (isset($_POST['submit'])) {

    $con = mysqli_connect("localhost", "root", "", "campus_maintenance");

    if (mysqli_connect_errno()) {
        echo "<p class='text-center text-danger fw-bold mt-3'>
                Database Connection Failed: " . mysqli_connect_error() . "
              </p>";
    } else {

        $name = $_POST['name'];
        $usn = $_POST['usn'];
        $location = $_POST['location'];
        $dept = $_POST['dept'];
        $type_arr = isset($_POST['type']) ? $_POST['type'] : [];
        $type = implode(", ", $type_arr);
        $priority = $_POST['priority'];
        $desc = $_POST['description'];

        // Photo upload
        $photo_name = '';
        if (!empty($_FILES['photo']['name'])) {
            $photo_name = time() . "_" . basename($_FILES['photo']['name']);
            move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photo_name);
        }

        // Insert
        $query = "INSERT INTO complaints
                  (name, usn, location, department, issues, priority, description, photo)
                  VALUES
                  ('$name', '$usn', '$location', '$dept', '$type', '$priority', '$desc', '$photo_name')";

        if (mysqli_query($con, $query)) {
            $_SESSION['last_usn'] = $usn;
            echo "<h2 class='text-center text-primary fw-bold mt-3'>
                    Complaint Submitted Successfully!
                  </h2>";
        } else {
            echo "<p class='text-center text-danger mt-3'>
                    Error: " . mysqli_error($con) . "
                  </p>";
        }

        // Show details
        echo "<div class='container mt-4'>
                <div class='table-responsive'>
                    <table class='table table-bordered table-striped'>
                        <tr><th>Field</th><th>Value</th></tr>
                        <tr><td>Name</td><td>$name</td></tr>
                        <tr><td>USN</td><td>$usn</td></tr>
                        <tr><td>Location</td><td>$location</td></tr>
                        <tr><td>Department</td><td>$dept</td></tr>
                        <tr><td>Issues</td><td>$type</td></tr>
                        <tr><td>Priority</td><td>$priority</td></tr>
                        <tr><td>Description</td><td>$desc</td></tr>";

        if ($photo_name != '') {
            echo "<tr><td>Photo</td><td><img src='uploads/$photo_name' width='150'></td></tr>";
        }

        echo "</table></div></div>";

        // View complaints button
        echo "
        <div class='text-center mt-4'>
            <a href='view_complaints.php?usn=$usn' class='btn btn-success btn-lg'>
                View My Complaints
            </a>
        </div>
        ";

        mysqli_close($con);
    }

} else {
// ------------------ SHOW FORM ------------------
?>

<h1 class="page-title">Campus Maintenance Complaint Form</h1>

<div class="container">
    <div class="card p-4 custom-card col-md-8 mx-auto">

        <form method="POST" action="" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label fw-bold">Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">USN / ID</label>
                <input type="text" name="usn" class="form-control" required minlength="10" maxlength="10">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Location</label>
                <input type="text" name="location" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Department</label>
                <input type="text" name="dept" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Type of Issue</label><br>

                <div class="form-check">
                    <input class="form-check-input issue-check" type="checkbox" name="type[]" value="Electrical">
                    <label class="form-check-label">Electrical</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input issue-check" type="checkbox" name="type[]" value="Plumbing">
                    <label class="form-check-label">Plumbing</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input issue-check" type="checkbox" name="type[]" value="Furniture">
                    <label class="form-check-label">Furniture</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input issue-check" type="checkbox" name="type[]" value="Cleaning">
                    <label class="form-check-label">Cleaning</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input issue-check" type="checkbox" name="type[]" value="Internet/WiFi">
                    <label class="form-check-label">Internet/WiFi</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input issue-check" type="checkbox" name="type[]" value="Others">
                    <label class="form-check-label">Others</label>
                </div>

            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Priority</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="priority" value="Low" required>
                    <label class="form-check-label">Low</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="priority" value="Medium" required>
                    <label class="form-check-label">Medium</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="priority" value="High" required>
                    <label class="form-check-label">High</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea class="form-control" name="description" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Upload Photo</label>
                <input class="form-control" type="file" name="photo" accept="image/*">
            </div>

            <div class="text-center">
                <button class="btn btn-success" type="submit" name="submit">Submit Complaint</button>
                <button class="btn btn-secondary" type="reset">Clear Form</button>
            </div>

        </form>

    </div>
</div>

<?php } ?>

<!-- Fade-in + checkbox validation -->
<script>
$(document).ready(function(){
    $("form").hide().fadeIn(500);

    // Require at least one issue
    $("form").on("submit", function(e){
        if ($(".issue-check:checked").length === 0) {
            alert("Please select at least one issue.");
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>

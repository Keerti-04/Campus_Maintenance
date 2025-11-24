<?php
$con = mysqli_connect("localhost","root","","campus_maintenance");

$usn = "";
if (isset($_GET['usn'])) {
    $usn = $_GET['usn'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Complaints</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-4 p-4 bg-white rounded shadow">

    <h2 class="text-center text-success mb-4">My Complaints</h2>

    <form method="GET" class="mb-4">
        <label class="form-label fw-bold">Enter your USN</label>
        <input type="text" name="usn" class="form-control mb-3" required minlength="10" maxlength="10" value="<?php echo $usn; ?>">
        <button class="btn btn-primary" type="submit">Search</button>
        <a href="complaint_form.php" class="btn btn-secondary ms-2">Back to Home</a>
    </form>

<?php
if ($usn != "") {

    $query = "SELECT * FROM complaints WHERE usn = '$usn'";
    $result = mysqli_query($con, $query);

    echo "<h4 class='mt-3'>Results for USN: <strong>$usn</strong></h4>";

    if (mysqli_num_rows($result) > 0) {

        echo "<div class='table-responsive mt-3'>
                <table class='table table-bordered table-striped'>
                    <tr>
                        <th>ID</th>
                        <th>Issue</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Photo</th>
                    </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['issues']}</td>
                    <td>{$row['priority']}</td>
                    <td><span class='badge bg-warning text-dark'>Pending</span></td>
                    <td>{$row['description']}</td>";

            if ($row['photo'] != "") {
                echo "<td><img src='uploads/{$row['photo']}' width='120'></td>";
            } else {
                echo "<td>No photo</td>";
            }

            echo "</tr>";
        }

        echo "</table></div>";

    } else {
        echo "<p class='text-danger mt-3'>No complaints found.</p>";
    }
}
?>

</div>
</body>
</html>

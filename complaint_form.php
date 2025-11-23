<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Maintenance Complaint Form</title>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, Helvetica, sans-serif;
        }

        h1 {
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 15px 0;
            width: 700px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0px 3px 6px rgba(0,0,0,0.2);
        }

        table {
            width: 700px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 3px 6px rgba(0,0,0,0.1);
            padding: 20px;
        }

        th {
            text-align: left;
            padding: 12px 0 5px 0;
            font-weight: bold;
        }

        td {
            padding: 8px 0;
        }

        input, select, textarea {
            width: 95%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type=checkbox], input[type=radio] {
            width: auto;
            margin-right: 5px;
        }

        input[type=submit], input[type=reset] {
            padding: 10px 20px;
            margin: 10px 5px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        input[type=submit]:hover, input[type=reset]:hover {
            background-color: #45a049;
        }

        textarea {
            resize: vertical;
        }

        
        table  {
            margin-top: 30px;
            border-collapse: collapse;
        }

        table , th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        table + table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>


<?php
if(isset($_POST['submit'])){
    $con = mysqli_connect("localhost","root","","campus_maintenance");
    if(mysqli_connect_errno()){
        echo "<p style='color:red;text-align:center;'>Database Connection Failed: ".mysqli_connect_error()."</p>";
    } else {
        $name = $_POST['name'];
        $usn = $_POST['usn'];
        $location = $_POST['location'];
        $dept = $_POST['dept'];
        $type_arr = isset($_POST['type']) ? $_POST['type'] : [];
        $type = implode(", ", $type_arr);
        $priority = $_POST['priority'];
        $desc = $_POST['description'];

        $photo_name = '';
        if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){
            $photo_name = time() . "_" . basename($_FILES['photo']['name']);
            $target_dir = "uploads/";
            $target_file = $target_dir . $photo_name;
            move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
        }

        $query = "INSERT INTO complaints (name, usn, location, department, issues, priority, description ,photo) 
                  VALUES ('$name','$usn','$location','$dept','$type','$priority','$desc' , '$photo_name')";
        if(mysqli_query($con,$query)){
            echo "<p style='color:blue;text-align:center;font-weight:bold;'>Complaint Submitted Successfully!</p>";
        } else {
            echo "<p style='color:red;text-align:center;'>Error: ".mysqli_error($con)."</p>";
        }

        echo "<table style='margin-top:20px;'>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        echo "<tr><td>Name</td><td>$name</td></tr>";
        echo "<tr><td>USN / ID</td><td>$usn</td></tr>";
        echo "<tr><td>Location</td><td>$location</td></tr>";
        echo "<tr><td>Department</td><td>$dept</td></tr>";
        echo "<tr><td>Issues</td><td>$type</td></tr>";
        echo "<tr><td>Priority</td><td>$priority</td></tr>";
        echo "<tr><td>Description</td><td>$desc</td></tr>";
        if($photo_name != ''){
            echo "<tr><td>Uploaded Photo</td><td><img src='uploads/$photo_name' width='150'></td></tr>";
        }
        echo "</table>";

        mysqli_close($con);
    }
} else {
?>
    <h1>Campus Maintenance Complaint Form</h1>
    <table>
    <form method="POST" action="" enctype="multipart/form-data">
        <tr>
            <th><label for="name">Name:</label></th>
            <td><input type="text" name="name" id="name" required></td>
        </tr>
        <tr>
            <th><label for="usn">USN / ID:</label></th>
            <td><input type="text" name="usn" id="usn" required></td>
        </tr>
        <tr>
            <th><label for="location">Location:</label></th>
            <td><input type="text" name="location" id="location" required></td>
        </tr>
        <tr>
            <th><label for="dept">Department:</label></th>
            <td><input type="text" name="dept" id="dept"></td>
        </tr>
        <tr>
            <th>Type of Issue:</th>
            <td>
                <input type="checkbox" name="type[]" value="Electrical">Electrical <br>
                <input type="checkbox" name="type[]" value="Plumbing">Plumbing <br>
                <input type="checkbox" name="type[]" value="Furniture">Furniture <br>
                <input type="checkbox" name="type[]" value="Cleaning">Cleaning <br>
                <input type="checkbox" name="type[]" value="Internet/WiFi">Internet/WiFi <br>
                <input type="checkbox" name="type[]" value="Others">Others <br>
            </td>
        </tr>
        <tr>
            <th>Priority:</th>
            <td>
                <input type="radio" name="priority" value="Low" required>Low
                <input type="radio" name="priority" value="Medium">Medium
                <input type="radio" name="priority" value="High">High
            </td>
        </tr>
        <tr>
            <th><label for="description">Description:</label></th>
            <td><textarea name="description" id="description" rows="4" cols="30" required></textarea></td>
        </tr>
        <tr>
            <th><label for="photo">Upload Photo:</label></th>
            <td><input type="file" name="photo" id="photo" accept="image/*"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;">
                <input type="submit" name="submit" value="Submit Complaint">
                <input type="reset" value="Clear Form">
            </td>
        </tr>
    </form>
    </table>
<?php
} 
?>

</body>
</html>
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$database = "portfolio";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Redirect if ID is not set or invalid
if (!isset($_GET['id'])) {
    $url = "builder.php";
    echo "<meta http-equiv='refresh' content='0;url=$url'>";
    exit;
}

$id = $_GET['id'];

// Retrieve number of skills from users table
$sql = "SELECT `no_of_skills` FROM `users` WHERE `id` = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    $url = "builder.php";
    echo "<meta http-equiv='refresh' content='0;url=$url'>";
    exit;
}

$row = mysqli_fetch_assoc($result);
$no_of_skills = $row['no_of_skills'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skills-Portfolio</title>
    <link rel="stylesheet" href="builder-style.css">
</head>
<body>
    <main>
        <h1 class="page-title">Enter Your Skills</h1>
        <form action="#" method="post">
            <?php 
                for ($i = 1; $i <= $no_of_skills; $i++) {
                    echo "<div class='nnn'><input name='skill$i' id='skill$i' type='text' placeholder='Skill $i' required>
                          <input type='number' name='percent$i' id='percent$i' placeholder='Percentage $i' required></div><br>";
                }
            ?>
            <button type="submit" name="submit">Submit</button>
        </form>
    </main>
</body>
</html>

<?php

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {

    if (isset($_POST['submit'])) {

        // Check if table exists for the user
        $sql_table_check = "SHOW TABLES LIKE '$id'";
        $result_table_check = mysqli_query($conn, $sql_table_check);

        if (mysqli_num_rows($result_table_check) <= 0) {
            // Create table for user if it doesn't exist
            $sql_create_table = "CREATE TABLE IF NOT EXISTS `$id` (
                `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
                `skill_name` VARCHAR(255),
                `percentage` INT(11)
            )";
            $result_create_table = mysqli_query($conn, $sql_create_table);
            if (!$result_create_table) {
                echo '<script>alert("Error creating table. Please try again.")</script>';
                $url = "portfolio-skills.php?id=$id";
                echo "<meta http-equiv='refresh' content='0;url=$url'>";
                exit;
            }
        }

        // Insert skills data into the user's table
        for ($i = 1; $i <= $no_of_skills; $i++) {
            $skill_name = $_POST["skill$i"];
            $percentage = $_POST["percent$i"];

            $sql_insert_data = "INSERT INTO `$id` (`skill_name`, `percentage`) VALUES ('$skill_name', '$percentage')";
            $result_insert_data = mysqli_query($conn, $sql_insert_data);

            if (!$result_insert_data) {
                echo '<script>alert("Error inserting data. Please try again.")</script>';
                $url = "portfolio-skills.php?id=$id";
                echo "<meta http-equiv='refresh' content='0;url=$url'>";
                exit;
            }
        }

        // Redirect after successful insertion
        echo '<script>alert("Your Portfolio has been successfully created!")</script>';
        $url = "index.php?id=$id";
        echo "<meta http-equiv='refresh' content='0;url=$url'>";
        exit;

    } else {
        echo '<script>alert("Form submission error. Please try again.")</script>';
        $url = "portfolio-skills.php?id=$id";
        echo "<meta http-equiv='refresh' content='0;url=$url'>";
        exit;
    }
}

// Close connection
mysqli_close($conn);

?>

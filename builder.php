<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Builder</title>
    <link rel="stylesheet" href="builder-style.css">
</head>

<body>
    <main>
        <!--  PORTFOLIO BUILDER FORM  -->
        <h1 class="page-title">Portfolio Builder</h1>
        <form action="#" method="post">
            <input type="text" class="username" name="name" id="name" placeholder="Full name" required>
            <input type="text" class="nickname" name="user" id="user" placeholder="User name" required>
            <input type="email" class="useremail" name="email" id="email" placeholder="Email address" required>
            <input type="text" class="tagline" name="tag" id="tag" placeholder="Your tagline" required>
            <input type="text" class="instagram" name="insta" id="insta" placeholder="Instagram link">
            <input type="number" class="whatsapp" name="mobile" id="mobile" placeholder="Whatsapp number">
            <input type="text" class="telegram" name="tg" id="tg" placeholder="Telegram link">
            <input type="number" class="skills" name="skill" id="skill" placeholder="Number of skills" required>
            <textarea name="abouts" class="about-me" name="abouts" id="abouts" placeholder="About you"></textarea>
            <select name="gender" id="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
            <button type="submit" name="submit">Next</button>
        </form>
    </main>
</body>

</html>


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

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    if (isset($_POST['submit'])) {
        
        // Retrieve form data
        $name = $_POST['name'];
        $username = $_POST['user'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
        $tagline = $_POST['tag'];
        $instagram = !empty($_POST['insta']) ? $_POST['insta'] : "https://instagram.com/fusycoder";
        $whatsapp = !empty($_POST['mobile']) ? $_POST['mobile'] : "8238790204";
        $telegram = !empty($_POST['tg']) ? $_POST['tg'] : "https://msng.link/o?amanderwal02=tg";
        $no_of_skills = $_POST['skill'];
        $abouts = $_POST['abouts'];

        // Check if username already exists
        $sql_check_username = "SELECT * FROM users WHERE username = '$username'";
        $result_check_username = mysqli_query($conn, $sql_check_username);

        if (mysqli_num_rows($result_check_username) > 0) {
            // Username already exists
            echo '<script>alert("Username already used! Please choose a different username.")</script>';
            $url = "builder.php";
            echo "<meta http-equiv='refresh' content='0;url=$url'>";
            exit; // Exit to prevent further execution
        }

        // Insert new user record
        $sql_insert_user = "INSERT INTO users (`name`, `username`, `email`, `tagline`, `gender` , `instagram`, `whatsapp`, `telegram`, `no_of_skills`, `about`) 
                            VALUES ('$name', '$username', '$email', '$tagline', '$gender' , '$instagram', '$whatsapp', '$telegram', '$no_of_skills', '$abouts')";
        $result_insert_user = mysqli_query($conn, $sql_insert_user);

        if ($result_insert_user) {
            // Get the inserted user ID
            $id = mysqli_insert_id($conn);

            // Redirect to next page with user ID
            $url = "portfolio-skills.php?id=$id";
            echo "<meta http-equiv='refresh' content='0;url=$url'>";
            exit; // Exit to prevent further execution
        } else {
            // Insertion failed
            echo '<script>alert("Something went wrong! Please try again.")</script>';
            $url = "builder.php";
            echo "<meta http-equiv='refresh' content='0;url=$url'>";
            exit; // Exit to prevent further execution
        }
    }
}

// Close connection
mysqli_close($conn);

?>

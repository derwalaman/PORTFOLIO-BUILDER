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

// Fetch user details based on ID from GET parameter
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve user details
    $sql_user = "SELECT * FROM `users` WHERE `id` = '$id'";
    $result_user = mysqli_query($conn, $sql_user);

    if ($result_user && mysqli_num_rows($result_user) > 0) {
        $row = mysqli_fetch_assoc($result_user);
        $name = $row['name'];
        $username = $row['username'];
        $email = $row['email'];
        $abouts = $row['about'];
        $instagram = $row['instagram'];
        $gender = $row['gender'];
        $whatsapp = $row['whatsapp'];
        $no_of_skills = $row['no_of_skills'];
        $tagline = $row['tagline'];
        $telegram = $row['telegram'];
    } else {
        // Redirect if user ID not found
        $url = "builder.php";
        echo "<meta http-equiv='refresh' content='0;url=$url'>";
        exit;
    }

    // Retrieve skills
    $skills = array();
    $sql_skills = "SELECT * FROM `$id` LIMIT $no_of_skills";
    $result_skills = mysqli_query($conn, $sql_skills);

    if ($result_skills) {
        while ($row_skills = mysqli_fetch_assoc($result_skills)) {
            $skills[] = array(
                'name' => $row_skills['skill_name'],
                'percentage' => $row_skills['percentage']
            );
        }
    }
} else {
    // Redirect if ID parameter not set
    $url = "builder.php";
    echo "<meta http-equiv='refresh' content='0;url=$url'>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name; ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="assets/images/my-avatar.ico" type="image/x-icon">
    <script src="https://kit.fontawesome.com/66aa7c98b3.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700,300|Quattrocento" rel="stylesheet">
    <link rel="shortcut icon" href="./img/fbavatar_1630400663387_6838396024049256103.png" type="image/x-icon">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <style>
    <?php
    // Generate CSS for skills dynamically
    for ($i = 0; $i < $no_of_skills; $i++) {
        echo ".progress-line .skill$i {
            width: " . $skills[$i]['percentage'] . "%;
        } 

        .progress-line .skill$i::after {
            content: '" . $skills[$i]['percentage'] . "%';
        }

        ";
    }
    ?>
    </style>
</head>

<body>
    <main>
        <nav class="navbar">
            <div class="nav-info">
                <h1 class="title">My Portfolio</h1>
                <ul class="menu">
                    <li><a href="#home-page">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#skill">Skills</a></li>
                    <li><a href="#contact-me">Contact us</a></li>
                </ul>
            </div>
        </nav>

        <div class="main-content">
            <div class="home-content" id="home-page">
                <div class="name">
                    <h1>I'm <?php echo $name; ?></h1>
                    <p><?php echo $tagline; ?></p>
                </div>
                <div class="angle-down-icon">
                    <a href="#about"><i class="fas fa-angle-down"></i></a>
                </div>
            </div>

            <div class="about-content">
                <div class="left-content" id="about">
                    <h1 class="about-title">About Me</h1>
                    <?php 
                        if ($gender == "female"){
                            echo '<img src="https://cdn4.iconfinder.com/data/icons/meta-people-3d-avatars/512/Purple_Shirt__T-Shirt_in_Woman_3d.png"
                        alt="image" />';
                        } elseif ($gender == "male"){
                            echo '<img src="https://i.postimg.cc/Hx4nChpH/fbavatar-1630400663387-6838396024049256103.png" alt="" />';
                        }
                    ?>
                    <p><br><?php echo $abouts; ?></p>
                </div>

                <div class="right-content" id="skill">
                    <h1 class="skills-title">My Skills</h1>
                    <div class="skills-bar">
                        <?php
                        for ($i = 0; $i < $no_of_skills; $i++) {
                            echo "<div class='bar'>
                                    <div class='info'>
                                        <span>" . $skills[$i]['name'] . "</span>
                                    </div>
                                    <div class='progress-line'><span class='skill$i'></span></div>
                                </div>";
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="contact" id="contact-me">
                <div class="contact-form">
                    <h1 class="form-title">Contact Form</h1>
                    <?php
                    echo "<form action='send.php?id=$id'>
                        <input type='text' class='form-name' name='username' id='username' placeholder='Full name' required>
                        <input type='email' class='form-email' name='email' id='useremail' placeholder='Email address' required>
                        <textarea name='message' class='form-message' id='msg' placeholder='Your message' required></textarea>
                        <button class='form-button' type='submit'>Send Message</button>
                    </form>";
                    ?>
                </div>

                <div class="contact-more">
                    <p class="links">Or find me on:</p>
                    <div class="social-links">
                        <a href="<?php echo $telegram; ?>" target="_blank"><i class="fab fa-telegram">
                                Telegram</i></a>
                        <a href="https://wa.me/91<?php echo $whatsapp; ?>?text=Hello" target="_blank"><i class="fab fa-whatsapp">
                                Whatsapp</i></a>
                        <a href="<?php echo $instagram; ?>" target="_blank"><i class="fab fa-instagram"></i>
                                Instagram</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>

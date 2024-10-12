<?php
include 'php/config.php';

if (isset($_POST['submit'])) {
    $ran_id = rand(time(), 1000000000);


    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $cpassword = mysqli_real_escape_string($conn, md5($_POST['cpassword']));

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_rename = $image;
        $image_folder = 'uploaded_img/' . $image_rename;
        $status = 'Active Now';


        $select = mysqli_query($conn, "SELECT * FROM user_form WHERE email='$email' AND password='$password'");

        if (mysqli_num_rows($select) > 0) {
            $alert[] = "user already exist!";
        } else {
            if ($password != $cpassword) {
                $alert[] = "password not matched!";
            } elseif ($image_size > 2000000) {
                $alert[] = "image size is too large!";
            } else {
                $insert = mysqli_query($conn, "INSERT INTO `user_form`(`user_id`, `username`, `email`, `password`, `img`, `status`) VALUES ('$ran_id','$name','$email','$password','$image_rename','$status')");

                if ($insert) {
                    move_uploaded_file($image_tmp_name, $image_folder);
                    header('location: login.php');
                } else {
                    $alert[] = "connection failed please retry!";
                }
            }
        }
    };
}




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>create account</title>
</head>

<body>
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>create account</h3>
            <?php
            if (isset($alert)) {
                foreach ($alert as $alert) {
                    echo '<div class="alert">' . $alert . '</div>';
                }
            }
            ?>
            <input type="text" name="name" placeholder="enter username" class="box" required>
            <input type="email" name="email" placeholder="enter email" class="box" required>
            <input type="password" name="password" placeholder="enter password" class="box" required>
            <input type="password" name="cpassword" placeholder="confirm password" class="box" required>
            <input type="file" name="image" class="box" accept="image/*">
            <input type="submit" name="submit" class="btn" value="start chatting">
            <p>already have an account? <a href="login.php">login now</a></p>
        </form>
    </div>
</body>

</html>
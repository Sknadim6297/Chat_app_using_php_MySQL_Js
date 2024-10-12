<?php
include 'php/config.php';

session_start();
$user_id=$_SESSION['user_id'];

if(!isset($user_id)){
    header('location: login.php');
}
$select=mysqli_query($conn,"SELECT * FROM user_form WHERE user_id='$user_id'");
if(mysqli_num_rows($select)>0){
    $row=mysqli_fetch_assoc($select);
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>update profile</title>
</head>
<body>
    <div class="update-profile">
        <form action="" method="post" enctype="multipart/form-data">
        <img src="uploaded_img/<?php echo $row['img'] ?>" alt="">
        <?php 
                if(isset($alert)){
                    foreach($alert as $alert){
                        echo '<div class="alert">'.$alert.'</div>';
                    }
                }
            ?>
            <div class="flex">
                <div class="inputBox">
                    <span>username :</span>
                    <input type="text" name="update_name" value="<?php echo $row['username'] ?>" class="box">
                    <span>your email :</span>
                    <input type="email" name="update_email" value="<?php echo $row['email'] ?>" class="box">
                    <span>update your pic</span>
                    <input type="file" name="update_image" accept="image/*" class="box">
                </div>
                <div class="inputBox">
                    <span>old password :</span>
                    <input type="password" name="old_pass" class="box">
                    <span>new password :</span>
                    <input type="password" name="new_pass" class="box">
                    <span>confirm password</span>
                    <input type="password" name="confirm_pass" class="box">
                </div>
            </div>
            <div class="flex btns">
                <input type="submit" value="update profile" name="update_profile" class="btn">
                <a href="home.php" class="delete-btn">Go back</a>
            </div>
        </form>
    </div>
</body>
</html>
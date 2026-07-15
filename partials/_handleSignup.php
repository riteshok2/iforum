<?php
$showError= "false";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    include '_dbconnect.php';

    $name = $_POST['name'];
    $user_email = $_POST['signupEmail'];
    $pass = $_POST['signupPassword'];
    $cpass = $_POST['signupcPassword'];

    //Check wheather this email exists
    $existSql = "SELECT * FROM `users` WHERE user_email = '$user_email'";
    $result = mysqli_query($conn, $existSql);
    $numRows = mysqli_num_rows($result);

    if($numRows>0){
        $showError = "Your email is already in use.";
    }else{
        if($pass==$cpass){
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users` (`user_email`, `user_pass`, `timespan`, `name`) VALUES ('$user_email', '$hash', current_timestamp(), '$name')";
            $result= mysqli_query($conn, $sql);
            if($result){
                $showAlert= true;
                header("location:/forum/index.php?signupsuccess=true");
                exit(); // Prevent further execution
            }
        else{
            $showError = "Password and confirm password are different.";
            
        }
    }
    header("location:/forum/index.php?signupsuccess=false&error=$showError");
    exit();
    }

}

?>
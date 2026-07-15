<?php
$showError= "false";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    include '_dbconnect.php';

    $email = $_POST['loginEmail'];
    $pass = $_POST['loginPassword'];

    //Check wheather this email exists
    $sql = "SELECT * FROM `users` WHERE user_email = '$email'";
    $result = mysqli_query($conn, $sql);
    $numRows = mysqli_num_rows($result);

    if($numRows==1){
        $row= mysqli_fetch_assoc($result);
        if(password_verify($pass , $row['user_pass'])){
            session_start();
            $_SESSION['loggedin'] = true;
             $_SESSION['useremail'] = $email;
             $_SESSION['username'] = $row['name'];
             $_SESSION['user_id'] = $row['user_id'];
             
             header("location:/forum/index.php?loginsuccess=true");
             exit();

        }else{
                // User not found case
                header("location:/forum/index.php?loginsuccess=fail");
                exit();
        }
    }
    
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./style.css">


    <title>Forum About</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php';?>
    <?php include 'partials/_header.php';?>

    <?php
    // Get thread ID from URL
    $thread_id = isset($_GET['threadid']) ? (int)$_GET['threadid'] : 0;

    // Default values
    $title = "Thread Not Found";
    $desc = "The thread you're looking for doesn't exist.";

    if ($thread_id > 0) {
        $sql = "SELECT * FROM `threads` WHERE thread_id = $thread_id";
        $result = mysqli_query($conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $title = $row['thread_title'];
            $desc = $row['thread_desc'];
        }
    }
    ?>





    <?php
    $showAlert = false;
    $method = $_SERVER['REQUEST_METHOD'];
     if($method== 'POST'){
        //Insert into comments DB:-
        $comment = $_POST['comment'];
        $comment = str_replace("<", "&lt;", $comment);
        $comment = str_replace(">", "&gt;", $comment);

        $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
        $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`) VALUES ('$comment', '$thread_id', '$user_id', current_timestamp());";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
                    echo "Error: " . mysqli_error($conn);
                }
        $showAlert = true;
        if($showAlert){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!!! </strong> Your comment has been added.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }
    ?>





    <div class="container my-4 jumb">
        <div class="jumbotron jumbotron-fluid custom-jumbo" >
            <h1 class="display-4 ms-3">
                <?php echo htmlspecialchars($title); ?>
            </h1>
            <p class="lead ms-3">
                <?php echo htmlspecialchars($desc); ?>
            </p>
            <hr class="my-4">
            <div class="ms-3">
            <ol>
                <li>This is to peer to peer forum for shearing knowledge with each other.</li>
                <li>Be Civil and
                    Respectful: Treat
                    others with respect and avoid being rude, offensive, or abusive.</li>
                <li>No Personal Attacks: Avoid harassing, bullying, or impersonating other users.</li>
                <li>Be Helpful: Assume good intentions from others and try to contribute constructively to discussions.
                </li>
                <li>Avoid Offensive Language: Don't post anything obscene, sexually explicit, or that could be
                    considered
                    hate speech.</li>
                <li>No Spam or Advertising: Don't post unsolicited advertisements, links to your website, or other
                    self-promotional content.</li>
                <li>Don't Share Personal Information: Be careful about sharing your email address, phone number, or
                    other
                    personal details that could be used by spammers or malicious actors.</li>

            </ol>
            </div>

            <?php
                $thread_id = isset($_GET['threadid']) ? (int)$_GET['threadid'] : 0;

                $title = "Thread Not Found";
                $desc = "The thread you're looking for doesn't exist.";
                $posted_by = "Unknown User";

                if ($thread_id > 0) {
                    $sql = "SELECT * FROM `threads` WHERE thread_id = $thread_id";
                    $result = mysqli_query($conn, $sql);

                    if ($row = mysqli_fetch_assoc($result)) {
                        $title = $row['thread_title'];
                        $desc = $row['thread_desc'];
                        $thread_user_id = $row['thread_user_id'];

                        // Get thread creator's name
                        $sql2 = "SELECT name FROM `users` WHERE user_id = '$thread_user_id' LIMIT 1";
                        $result2 = mysqli_query($conn, $sql2);
                        if ($result2 && $row2 = mysqli_fetch_assoc($result2)) {
                            $posted_by = $row2['name'];
                        }
                    }
                }
            ?>

            <p class="lead ms-3">Posted By : <b><?php echo htmlspecialchars($posted_by); ?></b></p>
        </div>
    </div>




    <?php 
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['username'])) {
        echo '<div class="container my-4">
            <h1 class="py-2">Post your Comment below:-</h1>

            <form action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '" method="POST">
                <div class="form-group">
                <label for="exampleFormControlTextarea1">Type your Comment</label>
                <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                <input type="hidden" name="user_id" value="' . $_SESSION["user_id"] . '">
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>';
    } else {
        echo '<div class="container my-4 alert alert-warning" id= "jumbotron">
            <h1 class="py-2">Post your Comment below:-</h1>
            You are not logged in. Please log in to post comments.
        </div>';
    }
    ?>





    <div class="container my-4 ques">
        <h1 class="py-3">Discussions</h1>
            <?php 
            $id = isset($_GET['threadid']) ? (int)$_GET['threadid'] : 0;
            $sql = "SELECT * FROM `comments` WHERE thread_id =$id";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)){
                    $id = htmlspecialchars($row['comment_id']);
                    $content = htmlspecialchars($row['comment_content']);
                    $comment_time = htmlspecialchars($row['comment_time']);
                    $thread_user_id = htmlspecialchars($row['comment_by']);



                    // Query user name based on thread_user_id
                        $sql2 = "SELECT name FROM `users` WHERE user_id='$thread_user_id' LIMIT 1";
                        $result2 = mysqli_query($conn, $sql2);
                        if ($result2 && mysqli_num_rows($result2) > 0) {
                                $row2 = mysqli_fetch_assoc($result2);
                                $Name = htmlspecialchars($row2['name']);
                            } else {
                                $Name = "Unknown User";
                            }
                
                
                    echo '<div class="media my-3">
                        <img class="mr-3" src="./user.jpg" width="34px" alt="user image">
                            <div class="media-body">
                            <p class="my-0"><b>' . $Name . ' at ' . htmlspecialchars($comment_time) . '</b></p>
                            ' . htmlspecialchars($content) . '
                            </div>
                        </div>';
                        }
                    } else {
                            echo '<div class="jumbotron jumbotron-fluid custom-jumbo jumb">
                             <div class="container">
                                <p class="display-4">Be the first Person to Comment on Query:- ' . htmlspecialchars($desc) . '</p>
                                <p class="lead">No comment found.</p>
                             </div>
                        </div>';
                        }
            ?> 

    </div>








    <?php include 'partials/_footer.php';?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
</body>

</html>
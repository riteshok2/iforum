
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous" />


    <title>Forum Categories</title>
</head>

<body>
    <?php include 'partials/_header.php'; ?>
    <?php include 'partials/_dbconnect.php'; ?>

    <?php 
    $cat_id = isset($_GET['catid']) ? (int)$_GET['catid'] : 0;

    // Fetch category details
    $sql = "SELECT * FROM `categories` WHERE category_id = $cat_id";
    $result = mysqli_query($conn, $sql);
    $cat_name = $cat_desc = "";
    if ($row = mysqli_fetch_assoc($result)) {
        $cat_name = $row['category_name'];
        $cat_desc = $row['category_description'];
    }
    ?>

    <?php
    // Handle POST request to add a new thread
    $showAlert = false;
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method == 'POST') {
        $th_title = mysqli_real_escape_string($conn, $_POST['title']);
        $th_title = str_replace("<", "&lt;", $th_title);
        $th_title = str_replace(">", "&gt;", $th_title);

        $th_desc = mysqli_real_escape_string($conn, $_POST['desc']);
        $th_desc = str_replace("<", "&lt;", $th_desc);
        $th_desc = str_replace(">", "&gt;", $th_desc);

        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
        

        $sql = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`, `timestamp`) 
                VALUES ('$th_title', '$th_desc', '$cat_id', '$user_id', current_timestamp())";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $showAlert = true;
        }

        if ($showAlert) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Successfully Entered!</strong> Your thread has been entered successfully. Please wait for the community to respond.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
    }
    ?>

    <div class="container my-4 jumb">
        <div class="jumbotron jumbotron-fluid custom-jumbo ">
            <h1 class="display-4 ms-3">Welcome to <?php echo htmlspecialchars($cat_name); ?> Forums</h1>
            <p class="lead ms-3"><?php echo htmlspecialchars($cat_desc); ?></p>
            <hr class="my-4" />
            <p class="ms-3">
                <ol>
                    <li>This is a peer-to-peer forum for sharing knowledge with each other.</li>
                    <li>Be Civil and Respectful: Treat others with respect and avoid being rude, offensive, or abusive.</li>
                    <li>No Personal Attacks: Avoid harassing, bullying, or impersonating other users.</li>
                    <li>Be Helpful: Assume good intentions from others and try to contribute constructively to discussions.</li>
                    <li>Avoid Offensive Language: Don't post anything obscene, sexually explicit, or that could be considered hate speech.</li>
                    <li>No Spam or Advertising: Don't post unsolicited advertisements, links to your website, or other self-promotional content.</li>
                    <li>Don't Share Personal Information: Be careful about sharing your email address, phone number, or other personal details.</li>
                </ol>
            </p>
            <p class="lead ms-3">
                <a class="btn btn-success btn-lg" href="#" role="button">Learn more</a>
            </p>
        </div>
    </div>

    <?php 
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['username'])) {
        echo '<div class="container my-4">
            <h1 class="py-2">Start A Discussion:</h1>

            <form action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '" method="POST">
                <div class="form-group mb-3">
                    <label for="title">Problem Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter Problem Title" required />
                    <small class="form-text text-muted">Keep your title as short and crisp as possible.</small>
                </div>
                <div class="form-group mb-3">
                    <label for="desc">Elaborate Your Concern</label>
                    <textarea class="form-control" id="desc" name="desc" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
        </div>';
    } else {
        echo '<div class="container my-4 alert alert-warning jumb">
            <h1 class="py-2">Start A Discussion:</h1>
            You are not logged in. Please log in to start a discussion.
        </div>';
    }
    ?>

    <div class="my-4 ques jumb container">
        <h1 class="py-3">Browse Questions</h1>
        <?php 
        $sql = "SELECT * FROM `threads` WHERE thread_cat_id = $cat_id";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $thread_id = htmlspecialchars($row['thread_id']);
                $title = htmlspecialchars($row['thread_title']);
                $desc = htmlspecialchars($row['thread_desc']);
                $thread_time = htmlspecialchars($row['timestamp']);
                $thread_user_id = htmlspecialchars($row['thread_user_id']);



                // Query user name based on thread_user_id
                $sql2 = "SELECT name FROM `users` WHERE user_id=$thread_user_id";
                $result2 = mysqli_query($conn, $sql2);
                // $row2 = mysqli_fetch_assoc($result2);
                // $Name = htmlspecialchars($row2['name']);
                if ($result2 && mysqli_num_rows($result2) > 0) {
                        $row2 = mysqli_fetch_assoc($result2);
                        $Name = $row2['name'];
                    } else {
                        $Name = "Unknown User";
                    } 



                echo '<div class="media my-3">
                        <img class="mr-3" src="./user.jpg" width="34px" alt="user image" />
                        <div class="media-body">
                            <p class="my-0">Asked by <b>' . $Name . ' at ' . $thread_time . '</b></p>
                            <h5 class="mt-0">
                                <a class="text-dark" target="_blank" href="thread.php?threadid=' . $thread_id . '">' . $title . '</a>
                            </h5>
                            ' . $desc . '
                        </div>
                    </div>';
            }
        } else {
            echo '<div class="jumbotron jumbotron-fluid custom-jumbo">
                    <div class="container">
                        <p class="display-4">Be the first person in this category!</p>
                        <p class="lead">No threads found for this category.</p>
                    </div>
                </div>';
        }
        ?>
    </div>

    <?php include 'partials/_footer.php'; ?>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="./app.js" defer></script>
    <link rel="stylesheet" href="style.css">

    <!-- jquery include:- -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <title>iFORUM- Coding forum.</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php';?>
    <?php include 'partials/_header.php';?>

    <!-- corausal/Slider code starts for here:- -->
    <div class="my-0">
        <!-- <h2>Coding Image Carousel</h2> -->
        <div id="carouselContainer" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner" id="carousel-inner">
                <!-- Images will be inserted here -->
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselContainer"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselContainer"
                data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Categories container start from here:- -->
    <div class="container my-3 ques">
        <h1 class="text-center my-3">Coding FORUM-Categories.</h1>
        <div class="row">
    
    <!-- Fetch all the categories -->
    <?php 
    $sql = "SELECT * FROM `categories`";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)){
        $cat_id = $row['category_id'];
        $cat_name = $row['category_name'];
        $cat_desc = $row['category_description'];
        
        echo '<div class="col-md-4 my-2">
                <div class="card category-card" data-category="'. htmlspecialchars($cat_name) .'" style="width: 18rem;">
                    <img src="placeholder.jpg" class="card-img-top fixed-size" alt="Image">
                    <div class="card-body">
                        <h5 class="card-title"><a href = "threadlist.php?catid='. $cat_id .'">'. htmlspecialchars($cat_name) .'</a></h5>
                        <p class="card-text">'. substr(htmlspecialchars($cat_desc), 0, 90) . '...</p>
                        <a href="threadlist.php?catid='. $cat_id .'" class="btn btn-primary" target="_blank">View Threads</a>
                    </div>
                </div>
            </div>';
    }
    ?>
        </div>
    </div>


    <?php include 'partials/_footer.php';?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
        </script>


</body>

</html>
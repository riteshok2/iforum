<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="./app.js" defer></script>
    <link rel="stylesheet" href="style.css">

    <!-- jQuery & Bootstrap -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">


    <title>iFORUM - Coding forum</title>
</head>

<body>
    <?php include 'partials/_dbconnect.php'; ?>
    <?php include 'partials/_header.php'; ?>

    <div class="container my-3">
        <?php
        // Capture search term
        $search = $_GET['search'];
        echo "<h1 class='py-3'>Search results for <em>\"" . htmlspecialchars($search) . "\"</em></h1>";

        // Pagination logic
        $limit = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Count total results
        $sql_count = "SELECT COUNT(*) as total FROM threads WHERE MATCH (thread_title, thread_desc) AGAINST ('$search')";
        $result_count = mysqli_query($conn, $sql_count);
        $total_results = mysqli_fetch_assoc($result_count)['total'];
        $total_pages = ceil($total_results / $limit);

        // Fetch paginated results
        $sql = "SELECT * FROM threads WHERE MATCH (thread_title, thread_desc) AGAINST ('$search') LIMIT $limit OFFSET $offset";
        $result = mysqli_query($conn, $sql);

        $noresult = true;
        while ($row = mysqli_fetch_assoc($result)) {
            $noresult = false;
            $title = $row['thread_title'];
            $desc = $row['thread_desc'];
            $id = $row['thread_id'];
            $url = "thread.php?threadid=" . $id;

            echo '<div class="result mb-4">';
            echo '<h3><a href="' . $url . '" class="text-dark">' . htmlspecialchars($title) . '</a></h3>';
            echo '<p>' . htmlspecialchars($desc) . '</p>';
            echo '</div>';
        }

        if ($noresult) {
            echo '<div class="container jumbotron jumbotron-fluid custom-jumbo" id="jumbotron">
                            <p class="display-6">No any similar text found like:- ' . $_GET["search"] . '</p>
                            <p class="lead">Suggestions: 
                                <ol>
                                    <li>Make sure that all words are spelled correctly.</li>
                                    <li>Try different key word.</li>
                                    <li>Try more general key word.</li>
                                </ol>
                            </p>          
                    </div>';
        }
        ?>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <!-- Previous -->
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= max(1, $page - 1) ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <!-- Page numbers -->
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Next -->
                <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= min($total_pages, $page + 1) ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>

    <?php include 'partials/_footer.php'; ?>
</body>
</html>

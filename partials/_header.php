<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

include_once __DIR__ . '/_dbconnect.php';

echo '
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/">
      <img src="./logo.png" alt="Coding FORUM Logo" height="50">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="_about.php">About</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Top Categories
          </a>
          <ul class="dropdown-menu">';

          if (isset($conn) && $conn) {
              $sql = "SELECT category_name, category_id FROM `categories` LIMIT 10";
              $result = mysqli_query($conn, $sql);
              if ($result) {
                  while ($row = mysqli_fetch_assoc($result)) {
                      echo '<li><a class="dropdown-item" href="threadlist.php?catid=' . $row['category_id'] . '">' . htmlspecialchars($row['category_name']) . '</a></li>';  
                  }
              }
          }
      echo '</ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="_contact.php">Contact Us</a>
        </li>
      </ul>

      <div class="d-flex align-items-center justify-content-between mx-2 my-2 flex-wrap">
        <!-- Search Form -->
        <form class="form-inline d-flex align-items-center me-3" role="search" method="get" action="search.php">
          <input class="form-control me-2" name="search" type="search" placeholder="Search" aria-label="Search" />
          <button class="btn btn-success" type="submit">Search</button>
        </form>';

        // Login state handling
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true && isset($_SESSION['username'])) {
              echo '
              <div class="d-flex align-items-center">
                  <span class="me-3 text-white">Welcome ' . htmlspecialchars($_SESSION['username']) . '</span>
                  <form action="partials/_logout.php" method="post" class="mb-0">
                      <button type="submit" class="btn btn-outline-danger btn-sm me-2">Logout</button>
                  </form>
              </div>';
        } else {
          echo '
          <div class="d-flex align-items-center">
            <button type="button" class="btn btn-outline-success me-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#signupModal">SignUp</button>
          </div>';
        }

echo '
      </div>
    </div>
  </div>
</nav>';

// Include modals
include_once __DIR__ . '/_loginModal.php';
include_once __DIR__ . '/_signupModal.php';

// Alerts for signup or login
if (isset($_GET['signupsuccess']) && $_GET['signupsuccess'] == "true") {
  echo '<div class="alert alert-success alert-dismissible my-0" role="alert">
          <h4 class="alert-heading">Well done!</h4>
          <p>You have successfully signed up. Now go to login for the Coding Forum.</p>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
}

if (isset($_GET['loginsuccess'])) {
  if ($_GET['loginsuccess'] == "true") {
    echo '<div class="alert alert-success alert-dismissible my-0" role="alert">
            <h4 class="alert-heading">Well done!</h4>
            <p>You have successfully Logged In.</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
  } elseif ($_GET['loginsuccess'] == "fail") {
    echo '<div class="alert alert-danger alert-dismissible fade show my-0" role="alert">
            <strong>Error!</strong> Invalid email or password.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
  }
}
?>

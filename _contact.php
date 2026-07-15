<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">


    <style>
        
        .contact-container {
            min-height: 100vh;
            max-width: 1000px;
            margin: 50px auto;
            background: rgb(169, 241, 169);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        } 
        h1 {
            color: #2c3e50;
            text-align: center;
        }
        address {
            font-style: normal;
            line-height: 1.8;
            font-size: 16px;
        }
        a {
            color: #0066cc;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
        }
    </style>


    <title>Forum Contact</title>
</head>
<body>
    <?php include 'partials/_dbconnect.php';?>
    <?php include 'partials/_header.php';?>

    <?php
        // Handle form submission
        $success = '';
        $error = '';

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // Sanitize and prepare data
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $message = trim($_POST['message']);

            if ($name && $email && $message) {
                $stmt = $conn->prepare("INSERT INTO `contact_messages` (`name`, `email`, `message`) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $message,);

                if ($stmt->execute()) {
                    $success = "Thank you! Your message has been sent.";
                } else {
                    $error = "Oops! Something went wrong.";
                }

                $stmt->close();
            } else {
                $error = "Please fill in all the fields.";
            }

            $conn->close();
        }
    ?>

    <div class="contact-container">
      <h2>Contact Us</h2>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>


        <form method="post" action="_contact.php">
            <div class="mb-3">
                <label for="name" class="form-label">Your Name</label>
                <input type="text" class="form-control" name="name" id="name" required value="<?= isset($name) ? htmlspecialchars($name) : '' ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Your Email</label>
                <input type="email" class="form-control" name="email" id="email" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Your Message</label>
                <textarea class="form-control" name="message" id="message" rows="5" required><?= isset($message) ? htmlspecialchars($message) : '' ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>

        <a class="back-link" href="index.php">&larr; Back to Home</a>
    </div>




    <?php include 'partials/_footer.php';?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
    crossorigin="anonymous"></script>
</body>
</html>

<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $email = $confirm_password = "";
$username_err = $password_err = $email_err = $confirm_password_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate username
  if (empty(trim($_POST["username"]))) {
      $username_err = "Please enter a username.";
  } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
      $username_err = "Username can only contain letters, numbers, and underscores.";
  } else {
      // Prepare a select statement
      $sql = "SELECT id FROM users WHERE username = ?";

      if ($stmt = $mysqli->prepare($sql)) {
          // Bind variables to the prepared statement as parameters
          $stmt->bind_param("s", $param_username);

          // Set parameters
          $param_username = trim($_POST["username"]);

          // Attempt to execute the prepared statement
          if ($stmt->execute()) {
              // store result
              $stmt->store_result();

              if ($stmt->num_rows == 1) {
                  $username_err = "This username is already taken.";
              } else {
                  $username = trim($_POST["username"]);
              }
          } else {
              echo "Oops! Something went wrong. Please try again later.";
          }

          // Close statement
          $stmt->close();
      }
  }

  // Validate email
  if (empty(trim($_POST["email"]))) {
    $email_err = "Please enter email.";
} else {
    $email = trim($_POST["email"]);
}

  

  // Validate password
  if (empty(trim($_POST["password"]))) {
      $password_err = "Please enter a password.";
  } elseif (strlen(trim($_POST["password"])) < 6) {
      $password_err = "Password must have atleast 6 characters.";
  } else {
      $password = trim($_POST["password"]);
  }

  // Validate confirm password
  if (empty(trim($_POST["confirm_password"]))) {
      $confirm_password_err = "Please confirm password.";
  } else {
      $confirm_password = trim($_POST["confirm_password"]);
      if (empty($password_err) && ($password != $confirm_password)) {
          $confirm_password_err = "Password did not match.";
      }
  }

  // Check input errors before inserting in database
  if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

      // Prepare an insert statement
      $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

      if ($stmt = $mysqli->prepare($sql)) {
          // Bind variables to the prepared statement as parameters
          $stmt->bind_param("sss", $param_username, $param_email, $param_password);

          // Set parameters
          $param_username = $username;
          $param_email = $email;
          $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

          // Attempt to execute the prepared statement
          if ($stmt->execute()) {
              // Redirect to login page
              header("location: login.php");
          } else {
              echo "Oops! Something went wrong. Please try again later.";
          }

          // Close statement
          $stmt->close();
      }
  }

  // Close connection
  $mysqli->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>GrossOut!</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/logo.png" rel="icon">
  <link href="assets/img/logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Amatic+SC:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center me-auto me-lg-0">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1>GrossOut<span>!</span></h1>
      </a>

      <a class="btn-book-a-table" href="login.php">Login</a>
      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>

    </div>
  </header><!-- End Header -->

 <!-- ======= Book A Table Section ======= -->

  <section id="book-a-table" class="book-a-table">
  
    <div class="section-header">
      <h4>"GrossOut!" </h4>
      <p>Join us and <span>keep your food</span> supervised!</p>
    </div>

    <div class="row g-0">

      <div class="col-lg-4 reservation-img" style="background-image: url(assets/img/book-a-table-2.png);" data-aos="zoom-out" data-aos-delay="200"></div>

      <div class="col-lg-8 d-flex align-items-center reservation-form-bg">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" role="form" class="php-email-form" >
          <div class="row gy-4">
          <div class="form-group mt-3">
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>"placeholder="Username">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
        
            </div>
            <div class="form-group mt-3">
              <input type="email" class="form-control<?php echo (!empty($email_err))? 'is-invalid' : ''; ?> " value="<?php echo $email; ?>" name="email" id="email" placeholder="Email" >
              <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>

          <div class="form-group mt-3">
            <input type="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" name="password" id="password" placeholder="Password">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
            
          </div>
          <div class="form-group mt-3">
            <input type="password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" name="confirm_password" id="confirm_password" placeholder="Confirm Password" >
            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
          
          </div>
           <div class="text-center"><button type="submit">Sign Up</button></div>
          </div>
          </form>
      </div><!-- End Reservation Form -->

    </div>

  </div>
  </section>
</body>

</html>
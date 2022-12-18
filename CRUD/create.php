<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$foodname = $quantity = $expdate  = $usedDays = $status="" ;
$foodname_err = $quantity_err = $expdate_err = "";


// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    $input_foodname = trim($_POST["foodname"]);
    if (empty($input_foodname)) {
        $foodname_err = "Please enter food name.";
    } else {
        $foodname = $input_foodname;
    }

   
     // Quantity
     $input_quantity = trim($_POST["quantity"]);
     if (empty($input_quantity)) {
         $quantity_err = "Please enter the quantity";
     } elseif (!ctype_digit($input_quantity)) {
         $quantity_err = "Please enter the right quantity";
     } else {
         $quantity = $input_quantity;
     }

     //validate Date 
     $thisDate = date("Y-m-d");
     $input_expdate = $_POST["expdate"];
     if($input_expdate != ''){
        $expdate = $input_expdate;
     }else{
        $expdate_err = "Please select the right expiration date.";
     } 
     
    // Check input errors before inserting in database
    if (empty($foodname_err) && empty($quantity_err) && empty($expdate_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO food (foodname, quantity, usedDays, status, expdate) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_foodname, $param_quantity, $param_usedDays, $param_status, $param_expdate);

            // Set parameters
            $param_foodname = $foodname;
            $param_quantity = $quantity;
            $param_usedDays = $usedDays;
            $param_status = $status;
            $param_expdate = $expdate;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto; 
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Food Name</label>
                            <input type="text" name="foodname" class="form-control <?php echo (!empty($foodname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $foodname; ?>">
                            <span class="invalid-feedback"><?php echo $foodname_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input name="quantity" class="form-control <?php echo (!empty($quantity_err)) ? 'is-invalid' : ''; ?>"><?php echo $quantity; ?></input>
                            <span class="invalid-feedback"><?php echo $quantity_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Expiration Date</label>
                            <input type="date" name="expdate" placeholder="date" class="form-control">
                        </div>
                    

                        <input type="submit" class="btn btn-danger" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
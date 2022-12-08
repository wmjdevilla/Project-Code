<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$foodname = $quantity = $expdate = $month = $day = $year ="";
$foodname_err = $quantity_err = $expdate_err = $month_err = $day_err = $year_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    $input_foodname = trim($_POST["foodname"]);
    if (empty($input_foodname)) {
        $foodname_err = "Please enter food name.";
    } else {
        $foodname = $input_foodname;
    }

    // Validate address
     // Validate salary
     $input_quantity = trim($_POST["quantity"]);
     if (empty($input_quantity)) {
         $quantity_err = "Please enter the quantity";
     } elseif (!ctype_digit($input_quantity)) {
         $quantity_err = "Please enter the right quantity";
     } else {
         $quantity = $input_quantity;
     }

     // Validate month, day, and year
     $input_month= (int)($_POST["month"]);
     $input_day= (int)($_POST["day"]);
     $input_year= (int)($_POST["year"]);

     
     
     if (empty($input_month)) {
         $month_err = "Please enter the correct number of the month";
     } elseif (!ctype_digit($input_month)) {
         $month_err = "Please enter the right date";
     } else {
         $month = $input_month;
     }
    if (empty($input_day)) {
        $day_err = "Please enter the correct number of the day";
    } elseif (!ctype_digit($input_day)) {
        $day_err = "Please enter the right date";
    } else {
        $day = $input_day;
    }
    if (empty($input_year)) {
        $year_err = "Please enter the correct number of the year";
    } elseif (!ctype_digit($input_year)) {
        $year_err = "Please enter the right date";
    } else {
        $year = $input_year;
    }

    $validate = checkdate($input_month, $input_day, $input_year);
    if($validate){
        $valid = $year.'-'.$month.'-'.$day;
    }
    else{
        $month_err = "Please enter the right date";
        $day_err = "Please enter the correct number of the day";
        $year_err = "Please enter the right date";
    }
    
    $expdate = $valid;
   

    
    

    // check it: 
    // Check input errors before inserting in database
    if (empty($foodname_err) && empty($quantity_err) && empty($expdate_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO food (foodname, quantity, expdate) VALUES (?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_foodname, $param_quantity, $param_expdate);

            // Set parameters
            $param_foodname = $foodname;
            $param_quantity = $quantity;
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
                            <textarea name="quantity" class="form-control <?php echo (!empty($quantity_err)) ? 'is-invalid' : ''; ?>"><?php echo $quantity; ?></textarea>
                            <span class="invalid-feedback"><?php echo $quantity_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Expiration Date</label>
                            <input type="text" name="month" placeholder="Month" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="text" name="day" placeholder="Day" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="text" name="year" placeholder="Year" class="form-control"><br /><br />
                        </div>
                    

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
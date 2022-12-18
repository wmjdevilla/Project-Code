<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$foodname = $quantity = $expdate = "";
$foodname_err = $quantity_err = $expdate_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    // Validate foodname$foodname
    $input_foodname = trim($_POST["foodname"]);
    if (empty($input_foodname)) {
        $foodname_err = "Please enter a foodname.";
    } else {
        $foodname = $input_foodname;
    }

    // Validate quantity quantity
    $input_quantity = trim($_POST["quantity"]);
    if (empty($input_quantity)) {
        $quantity_err = "Please enter an quantity.";
    } else {
        $quantity = $input_quantity;
    }

     //validate Date 
     $input_expdate = $_POST["expdate"];
     if($input_expdate != ''){
        $expdate = $input_expdate;
     }else{
        $expdate_err = "Please select the right expiration date.";
     }

    // Check input errors before inserting in database
    if (empty($foodname_err) && empty($quantity_err) && empty($expdate_err)) {
        // Prepare an update statement
        $sql = "UPDATE food SET foodname=?, quantity=?, expdate=? WHERE id=?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssi", $param_foodname, $param_quantity, $param_expdate, $param_id);

            // Set parameters
            $param_foodname = $foodname;
            $param_quantity = $quantity;
            $param_expdate = $expdate;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records updated successfully. Redirect to landing page
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
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM food WHERE id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $foodname = $row["foodname"];
                    $quantity = $row["quantity"];
                    $expdate = $row["expdate"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();

        // Close connection
        $mysqli->close();
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
                            <input type="date" name="expdate" placeholder="date" class="form-control">
                            
                        </div>
                
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-danger" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
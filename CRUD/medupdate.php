<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$medname = $medquantity = $medexpdate = "";
$medname_err = $medquantity_err = $medexpdate_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    // Validate medname$medname
    $input_medname = trim($_POST["medname"]);
    if (empty($input_medname)) {
        $medname_err = "Please enter a medicine name.";
    } else {
        $medname = $input_medname;
    }
   // Validate medquantity medquantity
    $input_medquantity = trim($_POST["medquantity"]);
    if (empty($input_medquantity)) {
        $medquantity_err = "Please enter quantity.";
    } else {
        $medquantity = $input_medquantity;
    }

     //validate Date 
     $input_medexpdate = $_POST["medexpdate"];
     if($input_medexpdate != ''){
        $medexpdate = $input_medexpdate;
     }else{
        $medexpdate_err = "Please select the right expiration date.";
     }

    // Check input errors before inserting in database
    if (empty($medname_err) && empty($medquantity_err) && empty($medexpdate_err)) {
        // Prepare an update statement
        $sql = "UPDATE medicine SET medname=?, medquantity=?, medexpdate=? WHERE id=?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssi", $param_medname, $param_medquantity, $param_medexpdate, $param_id);

            // Set parameters
            $param_medname = $medname;
            $param_medquantity = $medquantity;
            $param_medexpdate = $medexpdate;
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
        $sql = "SELECT * FROM medicine WHERE id = ?";
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
                    $medname = $row["medname"];
                    $medquantity = $row["medquantity"];
                    $medexpdate = $row["medexpdate"];
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
                            <label>Medicine Name</label>
                            <input type="text" name="medname" class="form-control <?php echo (!empty($medname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $medname; ?>">
                            <span class="invalid-feedback"><?php echo $medname_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <textarea name="medquantity" class="form-control <?php echo (!empty($medquantity_err)) ? 'is-invalid' : ''; ?>"><?php echo $medquantity; ?></textarea>
                            <span class="invalid-feedback"><?php echo $medquantity_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Expiration Date</label>
                            <input type="date" name="medexpdate" placeholder="date" class="form-control">
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
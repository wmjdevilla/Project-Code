<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$medname = $medquantity = $medexpdate  ="";
$medname_err = $medquantity_err = $medexpdate_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    $input_medname = trim($_POST["medname"]);
    if (empty($input_medname)) {
        $medname_err = "Please enter food name.";
    } else {
        $medname = $input_medname;
    }

    
     //Quantity
     $input_medquantity = trim($_POST["medquantity"]);
     if (empty($input_medquantity)) {
         $medquantity_err = "Please enter the medquantity";
     } elseif (!ctype_digit($input_medquantity)) {
         $medquantity_err = "Please enter the right medquantity";
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

     
     
    
   

    
    

    // check it: 
    // Check input errors before inserting in database
    if (empty($medname_err) && empty($medquantity_err) && empty($medexpdate_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO medicine (medname, medquantity, medexpdate) VALUES (?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_medname, $param_medquantity, $param_medexpdate);

            // Set parameters
            $param_medname = $medname;
            $param_medquantity = $medquantity;
            $param_medexpdate = $medexpdate;

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
                            <label>Medicine Name</label>
                            <input type="text" name="medname" class="form-control <?php echo (!empty($medname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $medname; ?>">
                            <span class="invalid-feedback"><?php echo $medname_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input name="medquantity" class="form-control <?php echo (!empty($medquantity_err)) ? 'is-invalid' : ''; ?>"><?php echo $medquantity; ?></textarea>
                            <span class="invalid-feedback"><?php echo $medquantity_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Expiration Date</label>
                            <input type="date" name="medexpdate" placeholder="date" class="form-control">
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
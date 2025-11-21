<?php
if (isset($_GET["error"])) {
    $error = $_GET["error"];

    // default
    $message = "An unknown error occurred.";
    $class   = "danger"; // red

    if ($error == 1) {
        $message = "Cannot delete: this record is being used (foreign key constraint).";
        $class   = "warning"; // yellow
    } elseif ($error == 2) {
        $message = "Already exist: can not create data.";
        $class   = "info"; // blue
    } elseif ($error == 3) {
        $message = "Already enrolled: this student is already enrolled in this subject.";
        $class   = "danger"; // red
    }elseif ($error == 4) {
        $message = "Does not exist: This student does not exist or not enrolled.";
        $class   = "warning"; // yellow
    }elseif ($error == 5) {
        $message = "Cannot delete: this record is being used (foreign key constraint).<br>Delete or Update to Null the Grade before deleting";
        $class   = "warning"; // yellow
    }elseif ($error == 6) {
        $message = "Payment error: Select Atleast one payment method.";
        $class   = "danger"; // yellow
    }elseif ($error == 7) {
        $message = "Payment error: Both payment method selected.<br>Select only one payment method.";
        $class   = "warning"; // yellow
    }elseif ($error == 8) {
        $message = "Payment Error: Gcash reference number is required.";
        $class   = "warning"; // yellow
    }

    echo "
    <div style='z-index: 1050;' class='container alert alert-$class alert-dismissible fade show position-absolute top-50 start-50 translate-middle w-25 text-center' role='alert'>
        <strong>Notice:</strong><br> $message
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    ";
}

if (isset($_GET["success"])){
    $success = $_GET["success"];

    // default
    $message = "Operation completed successfully.";
    $class   = "success"; // green

    if ($success == 1) {
        $message = "Successfully added.";
        $class   = "success"; // green
    } elseif ($success == 2) {
        $message = "Successfully updated.";
        $class   = "success"; // green
    } elseif ($success == 3) {
        $message = "Successfully deleted.";
        $class   = "success"; // green
    }

    echo "
    <div class='container alert alert-$class alert-dismissible fade show position-absolute top-50 start-50 translate-middle w-25 text-center' role='alert'>
        <strong>Success:</strong><br> $message
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    ";
}
?>

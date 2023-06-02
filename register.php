<?php

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jobtoday";

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $job = $_POST["job"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $gender = $_POST["gender"];
    $age = $_POST["age"];
    $qualification = $_POST["qualification"];
    $profession = $_POST["profession"];
    $district = $_POST["district"];
    $upazila = $_POST["upazila"];
    $experience = $_POST["experience"];

    // Check if phone number already exists
    $checkPhoneSql = "SELECT phone FROM jobdata WHERE phone = ?";
    $checkPhoneStmt = $conn->prepare($checkPhoneSql);
    $checkPhoneStmt->bind_param("s", $phone);
    $checkPhoneStmt->execute();
    $checkPhoneResult = $checkPhoneStmt->get_result();

    if ($checkPhoneResult->num_rows > 0) {
        echo "Phone number already exists";
        header("Location:exit.html");
        exit(); // Add an exit statement after redirecting to prevent further execution
    } else {
        // Prepare SQL statement
        $sql = "INSERT INTO jobdata (job, name, email, phone, gender, age, qualification, profession, district, upazila, experience) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Create a prepared statement
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters to the statement
            $stmt->bind_param("sssssssssss", $job, $name, $email, $phone, $gender, $age, $qualification, $profession, $district, $upazila, $experience);

            // Execute the statement
            if ($stmt->execute()) {
                echo "New record created successfully";
                header("Location:display.html");
                exit(); // Add an exit statement after redirecting to prevent further execution
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    // Close the check phone statement and result
    $checkPhoneStmt->close();
    $checkPhoneResult->close();
}

?>

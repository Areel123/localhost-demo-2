<?php
// import.php

require_once('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        // Check if the uploaded file is a CSV file
        $file_info = pathinfo($_FILES['csv_file']['name']);
        if (strtolower($file_info['extension']) !== 'csv') {
            echo "Error: Please upload a CSV file.";
            exit;
        }

        // Open the uploaded CSV file
        $file_handle = fopen($_FILES['csv_file']['tmp_name'], 'r');

        // Prepare and execute the SQL INSERT statement for each row in the CSV file
        $db = new DBConnection();
        $conn = $db->conn;

        while (($data = fgetcsv($file_handle, 1000, ',')) !== false) {
            $name = $data[0]; // Replace with appropriate column names from your CSV
            $email = $data[1];
            $contact = $data[2];
            $address = $data[3];

            $query = "INSERT INTO members (name, email, contact, address) 
                      VALUES ('$name', '$email', '$contact', '$address')";
            mysqli_query($conn, $query);
        }

        fclose($file_handle);
        echo "CSV data imported successfully!";
    } else {
        echo "Error uploading the CSV file.";
    }
}
?>

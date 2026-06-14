<?php
include("include/connect.php");

// Check if the form was submitted
if (isset($_POST['submit'])) {
    // Check if the file was uploaded
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == 0) {
        // Get the file path
        $file = $_FILES['csvFile']['tmp_name'];

        // Open the uploaded CSV file
        if (($handle = fopen($file, "r")) !== FALSE) {
            // Skip the header row
            fgetcsv($handle);

            // Prepare the SQL query
            $sql = "INSERT INTO units (unitname) VALUES (?)";
            $stmt = $conn->prepare($sql);

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Check if unitname is not empty
                if (!empty($data[0])) {
                    // Bind parameters and execute the query for each valid row
                    $stmt->bind_param("s", $data[0]);
                    $stmt->execute();
                } else {
                    echo "Skipping empty unitname in CSV.<br>";
                }
            }

            fclose($handle);
            echo "CSV data imported successfully!";
        } else {
            echo "Error opening the CSV file.";
        }
    } else {
        echo "No file uploaded or error with the file.";
    }
}
?>

<!-- File upload form -->
<form action="unitcsv.php" method="post" enctype="multipart/form-data">
    <input type="file" name="csvFile" required>
    <button type="submit" name="submit">Upload CSV</button>
</form>

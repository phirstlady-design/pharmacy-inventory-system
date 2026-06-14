<?php
include("include/connect.php");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['upload'])) {
    if ($_FILES['csv_file']['error'] === 0) {
        $file = $_FILES['csv_file']['tmp_name'];
        ini_set('auto_detect_line_endings', true);

        if (($handle = fopen($file, "r")) !== false) {
            $isFirstRow = true;
            $inserted = 0;
            $skipped = 0;

            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                // Debug: print row for inspection
                echo "<pre>"; print_r($row); echo "</pre>";

                if ($isFirstRow) {
                    $isFirstRow = false;
                    continue;
                }

                // Handle possible tab-delimited values in case comma doesn't work
                if (count($row) == 1 && strpos($row[0], "\t") !== false) {
                    $row = explode("\t", $row[0]);
                }

                // Check if unitname exists
                if (empty(trim($row[0]))) {
                    $skipped++;
                    continue;
                }

                $unitname = $conn->real_escape_string(trim($row[0]));
                $sql = "INSERT INTO units (unitname) VALUES ('$unitname')";
                if ($conn->query($sql)) {
                    $inserted++;
                } else {
                    echo "MySQL Error: " . $conn->error . "<br>";
                }
            }

            fclose($handle);
            echo "<br><strong>$inserted row(s) inserted.</strong>";
            echo "<br><strong>$skipped row(s) skipped.</strong>";
        } else {
            echo "Failed to open the file.";
        }
    } else {
        echo "Error uploading the file.";
    }
}

$conn->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV</title>
</head>
<body>
    <h1>Upload CSV File</h1>
    <form action="deptcsv.php" method="post" enctype="multipart/form-data">
        <input type="file" name="csv_file" accept=".csv" required>
        <button type="submit" name="upload">Upload</button>
    </form>
</body>
</html>


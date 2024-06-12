<?php
// Include database connection file
include '../include/koneksi.php';

// Function to delete data by ID
function deleteData($table, $id)
{
    global $conn;
    // SQL query to delete data
    $query = "DELETE FROM $table WHERE id = ?";
    // Prepare SQL statements
    $stmt = $conn->prepare($query);
    // Bind ID parameters
    $stmt->bind_param("i", $id);
    // Execute statements
    $stmt->execute();
    // Close statement
    $stmt->close();
}

// Function to delete files from local storage
function deleteFileFromStorage($fileName)
{
    $filePath = "../files/subtitles/movies/" . $fileName; // Adjust to your storage path
    if (file_exists($filePath)) {
        unlink($filePath); // Delete files from local storage if any
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get the file name from the database before deleting the entry
    $query = "SELECT url_sub FROM tbl_movie_subtitles WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($fileName);
    $stmt->fetch();
    $stmt->close();

    // Delete files from local storage
    deleteFileFromStorage($fileName);

    // Call the delete function to delete an entry from the database
    deleteData('tbl_movie_subtitles', $id);

    // Redirect to page after data deletion
    ?>
    <script type="text/javascript">
        window.location = "javascript:history.go(-1)";
    </script>
    <?php
}
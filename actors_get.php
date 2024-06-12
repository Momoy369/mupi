<?php

include 'include/koneksi.php';
include 'images/baseurl.php';

// Check if the AJAX request exists
if (isset($_POST['draw'])) {
    $draw = $_POST['draw'];
    $start = $_POST['start'];
    $length = $_POST['length'];
    $search = $_POST['search']['value'];

    // Query to calculate total data without filters
    $totalData = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbl_aktor"));

    // Basic query to retrieve data
    $query = "SELECT * FROM tbl_aktor";

    // If there are search criteria, add search conditions to the query
    if (!empty($search)) {
        $query .= " WHERE (nama_aktor LIKE '%$search%')";
    }

    // Calculate the total data after filtering
    $totalFiltered = mysqli_num_rows(mysqli_query($conn, $query));

    // Add limits and offsets to the query
    $query .= " ORDER BY id_aktor DESC LIMIT $length OFFSET $start";

    // Query execution
    $result = mysqli_query($conn, $query);

    // The data format that will be sent is a JSON response
    $data = array();
    $i = $start + 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = array(
            'index' => $i,
            'nama_aktor' => ucwords($row['nama_aktor']),
            'action' => '<a href="action/delete-actors?id_aktor=' . $row['id_aktor'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this actor?\')"><i class="fa fa-trash"></i></a>'
        );

        $i++;
    }

    // JSON Responses
    $response = array(
        'draw' => intval($draw),
        'recordsTotal' => $totalData,
        'recordsFiltered' => $totalFiltered,
        'data' => $data
    );

    // Set header as JSON
    header('Content-Type: application/json');
    // Encode the response to JSON format and display it
    echo json_encode($response);
} else {
    // If there is no AJAX request, redirect to the actors.php page
    header("Location: actors.php");
    exit();
}

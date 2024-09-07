<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadFile = $uploadDir . basename($_FILES['file']['name']);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        echo json_encode(['message' => 'File uploaded successfully!']);
    } else {
        echo json_encode(['message' => 'File upload failed.']);
    }
} else {
    echo json_encode(['message' => 'Invalid request method.']);
}
?>

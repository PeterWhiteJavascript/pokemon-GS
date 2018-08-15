<?php
$filename = $_POST['filename'];
$data = json_decode($_POST['data'], true);
if ($data === null) {
    http_response_code(400);
    die(json_encode(['error' => 'Invalid data']));
}

file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
echo json_encode($data);
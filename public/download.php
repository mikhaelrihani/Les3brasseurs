<?php
$file = $_GET['file'];
$filePath = __DIR__ . '/upload/' . basename($file);

if (file_exists($filePath)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; fileName="'.basename($file->getClientOriginalName()).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
    exit;
} else {
    http_response_code(404);
    echo "File not found.";
}
?>

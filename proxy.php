<?php
// Set CORS headers to allow cross-origin requests
header("Access-Control-Allow-Origin: *"); // For production, replace '*' with your GitHub Pages domain if possible.
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Ensure that only POST requests are processed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["error" => "Method Not Allowed. Use POST."]);
    exit;
}

// Read and decode the JSON data from the request body
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

// Verify that the product code was sent
if (!isset($data['code'])) {
    echo json_encode(["error" => "No product code provided"]);
    exit;
}

$code = $data['code'];

// Construct the URL to your validation script. 
// Replace the URL below with the correct path to your validation PHP script.
$validationUrl = 'https://uhidk.fwh.is/validate.php?code=' . urlencode($code);

// Use cURL to fetch the response from the validation script
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $validationUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$curlError = curl_error($ch);
curl_close($ch);

if ($response === false) {
    echo json_encode(["error" => "Error fetching validation data: " . $curlError]);
    exit;
}

// Output the response from the validation script (assumed to be JSON)
echo $response;
?>

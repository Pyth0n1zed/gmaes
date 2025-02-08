<?php
// Set CORS headers to allow cross-origin requests
header("Access-Control-Allow-Origin: *"); // For production, consider replacing '*' with your actual GitHub Pages domain.
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight (OPTIONS) requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["error" => "Method Not Allowed. Use POST."]);
    exit;
}

// Read the raw POST data and decode it from JSON
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

if (!isset($data['code'])) {
    // Return a JSON error if no product code was provided
    echo json_encode(["error" => "No product code provided"]);
    exit;
}

$code = $data['code'];

// Construct the URL for your validation script.
// IMPORTANT: Update the URL below to point to your actual validation script on InfinityFree.
$validationUrl = 'https://your-infinityfree-site.com/validate_code.php?code=' . urlencode($code);

// Use cURL to call the validation script
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $validationUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if ($response === false) {
    $curlError = curl_error($ch);
    curl_close($ch);
    echo json_encode(["error" => "cURL error: " . $curlError]);
    exit;
}

curl_close($ch);

// Ensure the response is returned with a JSON content type header
header("Content-Type: application/json");
echo $response;
?>

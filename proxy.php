<?php
// Set CORS headers so that requests from other origins (e.g., your GitHub Pages site) are allowed.
header("Access-Control-Allow-Origin: *"); // For security, you might replace '*' with your actual domain.
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
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

// Read the raw POST data and decode it as JSON
$rawData = file_get_contents('php://input');
$data = json_decode($rawData, true);

if (!isset($data['code'])) {
    echo json_encode(["error" => "No product code provided"]);
    exit;
}

$code = $data['code'];

// Construct the URL to your validation script.
// IMPORTANT: Update the URL below to point to your actual validation script on InfinityFree.
$validationUrl = 'https://your-infinityfree-site.com/validate_code.php?code=' . urlencode($code);

// Use cURL to fetch the response from your validation script.
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

// Ensure the response is returned as JSON.
header("Content-Type: application/json");
echo $response;
?>

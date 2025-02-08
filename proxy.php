<?php
// Set CORS headers
header("Access-Control-Allow-Origin: *");  // Allow all origins (you can replace '*' with your domain for more security)
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight (OPTIONS request)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);  // Preflight successful
    exit;
}

// Handle the POST request
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(['error' => 'Method Not Allowed. Use POST']);
    exit;
}

// Read the POST data
$data = json_decode(file_get_contents('php://input'), true);

// Check if the product code is provided
if (isset($data['code'])) {
    $code = $data['code'];  // Get the product code

    // URL to your validation PHP script on InfinityFree
    $validationUrl = 'https://your-infinityfree-site.com/validate_code.php';
    
    // Send the request using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $validationUrl . '?code=' . urlencode($code));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Return the response
    echo $response;
} else {
    echo json_encode(['error' => 'No product code provided']);
}
?>

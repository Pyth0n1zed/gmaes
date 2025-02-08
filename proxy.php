<?php
// Allow cross-origin requests
header("Access-Control-Allow-Origin: *");  // Allow all origins (replace '*' with your domain for security)
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight requests (OPTIONS request)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(['error' => 'Method Not Allowed. Use POST']);
    exit;
}

// Read the JSON data from the request body
$data = json_decode(file_get_contents('php://input'), true);

// Check if 'code' is provided in the request body
if (isset($data['code'])) {
    $code = $data['code'];  // Get the product code

    // URL to your validation PHP script on InfinityFree
    $validationUrl = 'https://your-infinityfree-site.com/validate_code.php';
    
    // Create the full URL with the product code parameter
    $validationUrl .= '?code=' . urlencode($code);

    // Use cURL to send the request to the validation script on InfinityFree
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $validationUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Return the response from the validation script to the client
    echo $response;
} else {
    // Return an error if no code was provided
    echo json_encode(['error' => 'No product code provided']);
}
?>

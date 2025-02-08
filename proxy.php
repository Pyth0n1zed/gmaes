header("Access-Control-Allow-Origin: *");  // Allow all origins (you can replace '*' with your domain for more security)
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Handle preflight request
    http_response_code(200);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);  // Read POST data

if (isset($data['code'])) {
    $code = $data['code'];  // Assuming you're sending a JSON object with the 'code' field
    $validationUrl = 'https://your-infinityfree-site.com/validate_code.php';  // URL to your validation script
    $response = file_get_contents($validationUrl . '?code=' . urlencode($code));
    echo $response;  // Return the result to the client
} else {
    echo json_encode(['error' => 'Code not provided']);
}

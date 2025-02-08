// proxy.php
$code = $_GET['code'];  // Get the product code
$validationUrl = 'https://uhidk.fwh.is/validate.php?code=' . urlencode($code);

// Send the request to the InfinityFree server and fetch the result
$response = file_get_contents($validationUrl);

// Return the response to the client (GitHub Pages)
echo $response;

<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// ======================
// SETTINGS
// ======================

define('API_KEY', 'toxicadminn');  // Change if you want
define('TARGET_API', 'https://pentestgpt-impds-api-finalapi.onrender.com/search-aadhaar');

// ======================
// API KEY CHECK
// ======================

$apikey = $_GET['apikey'] ?? '';

if ($apikey !== API_KEY) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid API Key",
        "developer" => "https://t.me/botadminshere",
        "credit" => "https://t.me/Toxicadminn",
        "private" => "https://t.me/+14rDlunTEzwwZGY1"
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

// ======================
// QUERY CHECK
// ======================

$aadhaar = $_GET['aadhaar'] ?? $_GET['query'] ?? '';

if (empty($aadhaar)) {
    echo json_encode([
        "success" => false,
        "message" => "Please provide aadhaar number",
        "example" => "?apikey=toxicadminn&aadhaar=123456789012",
        "developer" => "https://t.me/botadminshere",
        "credit" => "https://t.me/Toxicadminn",
        "private" => "https://t.me/+14rDlunTEzwwZGY1"
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

// Sanitize (only numbers)
$aadhaar = preg_replace('/[^0-9]/', '', $aadhaar);

if (strlen($aadhaar) !== 12) {
    echo json_encode([
        "success" => false,
        "message" => "Aadhaar must be 12 digits",
        "developer" => "https://t.me/botadminshere",
        "credit" => "https://t.me/Toxicadminn"
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

// ======================
// BUILD TARGET URL
// ======================

$url = TARGET_API . "?search=A&aadhaar=" . urlencode($aadhaar);

// ======================
// CURL REQUEST
// ======================

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT => 45,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// ======================
// ERROR HANDLING
// ======================

if ($response === false || $httpCode !== 200) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to fetch Aadhaar data",
        "http_code" => $httpCode,
        "developer" => "https://t.me/botadminshere",
        "credit" => "https://t.me/Toxicadminn"
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

// ======================
// FINAL OUTPUT
// ======================

$data = json_decode($response, true);

$output = [
    "success" => true,
    "developer" => "https://t.me/botadminshere",
    "credit" => "https://t.me/Toxicadminn",
    "private" => "https://t.me/+14rDlunTEzwwZGY1",
    "result" => $data
];

echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>

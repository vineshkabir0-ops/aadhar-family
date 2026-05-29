<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// ======================
// SETTINGS
// ======================

define('API_KEY', 'toxicadminn');  // change if you want
define('BASE_URL', 'https://pentestgpt-impds-api-finalapi.onrender.com/search-aadhaar');

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

$search = $_GET['search'] ?? 'A';
$aadhaar = $_GET['aadhaar'] ?? '';

if (empty($aadhaar)) {
    echo json_encode([
        "success" => false,
        "message" => "Please provide aadhaar number",
        "example" => "?apikey=toxicadminn&search=A&aadhaar=202372727238",
        "developer" => "https://t.me/botadminshere",
        "credit" => "https://t.me/Toxicadminn",
        "private" => "https://t.me/+14rDlunTEzwwZGY1"
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    exit;
}

// sanitize
$aadhaar = preg_replace('/[^0-9]/', '', $aadhaar);
$search  = preg_replace('/[^A-Za-z]/', '', $search);

// ======================
// TARGET URL
// ======================

$url = BASE_URL . "?search=" . urlencode($search) . "&aadhaar=" . urlencode($aadhaar);

// ======================
// CURL
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

if ($response === false || $httpCode !== 200) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to fetch data from source",
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

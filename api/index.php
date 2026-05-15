<?php
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// API expiry (change kar dena jab chahe)
$expiryDate = strtotime('2027-12-31'); // extended for you
$currentDate = time();

if ($currentDate > $expiryDate) {
    echo json_encode([
        "success" => false,
        "message" => "API Expired! Contact @Toxicadminn",
        "credit" => "@TOXICLAIMS",
        "channel" => "https://t.me/Toxicadminn"
    ]);
    exit;
}

$remainingDays = floor(($expiryDate - $currentDate) / 86400);

$aadhaar = $_GET['aadhaar'] ?? $_GET['number'] ?? null;

if (!$aadhaar) {
    echo json_encode([
        "success" => false,
        "message" => "Aadhaar number missing. Use ?aadhaar=XXXXXXXXXXXX",
        "credit" => "@TOXICLAIMS",
        "channel" => "https://t.me/Toxicadminn",
        "days_remaining" => $remainingDays
    ]);
    exit;
}

// Clean Aadhaar (only digits)
$aadhaar = preg_replace('/[^0-9]/', '', $aadhaar);

if (strlen($aadhaar) !== 12) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid Aadhaar format. Must be 12 digits.",
        "credit" => "@TOXICLAIMS"
    ]);
    exit;
}

$targetUrl = "https://aadharfam.onrender.com/full-search?aadhaar=" . $aadhaar;

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $targetUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_TIMEOUT => 45,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || $httpCode !== 200) {
    echo json_encode([
        "success" => false,
        "message" => "Failed to fetch from source (HTTP $httpCode)",
        "credit" => "@TOXICLAIMS",
        "channel" => "https://t.me/Toxicadminn"
    ]);
    exit;
}

$data = json_decode($response, true);

if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid response from aadharfam",
        "raw_response" => substr($response, 0, 500),
        "credit" => "@TOXICLAIMS"
    ]);
    exit;
}

// Final output
$output = [
    "success" => true,
    "credit" => "@TOXICLAIMS",
    "channel" => "https://t.me/Toxicadminn",
    "api_valid_until" => "Dec 31, 2027",
    "days_remaining" => $remainingDays,
    "aadhaar" => $aadhaar,
    "result" => $data
];

echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
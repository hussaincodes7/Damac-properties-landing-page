<?php
ob_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method");
    }

    // Debug logging
    error_log("=== BROCHURE FORM SUBMISSION ===");
    error_log("POST data: " . print_r($_POST, true));

    // Basic validation - check for firstName and lastName (without spaces)
    $required = ['Title', 'firstName', 'lastName', 'Email', 'Phone'];
    
    foreach ($required as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field]) || trim($_POST[$field]) === '') {
            error_log("Missing field: $field");
            throw new Exception("$field is required");
        }
    }
    
    if (!filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    // Database connection
    $db = new mysqli('localhost', 'root', '', 'damac');
    if ($db->connect_error) {
        throw new Exception("Database connection failed: " . $db->connect_error);
    }

    // Get IP address
    $ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    // Prepare data for insertion
    $data = [
        'title' => trim($_POST['Title']),
        'first_name' => trim($_POST['firstName']),
        'last_name' => trim($_POST['lastName']),
        'email' => trim($_POST['Email']),
        'phone' => trim($_POST['Phone']),
        'campaign_id' => trim($_POST['campaignId'] ?? ''),
        'utm_source' => trim($_POST['utmSource'] ?? ''),
        'utm_medium' => trim($_POST['utmMedium'] ?? ''),
        'utm_campaign' => trim($_POST['utmCampaign'] ?? ''),
        'web_source' => trim($_POST['webSource'] ?? ''),
        'ad_group' => trim($_POST['adGroup'] ?? ''),
        'campaign_name' => trim($_POST['campaignName'] ?? ''),
        'goal' => trim($_POST['goal'] ?? ''),
        'digital_source' => trim($_POST['digitalSource'] ?? ''),
        'channel_cluster' => trim($_POST['channelCluster'] ?? ''),
        'banner_size' => trim($_POST['bannerSize'] ?? ''),
        'keyword' => trim($_POST['keyword'] ?? ''),
        'placement' => trim($_POST['placement'] ?? ''),
        'ad_position' => trim($_POST['adPosition'] ?? ''),
        'match_type' => trim($_POST['matchType'] ?? ''),
        'network' => trim($_POST['network'] ?? ''),
        'bid_type' => trim($_POST['bidType'] ?? ''),
        'gclid' => trim($_POST['GCLID'] ?? ''),
        'fbclid' => trim($_POST['fbclid'] ?? ''),
        'lead_source' => trim($_POST['leadSource'] ?? 'digital'),
        'last_mile_conversion' => trim($_POST['lastMileConversion'] ?? 'instapageForm'),
        'device' => trim($_POST['device'] ?? ''),
        'project_name' => trim($_POST['projectName'] ?? ''),
        'os' => trim($_POST['os'] ?? ''),
        'resolution' => trim($_POST['resolution'] ?? ''),
        'browser' => trim($_POST['browser'] ?? ''),
        'time_spent_before_form_submit' => intval($_POST['timeSpentbeforeFormSubmit'] ?? 0),
        'ip_address' => $ip,
        'landing_page_url' => trim($_POST['landingPageURL'] ?? ''),
        'website_language' => trim($_POST['websiteLanguage'] ?? 'EN'),
        'country_name_sync' => trim($_POST['countryNameSync'] ?? ''),
        'city_sync' => trim($_POST['citySync'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'country_code_sync' => trim($_POST['countryCodeSync'] ?? ''),
        'country_code' => trim($_POST['countryCode'] ?? ''),
        'ga_client_id' => trim($_POST['ga_client_id'] ?? ''),
        'fbid' => trim($_POST['fbid'] ?? ''),
        'country' => trim($_POST['country'] ?? ''),
        'user_agent' => trim($_POST['user_agent'] ?? ''),
        'refid' => trim($_POST['refid'] ?? ''),
        'adid' => trim($_POST['adid'] ?? ''),
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Build query
    $columns = array_keys($data);
    $placeholders = implode(',', array_fill(0, count($data), '?'));
    $query = "INSERT INTO brochure_downloads (" . implode(',', $columns) . ") VALUES ($placeholders)";
    
    $stmt = $db->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $db->error);
    }

    // Bind parameters
    $types = str_repeat('s', count($data) - 1) . 's'; // All strings
    $stmt->bind_param($types, ...array_values($data));
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Brochure request submitted successfully';
        $response['download_id'] = $db->insert_id;
        error_log("Successfully inserted record with ID: " . $db->insert_id);
    } else {
        throw new Exception("Database error: " . $stmt->error);
    }

    $stmt->close();
    $db->close();

} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    error_log("Brochure Form Error: " . $e->getMessage());
}

ob_end_clean();
echo json_encode($response);
exit;
?>
<?php
header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

try {
    $required = ['Title', 'Email', 'Phone'];
    
    if (empty($_POST['First_Name']) && empty($_POST['First Name'])) {
        throw new Exception("First Name is required");
    }
    
    if (empty($_POST['Last_Name']) && empty($_POST['Last Name'])) {
        throw new Exception("Last Name is required");
    }
    
    foreach ($required as $field) {
        if (empty($_POST[$field]) || trim($_POST[$field]) === '') {
            throw new Exception("$field is required");
        }
    }
    
    if (!filter_var($_POST['Email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    $db = new mysqli('localhost', 'root', '', 'damac');
    if ($db->connect_error) {
        throw new Exception("Database connection failed: " . $db->connect_error);
    }

    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? 
          $_SERVER['HTTP_X_FORWARDED_FOR'] ?? 
          $_SERVER['HTTP_CLIENT_IP'] ?? 
          $_SERVER['REMOTE_ADDR'] ?? 
          '';
    $ip = trim(explode(',', $ip)[0]);

    $countryCode = '';
    $city = '';

    if (!empty($ip) && filter_var($ip, FILTER_VALIDATE_IP)) {
        $geoData = @file_get_contents("http://ip-api.com/json/$ip?fields=status,countryCode,city");
        if ($geoData !== false) {
            $geoData = json_decode($geoData, true);
            if ($geoData && $geoData['status'] == 'success') {
                $countryCode = strtoupper($geoData['countryCode'] ?? '');
                $city = $geoData['city'] ?? '';
            }
        }
    }

    $fieldMapping = [
        'Title' => 'title',
        'Email' => 'email',
        'Phone' => 'phone',
        'Comments' => 'comments',
        'campaignId' => 'campaign_id',
        'utmSource' => 'utm_source',
        'utmMedium' => 'utm_medium',
        'utmCampaign' => 'utm_campaign',
        'webSource' => 'web_source',
        'adGroup' => 'ad_group',
        'campaignName' => 'campaign_name',
        'goal' => 'goal',
        'digitalSource' => 'digital_source',
        'channelCluster' => 'channel_cluster',
        'bannerSize' => 'banner_size',
        'keyword' => 'keyword',
        'placement' => 'placement',
        'adPosition' => 'ad_position',
        'matchType' => 'match_type',
        'network' => 'network',
        'bidType' => 'bid_type',
        'GCLID' => 'gclid',
        'fbclid' => 'fbclid',
        'leadSource' => 'lead_source',
        'lastMileConversion' => 'last_mile_conversion',
        'device' => 'device',
        'projectName' => 'project_name',
        'os' => 'os',
        'resolution' => 'resolution',
        'browser' => 'browser',
        'timeSpentbeforeFormSubmit' => 'time_spent_before_form_submit',
        'landingPageURL' => 'landing_page_url',
        'websiteLanguage' => 'website_language',
        'countryNameSync' => 'country_name_sync',
        'citySync' => 'city_sync',
        'city' => 'city',
        'countryCodeSync' => 'country_code_sync',
        'countryCode' => 'country_code',
        'ga_client_id' => 'ga_client_id',
        'fbid' => 'fbid',
        'country' => 'country',
        'user_agent' => 'user_agent',
        'refid' => 'refid',
        'adid' => 'adid',
        'ipAddress' => 'ip_address'
    ];

    $columns = ['first_name', 'last_name'];
    $values = [
        !empty($_POST['First_Name']) ? trim($_POST['First_Name']) : trim($_POST['First Name'] ?? ''),
        !empty($_POST['Last_Name']) ? trim($_POST['Last_Name']) : trim($_POST['Last Name'] ?? '')
    ];
    $types = 'ss';

    $ipAdded = false;
    foreach ($fieldMapping as $postField => $dbColumn) {
        if ($dbColumn === 'ip_address') {
            $ipAdded = true;
        }
        
        $fieldValue = isset($_POST[$postField]) ? trim($_POST[$postField]) : '';
        
        if ($postField === 'ipAddress' && ($fieldValue === 'ip' || $fieldValue === 'auto' || empty($fieldValue))) {
            $fieldValue = $ip;
        }
        
        $columns[] = $dbColumn;
        $values[] = $fieldValue;
        $types .= 's';
    }

    if (!empty($countryCode)) {
        $columns[] = 'country_code';
        $values[] = $countryCode;
        $types .= 's';
    }

    if (!empty($city)) {
        $columns[] = 'city';
        $values[] = $city;
        $types .= 's';
    }

    if (!$ipAdded) {
        $columns[] = 'ip_address';
        $values[] = $ip;
        $types .= 's';
    }

    $columns[] = 'created_at';
    $values[] = date('Y-m-d H:i:s');
    $types .= 's';

    $placeholders = implode(',', array_fill(0, count($values), '?'));
    $query = "INSERT INTO leads (" . implode(',', $columns) . ") VALUES ($placeholders)";
    
    $stmt = $db->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $db->error);
    }

    $stmt->bind_param($types, ...$values);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Form submitted successfully';
    } else {
        throw new Exception("Database error: " . $stmt->error);
    }

    $stmt->close();
    $db->close();

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    error_log("Form Error: " . $e->getMessage());
    error_log("POST Data: " . print_r($_POST, true));
}

echo json_encode($response);
?>
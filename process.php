<?php
// Enable detailed error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Set content type to JSON
header('Content-Type: application/json');

// Allow CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Only POST requests are accepted.']);
    exit;
}

// Initialize response array
$response = ['success' => false, 'message' => ''];

try {
    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dama";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Check if table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'leads'");
    if ($tableCheck->num_rows == 0) {
        // Create table
        $createTable = "CREATE TABLE leads (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(10),
            first_name VARCHAR(100),
            last_name VARCHAR(100),
            email VARCHAR(255),
            phone VARCHAR(20),
            campaign_id VARCHAR(100),
            utm_source VARCHAR(100),
            utm_medium VARCHAR(100),
            utm_campaign VARCHAR(100),
            web_source VARCHAR(100),
            ad_group VARCHAR(100),
            campaign_name VARCHAR(100),
            goal VARCHAR(100),
            digital_source VARCHAR(100),
            channel_cluster VARCHAR(100),
            banner_size VARCHAR(100),
            keyword VARCHAR(100),
            placement VARCHAR(100),
            ad_position VARCHAR(100),
            match_type VARCHAR(100),
            network VARCHAR(100),
            bid_type VARCHAR(100),
            gclid VARCHAR(100),
            fbclid VARCHAR(100),
            lead_source VARCHAR(100),
            last_mile_conversion VARCHAR(100),
            device VARCHAR(50),
            project_name VARCHAR(100),
            os VARCHAR(100),
            resolution VARCHAR(50),
            browser TEXT,
            time_spent_before_form_submit VARCHAR(20),
            ip_address VARCHAR(45),
            landing_page_url TEXT,
            website_language VARCHAR(10),
            country_name_sync VARCHAR(100),
            city_sync VARCHAR(100),
            city VARCHAR(100),
            country_code_sync VARCHAR(10),
            country_code VARCHAR(10),
            ga_client_id VARCHAR(100),
            fbid VARCHAR(100),
            country VARCHAR(100),
            user_agent TEXT,
            refid VARCHAR(100),
            adid VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        if (!$conn->query($createTable)) {
            throw new Exception("Error creating table: " . $conn->error);
        }
    }

    // Get IP address
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';

    // Prepare data with null coalescing to handle empty values
    $title = $_POST['Title'] ?? '';
    $firstName = $_POST['First_Name'] ?? '';
    $lastName = $_POST['Last_Name'] ?? '';
    $email = $_POST['Email'] ?? '';
    $phone = $_POST['Phone'] ?? '';
    $campaignId = $_POST['campaignId'] ?? '';
    $utmSource = $_POST['utmSource'] ?? '';
    $utmMedium = $_POST['utmMedium'] ?? '';
    $utmCampaign = $_POST['utmCampaign'] ?? '';
    $webSource = $_POST['webSource'] ?? '';
    $adGroup = $_POST['adGroup'] ?? '';
    $campaignName = $_POST['campaignName'] ?? '';
    $goal = $_POST['goal'] ?? '';
    $digitalSource = $_POST['digitalSource'] ?? '';
    $channelCluster = $_POST['channelCluster'] ?? '';
    $bannerSize = $_POST['bannerSize'] ?? '';
    $keyword = $_POST['keyword'] ?? '';
    $placement = $_POST['placement'] ?? '';
    $adPosition = $_POST['adPosition'] ?? '';
    $matchType = $_POST['matchType'] ?? '';
    $network = $_POST['network'] ?? '';
    $bidType = $_POST['bidType'] ?? '';
    $gclid = $_POST['GCLID'] ?? '';
    $fbclid = $_POST['fbclid'] ?? '';
    $leadSource = $_POST['leadSource'] ?? '';
    $lastMileConversion = $_POST['lastMileConversion'] ?? '';
    $device = $_POST['device'] ?? '';
    $projectName = $_POST['projectName'] ?? '';
    $os = $_POST['os'] ?? '';
    $resolution = $_POST['resolution'] ?? '';
    $browser = $_POST['browser'] ?? '';
    $timeSpent = $_POST['timeSpentbeforeFormSubmit'] ?? '';
    $landingPageURL = $_POST['landingPageURL'] ?? '';
    $websiteLanguage = $_POST['websiteLanguage'] ?? '';
    $countryNameSync = $_POST['countryNameSync'] ?? '';
    $citySync = $_POST['citySync'] ?? '';
    $city = $_POST['city'] ?? '';
    $countryCodeSync = $_POST['countryCodeSync'] ?? '';
    $countryCode = $_POST['countryCode'] ?? '';
    $gaClientId = $_POST['ga_client_id'] ?? '';
    $fbid = $_POST['fbid'] ?? '';
    $country = $_POST['country'] ?? '';
    $userAgent = $_POST['user_agent'] ?? '';
    $refid = $_POST['refid'] ?? '';
    $adid = $_POST['adid'] ?? '';

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO leads (
        title, first_name, last_name, email, phone, campaign_id, utm_source, utm_medium, 
        utm_campaign, web_source, ad_group, campaign_name, goal, digital_source, 
        channel_cluster, banner_size, keyword, placement, ad_position, match_type, 
        network, bid_type, gclid, fbclid, lead_source, last_mile_conversion, device, 
        project_name, os, resolution, browser, time_spent_before_form_submit, ip_address, 
        landing_page_url, website_language, country_name_sync, city_sync, city, 
        country_code_sync, country_code, ga_client_id, fbid, country, user_agent, refid, adid
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssssssssssssssssssssssssssssssssssssssssss", 
        $title, $firstName, $lastName, $email, $phone, $campaignId, $utmSource, $utmMedium, 
        $utmCampaign, $webSource, $adGroup, $campaignName, $goal, $digitalSource, 
        $channelCluster, $bannerSize, $keyword, $placement, $adPosition, $matchType, 
        $network, $bidType, $gclid, $fbclid, $leadSource, $lastMileConversion, $device, 
        $projectName, $os, $resolution, $browser, $timeSpent, $ipAddress, $landingPageURL, 
        $websiteLanguage, $countryNameSync, $citySync, $city, $countryCodeSync, $countryCode, 
        $gaClientId, $fbid, $country, $userAgent, $refid, $adid
    );

    // Execute
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Form submitted successfully!';
        $response['record_id'] = $conn->insert_id;
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    // Log error for debugging
    error_log("Form submission error: " . $e->getMessage());
    
    // Return error response
    $response['message'] = "Error: " . $e->getMessage();
    http_response_code(500);
}

// Return JSON response
echo json_encode($response);
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $firstname = $_POST['firstname'] ?? '';
  $telephone = $_POST['telephone'] ?? '';
  // Validate form data
  header("Location: thank-you.html");
  if (empty($firstname) || empty($telephone)) {
    echo "<p style='color: red;'>שגיאה: יש למלא את כל השדות.</p>";
    exit();
  }

  // Validate Israeli phone number format
  if (!preg_match("/^(?:(?:(\+972|972)|0)(?:-)?(?:(?:(?:[23489]{1}\d{7})|[5]{1}\d{8})))$/", $telephone)) {
    echo "<p style='color: red;'>שגיאה: מספר טלפון לא תקין.</p>";
    exit();
  }

  // Select CRM: 'hubspot', 'zoho', 'bitrix24'
  $crm = ''; // Change this to 'zoho' or 'bitrix24' as needed
  // CRM credentials
  $hubspotApiKey = 'your_hubspot_api_key';
  $zohoAccessToken = 'your_zoho_access_token';
  $bitrix24ApiUrl = 'https://yourcompany.bitrix24.com/rest/1/your_bitrix24_api_key/crm.lead.add.json';

  // Sending lead based on CRM selection
  switch ($crm) {
    case 'hubspot':
      sendToHubSpot($firstname, $telephone, $hubspotApiKey);
      break;
    case 'zoho':
      sendToZoho($firstname, $telephone, $zohoAccessToken);
      break;
    case 'bitrix24':
      sendToBitrix24($firstname, $telephone, $bitrix24ApiUrl);
      break;
    default:
      echo "Invalid CRM selected.";
      exit();
  }

  // Redirect to the Thank You page upon success
  header("Location: thank-you.html");
  exit();
}

// Function to send lead to HubSpot
function sendToHubSpot($firstname, $telephone, $apiKey) {
  $url = 'https://api.hubapi.com/contacts/v1/contact/?hapikey=' . $apiKey;
  $postData = json_encode([
    'properties' => [
      ['property' => 'firstname', 'value' => $firstname],
      ['property' => 'phone', 'value' => $telephone]
    ]
  ]);

  $response = sendRequest($url, $postData);
  if (!$response) {
    echo "<p style='color: red;'>Error sending lead to HubSpot!</p>";
    exit();
  }
}

// Function to send lead to Zoho
function sendToZoho($firstname, $telephone, $accessToken) {
  $url = 'https://www.zohoapis.com/crm/v2/Leads';
  $postData = json_encode([
    'data' => [
      [
        'First_Name' => $firstname,
        'Phone' => $telephone
      ]
    ]
  ]);

  $headers = [
    'Authorization: Zoho-oauthtoken ' . $accessToken,
    'Content-Type: application/json'
  ];

  $response = sendRequest($url, $postData, $headers);
  if (!$response) {
    echo "<p style='color: red;'>Error sending lead to Zoho CRM!</p>";
    exit();
  }
}

// Function to send lead to Bitrix24
function sendToBitrix24($firstname, $telephone, $apiUrl) {
  $postData = [
    'fields' => [
      'TITLE' => 'Lead from Landing Page',
      'NAME' => $firstname,
      'PHONE' => [
        ['VALUE' => $telephone, 'VALUE_TYPE' => 'WORK']
      ]
    ]
  ];

  $response = sendRequest($apiUrl, json_encode($postData));
  if (!$response) {
    echo "<p style='color: red;'>Error sending lead to Bitrix24!</p>";
    exit();
  }
}

// Helper function to send the request via cURL
function sendRequest($url, $postData, $headers = ['Content-Type: application/json']) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
  $response = curl_exec($ch);
  curl_close($ch);
  return $response;
}
?>

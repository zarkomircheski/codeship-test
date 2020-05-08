<?php

define('WP_USE_THEMES', false);
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-blog-header.php';

// For AMP Form Submission Responses to be accepted, the following headers are required.
// https://www.ampproject.org/docs/fundamentals/amp-cors-requests
$moduleLocation = $_POST['module_location'] ? $_POST['module_location']: null;
$channel = $_POST['channel'] ? $_POST['channel'] : null;
$status = $_POST['status'] ? $_POST['status'] : null;

if ($_POST['amp']) {
    $domain_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    header("Content-type: application/json");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Origin: *.ampproject.org");
    header("AMP-Access-Control-Allow-Source-Origin: " . $domain_url);
    header("Access-Control-Expose-Headers: AMP-Access-Control-Allow-Source-Origin");
    $emailAddress = $_POST['ampEmailSubmit'];
    $moduleLocation = 'Amp';
    $channel = 'E';
    $status = 'N';
    if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array('errmsg'=>'There is some error while sending email!'));
        exit;
    }
} else {
    $emailAddress = $_POST['address'];
}
$data = array(
    "address" => $emailAddress,
    "channel" => $channel,
    "status" => $status
);
$data['demographics'] = array();
if ($_POST['referralsource']) {
    $data['sourceDescription'] = $_POST['referralsource'];
}
if ($_POST['signup_url']) {
    array_push($data['demographics'], "signup_url={$_POST['signup_url']}");
}
if ($_POST['parsely_uuid']) {
    array_push($data['demographics'], "parsely_uuid={$_POST['parsely_uuid']}");
}
if ($_POST['multi_variant']) {
    array_push($data['demographics'], "multi_variant={$_POST['multi_variant']}");
}
if ($moduleLocation !== null) {
    array_push($data['demographics'], "module_location={$moduleLocation}");
}
if ($_POST['expecting_date']) {
    array_push(
        $data['demographics'],
        "expecting_date={$_POST['expecting_date']}"
    );
}
if ($_POST['referrer_id'] && (int)$_POST['referrer_id'] !== 0) {
    array_push($data['demographics'], "referrer={$_POST['referrer_id']}");
}

$person = get_user_by('email', $data['address']);

$data_string = json_encode($data);

$curl = curl_init(sprintf(
    "https://api.postup.com/api/recipient?address=%s",
    $emailAddress
));

curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "authorization: Basic c2Vhbi53YWxzaEBmYXRoZXJseS5jb206OGNucV8lKVQkSi82XzsqOF5OPG4=",
        "cache-control: no-cache",
    ),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$resp = json_decode($response);

if (isset($resp[0])) {
    $send_data = "PUT";
    $curl_url_path = '/' . $resp[0]->recipientId;
} else {
    $send_data = "POST";
    $curl_url_path = '';
}

$ch = curl_init('https://api.postup.com/api/recipient' . $curl_url_path);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $send_data);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    array(
        "authorization: Basic c2Vhbi53YWxzaEBmYXRoZXJseS5jb206OGNucV8lKVQkSi82XzsqOF5OPG4=",
        "cache-control: no-cache",
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string)
    )
);

$result = curl_exec($ch);
$decoded_result = json_decode($result);
curl_close($ch);
http_response_code(200);
if (isset($decoded_result)) {
    $recipient_id = $decoded_result->recipientId;

    $listSub = sendCurlRequest(array(
        "listId" => isset($_POST['list_id']) ? $_POST['list_id'] : '1',
        "recipientId" => $recipient_id,
        "status" => "NORMAL"
    ), 'https://api.postup.com/api/listsubscription/');

    if (strpos($listSub, 'error') !== false) {
        if ($_POST['amp']) {
            http_response_code(404);
            echo json_encode(array('errmsg'=>'There is some error while subscribing to list!'));
            exit;
        } else {
            echo $listSub;
            exit;
        }
    }

    sendCurlRequest(array(
        'sendTemplateId' => '1',
        'recipients' => [array(
            'address' => $emailAddress,
            'tags' => [],
        )]
    ), 'https://api.postup.com/api/templatemailing');
}

echo $listSub;
exit;

function sendCurlRequest($postFields, $url)
{
    $url =  curl_init($url) ;
    $fields_string = json_encode($postFields);

    curl_setopt($url, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($url, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $url,
        CURLOPT_HTTPHEADER,
        array(
            "authorization: Basic c2Vhbi53YWxzaEBmYXRoZXJseS5jb206OGNucV8lKVQkSi82XzsqOF5OPG4=",
            "cache-control: no-cache",
            'Content-Type: application/json',
            'Content-Length: ' . strlen($fields_string)
        )
    );

    //execute post
    $result = curl_exec($url);
    curl_close($url);

    return $result;
}

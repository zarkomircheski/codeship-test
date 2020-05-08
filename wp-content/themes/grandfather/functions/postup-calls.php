<?php

/**
 *
 * If the field is set in the POST object, it will be sanitized
 * and pushed to the postup data object.
 *
 */
function fth_postup_sanitize(&$data, $field)
{
    if ($_POST['referralsource']) {
        $data['sourceDescription'] = filter_input(INPUT_POST, 'referralsource', FILTER_SANITIZE_STRING);
    }
    if (isset($_POST[$field])) {
        $data[$field] = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
    }
}

/**
 *
 * If a custom variable (demographic) is set in the POST object,
 * it will be pushed to the data object under the demographic array.
 *
 */
function fth_postup_add_to_demographics(&$data, $field)
{
    if (isset($_POST[$field])) {
        // First, check if the demographic key exists in
        // the data array and set it if it's missing.
        if (!isset($data['demographics'])) {
            $data['demographics'] = array();
        }
        $value = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING);
        array_push($data['demographics'], "{$field}={$value}");
    }
}


/**
 *
 * This function sends the recipient's email to postup and receives
 * a response object containing the recipient id if it exists.
 * @return $recipientId - Postup Id for the recipient.
 *
 */
function fth_postup_get_recipient($data)
{

  // Set all curl options
    $curl = curl_init(sprintf("https://api.postup.com/api/recipient?address=%s", $data['address']));

    $curlOptions = array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING       => "",
    CURLOPT_MAXREDIRS      => 10,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_CUSTOMREQUEST  => "GET",
    CURLOPT_HTTPHEADER     => array(
      "authorization: Basic d2lsbGlhbUB1c3Rlcml4LmNvbTpEOHhhb0tDanh3cHBrM3plKjtLWjZRVHc/",
      "cache-control: no-cache",
    ),
    );

    // Apply curl options
    curl_setopt_array($curl, $curlOptions);

    // Handle curl response
    $response = curl_exec($curl);
    $err      = curl_error($curl);
    curl_close($curl);

    // Return parsed JSON response
    $jsonResponse = json_decode($response);

    // return the recipient ID
    return isset($jsonResponse[0]) ? $jsonResponse[0]->recipientId : false;
}


/**
 *
 * This function takes an object of demographics and
 * adds it to the recipient profile on postup.
 * @return string raw JSON result from postup.
 *
 */
function fth_add_demographics_to_recipient($data, $curlUrlPath, $httpMethod)
{

  // Set up the endpoint and post body
    $dataString = json_encode($data);
    $curl = curl_init('https://api.postup.com/api/recipient' . $curlUrlPath);

    // Curl options
    curl_setopt_array($curl, array(
    CURLOPT_CUSTOMREQUEST  => $httpMethod,
    CURLOPT_POSTFIELDS     => $dataString,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => array(
      "authorization: Basic d2lsbGlhbUB1c3Rlcml4LmNvbTpEOHhhb0tDanh3cHBrM3plKjtLWjZRVHc/",
        "cache-control: no-cache",
        'Content-Type: application/json',
        'Content-Length: ' . strlen($dataString)
    )
    ));

    // Execute, get the result from Postup and parse it.
    $result  = curl_exec($curl);
    curl_close($curl);
    http_response_code(200);

    // return a raw JSON result
    return $result;
}

/**
 *
 * This function makes a request to Postup and adds recipient to a
 * list.
 * @return string raw response from postup.
 *
 */
function fth_add_recipient_to_list($recipientId, $listId)
{

  // Set up the post data with list and recipient information.
    $listData       = array(
    "listId"      => $listId,
    "recipientId" => $recipientId,
    "status"      => "NORMAL"
    );

    // Create a json string to send in post body
    $listDatastring = json_encode($listData);

    // Set up curl.
    $curl = curl_init('https://api.postup.com/api/listsubscription/');

    // Curl options.
    curl_setopt_array($curl, array(
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $listDatastring,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
      "authorization: Basic d2lsbGlhbUB1c3Rlcml4LmNvbTpEOHhhb0tDanh3cHBrM3plKjtLWjZRVHc/",
            "cache-control: no-cache",
            'Content-Type: application/json',
            'Content-Length: ' . strlen($listDatastring)
    )
    ));

    // Execute and get result from Postup.
    $result = curl_exec($curl);
    curl_close($curl);

    // Return the raw JSON
    return $result;
}

/**
 *
 * Postup ADD USER => uses admin-ajax.php
 * ======================================
 * This function sanitizes all POST input and creates a data object
 * to send to Postup.
 * It makes the following requests to Postup:
 *
 *    1. Get a recipient ID (fth_postup_get_recipient)
 *    2. Add demographic data to the recipient
 *    3. Add the user to a list.
 *
 *
 */
add_action('wp_ajax_fth_postup_add_user', 'fth_postup_add_user');
add_action('wp_ajax_nopriv_fth_postup_add_user', 'fth_postup_add_user');
function fth_postup_add_user()
{

  // Data object to send to Postup
    $data   = array();

    // Possible fields for the Postup object from the POST object
    $fields = array(
        'address', // REQUIRED
        'channel', // REQUIRED
        'status',
        'sourceDescription'
    );

    // Possible demographic fields for the postup object from the POST object
    $demographics = array(
        'full_account',
        'child_1_birthdate',
        'child_2_birthdate',
        'child_3_birthdate',
        'child_4_birthdate',
        'expecting_series',
        'expecting_date',
        'signup_url'
    );

    // Add fields to Postup object
    foreach ($fields as $field) {
        fth_postup_sanitize($data, $field);
    }

    // Add demographics fields to Postup object
    foreach ($demographics as $field) {
        fth_postup_add_to_demographics($data, $field);
    }

    // 1. curl
    // Sends email address to Postup and
    // receives Recipient ID on success
    $recipientId = fth_postup_get_recipient($data);

    // Determine whether to use PUT or POST for next curl.
    $curlUrlPath = $recipientId ? "/{$recipientId}" : "";
    $httpMethod  = $recipientId ? "PUT" : "POST";

    // 2. curl
    // Sends recipient Id to Postup and returns a decoded
    // result from the json response.
    // If there is no recipient id, a new one will be generated.
    $result = fth_add_demographics_to_recipient($data, $curlUrlPath, $httpMethod);
    $decodedResult = json_decode($result);


    // 3. curl
    // Adds the recipient to a mailing list on Postup
    if (isset($decodedResult)) {
        $recipientId = $decodedResult->recipientId;
        $listId = isset($_POST['expecting_series']) ? 2 : 1;
        $listResult = fth_add_recipient_to_list($recipientId, $listId);
    }

    // Return and exit
    echo isset($listResult) ? $listResult : $result;
    die;
}

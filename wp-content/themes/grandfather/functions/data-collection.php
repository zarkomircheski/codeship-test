<?php

function connect()
{
    $postgresUser = Fatherly\Config\FatherlyConfig::config()->get('postgresUser');
    $postgresPass = '';
    if (getenv('POSTGRES_SECRET_ACCESS_KEY')) {
        $postgresPass = getenv('POSTGRES_SECRET_ACCESS_KEY');
    } elseif (defined('POSTGRES_SECRET_KEY')) {
        $postgresPass = constant("POSTGRES_SECRET_KEY");
    }

    $postgresHost = Fatherly\Config\FatherlyConfig::config()->get('postgres_host');
    return(pg_connect('host='.$postgresHost.' dbname=analytics user='.$postgresUser.' password='.$postgresPass.' '));
}

function fth_ajax_data_collection()
{
    // Connect to the database
    $analytics = connect();

    date_default_timezone_set('America/New_York');
    $time = date('Y-m-d H:i:s');

    // verify data before inserting it

    // Postup id should be a number
    $recipId = $_POST['recipientID'];
    if ($recipId && is_numeric($recipId)) {
        $recipId = sanitize_text_field($recipId);
    } else {
        $recipId = 'NULL';
    }

    // Data type should only contain lowercase letters a-z
    $dataType = $_POST['type'];
    if (!preg_match('/[^a-z]/', $dataType)) {
        $type = sanitize_text_field($dataType);
    } else {
        echo false;
        wp_die();
    }

    // the uuid should consist of numbers letters and dashes with no spaces
    $uuid = $_POST['uuid'];
    if (!preg_match('/[^A-Za-z0-9-]+$/', $uuid)) {
        $uuid = sanitize_text_field($uuid);
    } else {
        $uuid = 'NULL';
    }

    // check to see if an email was passed
    $email = $_POST['email'];
    if (!(isset($email) && filter_var($email, FILTER_VALIDATE_EMAIL))) {
        $email = 'NULL';
    }

    // verify data object
    $data = $_POST['userData'];
    if ($dataType === 'children') {
        $verifiedData = array();
        if ($data !== 'no-kids') {
            foreach ($data as $item) {
                // dob should be a valid date
                if (DateTime::createFromFormat('Y-m-d', $item['dob']) !== false) {
                    $dob = $item['dob'];
                } else {
                    echo false;
                }

                // Gender needs to be one of these four options
                if ($item['gender'] && ($item['gender'] === 'male' || $item['gender'] === 'female' || $item['gender'] === 'notSure' || $item['gender'] === 'other')) {
                    $gender = $item['gender'];
                } else {
                    echo false;
                    wp_die();
                }

                array_push($verifiedData, '{dob: ' . $dob . ', gender: ' . $gender . ' }');
            }
        }
    } else if ($dataType === 'registry' || $dataType === 'iq') {
        $verifiedData =  new stdClass();
        $answer = $_POST['answer'];
        if ($answer) {
            $verifiedData->answer = sanitize_text_field($answer);
        }
        $question = $_POST['question'];
        if ($question) {
            $verifiedData->question = sanitize_text_field($question);
        }
        $moduleId = $_POST['moduleId'];
        if (is_numeric($moduleId)) {
            $verifiedData->module_id = sanitize_text_field($moduleId);
        }
    } else {
        echo false;
        wp_die();
    }

    // JSON encode object before inserting
    $verifiedData = json_encode($verifiedData);


    $result = pg_query($analytics, "INSERT INTO data_collection(time, uuid, recipId, type, data, email) VALUES ( '$time' ,'$uuid', $recipId, '$type', '$verifiedData', '$email');");

    if (!$result) {
        echo false;
        wp_die();
    } else {
        echo true;
        wp_die();
    }
}

function fth_ajax_get_answers()
{
    if (is_user_logged_in()) {
        $analytics = connect();
        $question = sanitize_text_field($_GET['question']);
        $result = pg_query_params($analytics, "SELECT data->>'answer' as answer FROM data_collection WHERE data->> 'question' = $1", array($question));

        $data = [];
        $count = 0;

        while ($row = pg_fetch_array($result)) {
            if ($row['answer'] !== null) {
                if ($data[$row['answer']]) {
                    $data[$row['answer']] = $data[$row['answer']] + 1;
                } else {
                    $data[$row['answer']] = 1;
                }
                $count++;
            }
        }


        if ($_GET['type'] === 'display') {
            // Display question
            echo '<h2 class="fatherly-iq-result">' . $question, '</h2>';

            // Show section categories
            echo '<table class="fatherly-iq-result-table"><tr><th>Answer</th><th>Number Of Answers</th><th>Percentage</th></tr>';

            // Display Answers
            foreach ($data as $key => $value) {
                $percent = 100 / $count * $value;
                echo '<tr><td>' . $key . '</td><td>' . $value . '</td><td>' . round($percent, 2) . '%</td></tr>';
            }

            // Close table and add return link
            echo '<tr><td>Total Answers</td><td colspan="2">' . $count . '</td></tr></table><a href="?page=pull-fatherly-iq" class="fatherly-iq-button">Select Another question</a>';
        } else {
            $list = array(
                array(str_replace(',', '', $question)),
                array('Answer', 'Number Of Answers'),
            );

            foreach ($data as $key => $value) {
                array_push($list, array(str_replace(',', '', $key), str_replace(',', '', $value)));
            }
            echo json_encode($list);
        }
    }
    wp_die();
}

add_action('wp_ajax_fth_ajax_data_collection', 'fth_ajax_data_collection');
add_action('wp_ajax_nopriv_fth_ajax_data_collection', 'fth_ajax_data_collection');

add_action('wp_ajax_fth_ajax_get_answers', 'fth_ajax_get_answers');
add_action('wp_ajax_nopriv_fth_ajax_get_answers', 'fth_ajax_get_answers');

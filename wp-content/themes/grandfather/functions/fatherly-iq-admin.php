<?php


function fatherly_iq()
{
    add_menu_page(
        __('Pull Fatherly IQ page', 'my-textdomain'),
        __('Pull Fatherly IQ', 'my-textdomain'),
        'manage_options',
        'pull-fatherly-iq',
        'fatherly_iq_admin',
        'dashicons-schedule',
        3
    );
}

add_action('admin_menu', 'fatherly_iq');


function fatherly_iq_admin()
{

    ?>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        .fatherly-iq-result-table tr:last-child {
            border-top: 2px solid;
        }

        .fatherly-iq-time {
            width: 80px;
        }

        .fatherly-iq-button {
            margin: 0 5px;
            width: 75px;
            border-radius: 5px;
            height: 30px;
            line-height: 28px;
            padding: 0 10px 0 16px;
            vertical-align: top;
        }

        .fatherly-iq-button:hover {
            background-color: #9e9e9e;
            cursor: pointer;
        }

        .fatherly-iq-csv {
            color: #555;
            border-color: #ccc;
            background: #f7f7f7;
            box-shadow: 0 1px 0 #ccc;
        }

        .fatherly-iq-display {
            width: 100px;
            background: #0085ba;
            border-color: #0073aa #006799 #006799;
            box-shadow: 0 1px 0 #006799;
            color: #fff;
        }

        .fatherly-iq-result {
            color: #0085ba;
            font-size: 22px;
        }
    </style>
    
    <?php

    // Connect to the database
    $postgresUser = Fatherly\Config\FatherlyConfig::config()->get('postgresUser');
    $postgresPass = '';
    if (getenv('POSTGRES_SECRET_ACCESS_KEY')) {
        $postgresPass = getenv('POSTGRES_SECRET_ACCESS_KEY');
    } elseif (defined('POSTGRES_SECRET_KEY')) {
        $postgresPass = constant("POSTGRES_SECRET_KEY");
    }

    $postgresHost = Fatherly\Config\FatherlyConfig::config()->get('postgres_host');
    $analytics = pg_connect('host='.$postgresHost.' dbname=analytics user='.$postgresUser.' password='.$postgresPass.' ');
    $result = pg_query($analytics, "SELECT * FROM (SELECT DISTINCT ON (data->>'question') id, time, data->>'question' FROM data_collection WHERE data->>'question' IS NOT NULL ORDER BY data->>'question', id ASC) AS questions ORDER BY id DESC;");

    echo '<h1> Pull Fatherly IQ</h1>';
    echo '<h3>Click on a button to the right of the question to get your results</h3>';

    if (!$result) {
        echo "query did not execute";
    }
    if (pg_num_rows($result) == 0) {
        echo "0 records";
    } else {
        echo '<div class="fatherly-iq"><table class="fatherly-iq-questions">';
        echo '<tr><th>Question</th><th>First Answer</th></tr>';
        while ($row = pg_fetch_array($result)) {
            if ($row[2] !== null) {
                echo '<tr class="fatherly-iq-question" data-question="' . $row[2]. '"><td class="fatherly-iq-question-title">' . $row[2]. '</td>';
                echo '<td class="fatherly-iq-time">'. $row[1] .'</td>';
                echo '<td><div class="fatherly-iq-button fatherly-iq-csv">Create CSV</div></td>';
                echo '<td><div class="fatherly-iq-button fatherly-iq-display">Display Answers</div></td></tr>';
            }
        }
        echo '</table></div>';
    }
}
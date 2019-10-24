<?php

$connection = new mysqli('localhost', 'root', '');

if (isset($_GET['offset'])) {
    $user = $connection->query("SELECT * FROM pyscanner.users WHERE api_token = '" . $connection->real_escape_string($_POST['token']) . "' AND verified = 1")->fetch_row();
    if (!is_null($user)) {
        $_GET['offset'] = $_GET['offset'] * 5;
//	$block = $connection->query("select distinct substring(substring(ip, 1, CHAR_LENGTH(ip) - LOCATE('.', REVERSE(ip))), 1, CHAR_LENGTH(substring(ip, 1, CHAR_LENGTH(ip) - LOCATE('.', REVERSE(ip)))) - LOCATE('.', REVERSE(substring(ip, 1, CHAR_LENGTH(ip) - LOCATE('.', REVERSE(ip)))))) as ip FROM pyscanner.services limit 1 offset ".$connection->real_escape_string($_GET['offset']))->fetch_row()[0];
        $block = $connection->query("select distinct substring(ip, 1, CHAR_LENGTH(ip) - LOCATE('.', REVERSE(ip))) as ip from pyscanner.services limit 1 offset " . $connection->real_escape_string($_GET['offset']))->fetch_row()[0];

        $block = explode(".", $block);
        echo $block[0] . "." . $block[1] . "." . ($block[2] - 1) . ".0/22";
    }
}

?>

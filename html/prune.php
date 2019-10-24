<?php

if (isset($_GET['block'])) {

    $connection = new mysqli('localhost', 'root', '');
    $user = $connection->query("SELECT * FROM pyscanner.users WHERE api_token = '" . $connection->real_escape_string($_GET['token']) . "' AND verified = 1")->fetch_row();
    if (!is_null($user)) {

        $block = explode('.', $_GET['block']);


        $connection->query("DELETE FROM pyscanner.hosts WHERE ip LIKE '" . $connection->real_escape_string($block['0'] . "." . $block[1] . "." . $block[2]) . "%' OR  ip LIKE '" . $connection->real_escape_string($block['0'] . "." . $block[1] . "." . ($block[2] + 1)) . "%' OR  ip LIKE '" . $connection->real_escape_string($block['0'] . "." . $block[1] . "." . ($block[2] + 2)) . "%' OR ");
        $connection->query("DELETE FROM pyscanner.services WHERE ip LIKE '" . $connection->real_escape_string($block['0'] . "." . $block[1] . "." . $block[2]) . "%' OR  ip LIKE '" . $connection->real_escape_string($block['0'] . "." . $block[1] . "." . ($block[2] + 1)) . "%' OR  ip LIKE '" . $connection->real_escape_string($block['0'] . "." . $block[1] . "." . ($block[2] + 2)) . "%' OR ");

    }
    $connection->close();
}
?>


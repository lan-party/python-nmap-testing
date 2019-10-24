<?php

if (isset($_POST['ip'])) {

    $connection = new mysqli('localhost', 'root', '');
    $user = $connection->query("SELECT * FROM pyscanner.users WHERE api_token = '" . $connection->real_escape_string($_POST['token']) . "' AND verified = 1")->fetch_row();
    if (!is_null($user)) {

        $update = $connection->query("SELECT * FROM pyscanner.hosts WHERE ip = '" . $connection->real_escape_string($_POST['ip']) . "'");

        if ($update->num_rows > 0) {
            if ($update->num_rows == 1) {
                $connection->query("UPDATE pyscanner.hosts SET whois = '" . $connection->real_escape_string($_POST['ip']) . "' WHERE id = " . $update->fetch_row()[0]);
            } else {
                $connection->query("DELECT FROM pyscanner.hosts WHERE ip = '" . $connection->real_escape_string($_POST['ip']) . "'");
                $connection->query("INSERT INTO pyscanner.hosts (ip, whois) VALUES ('" . $connection->real_escape_string($_POST['ip']) . "', '" . $connection->real_escape_string($_POST['whois']) . "')");
            }
        } else {
            $connection->query("INSERT INTO pyscanner.hosts (ip, whois) VALUES ('" . $connection->real_escape_string($_POST['ip']) . "', '" . $connection->real_escape_string($_POST['whois']) . "')");
        }

        if (isset($_POST['nmap'])) {
            $ports = json_decode($_POST['nmap']);
            if (is_null($ports)) {
                $ports = [$_POST['nmap']];
                $ports[0] = str_replace('[', '', $ports[0]);
                $ports[0] = str_replace(']', '', $ports[0]);
            }
            foreach ($ports as $port) {
                $porte = explode(";", $port);
                $connection->query("INSERT INTO pyscanner.services (ip, port, service, status, response, nmap_dump) VALUES ('" . $connection->real_escape_string($_POST['ip']) . "', " . $connection->real_escape_string($porte[4]) . ", '" . $connection->real_escape_string($porte[5]) . "', '" . $connection->real_escape_string($porte[6]) . "', '" . $connection->real_escape_string($port[12]) . "', '" . $port . "')");
            }
        }
    }
    $connection->close();
}
?>


<html>
<body>
<form action="" method="post">
    <div style="width: 300px;">
        <p><input type="text" style="width: 100%;" name="ip" placeholder="ip"/></p>
        <p><input type="text" style="width: 100%;" name="whois" placeholder="whois"/></p>
        <p><input type="text" style="width: 100%;" name="nmap" placeholder="nmap"/></p>
        <input type="hidden" name="formsumbit" value="true"/>
        <input type="submit" value="Add Host"/>
    </div>
</form>
</body>
</html>

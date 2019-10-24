<?php

$connection = new mysqli('localhost', 'root', '');
mysqli_set_charset($connection, "utf8");

if (isset($_POST['host'])) {
    $user = $connection->query("SELECT * FROM pyscanner.users WHERE loginkey = '".$connection->real_escape_string($_COOKIE['pyscannerkey'])."' AND verified = 1")->fetch_row();

    $query = "";
    if (isset($_POST['port']) && count($_POST['port']) > 0) {
        for ($i = 0; $i < count($_POST['port']); $i++) {
            if ($_POST['port'][$i] == "") {
                $query .= " JOIN (SELECT * FROM pyscanner.services WHERE status = '".$connection->real_escape_string($_POST['status'][$i])."'";
            } else {
                $query .= " JOIN (SELECT * FROM pyscanner.services WHERE port = ".$connection->real_escape_string($_POST['port'][$i])." AND status = '".$connection->real_escape_string($_POST['status'][$i])."'";
            }

            if ($_POST['resp'][$i] !== '') {
                $query .= " AND LOWER(FROM_BASE64(response)) LIKE LOWER('%".$connection->real_escape_string($_POST['resp'][$i])."%') ";
            }

            if ($_POST['service'][$i] !== 'any') {
                $query .= " AND service = '".$connection->real_escape_string($_POST['service'][$i])."'";
            }

            $query .= ") as s".$i." ON hosts.ip = s".$i.".ip";
        }
        $hosts = $connection->query("SELECT DISTINCT hosts.ip, hosts.id, hosts.whois, s0.check_date FROM pyscanner.hosts ".$query." WHERE LOWER(hosts.whois) LIKE '%asn_description\": \"".$connection->real_escape_string($_POST['isp'])."%' AND LOWER(hosts.whois) LIKE '%asn_country_code\": \"".$connection->real_escape_string($_POST['country'])."%' AND hosts.ip LIKE '%".$connection->real_escape_string($_POST['host'])."%' ORDER BY s0.check_date DESC LIMIT 250");
        $count = $connection->query("SELECT count(*) FROM pyscanner.hosts ".$query." WHERE LOWER(hosts.whois) LIKE '%asn_description\": \"".$connection->real_escape_string($_POST['isp'])."%' AND LOWER(hosts.whois) LIKE '%asn_country_code\": \"".$connection->real_escape_string($_POST['country'])."%' AND hosts.ip LIKE '%".$connection->real_escape_string($_POST['host'])."%'");
    } else {
        $hosts = $connection->query("SELECT DISTINCT hosts.ip, hosts.id, hosts.whois, services.check_date FROM pyscanner.hosts JOIN pyscanner.services ON hosts.ip = services.ip WHERE LOWER(hosts.whois) LIKE '%asn_description\": \"".$connection->real_escape_string($_POST['isp'])."%' AND LOWER(hosts.whois) LIKE '%asn_country_code\": \"".$connection->real_escape_string($_POST['country'])."%' AND hosts.ip LIKE '%".$connection->real_escape_string($_POST['host'])."%' ORDER BY services.check_date DESC LIMIT 250");
        $count = $connection->query("SELECT count(*) FROM pyscanner.hosts  WHERE LOWER(hosts.whois) LIKE '%asn_description\": \"".$connection->real_escape_string($_POST['isp'])."%' AND LOWER(hosts.whois) LIKE '%asn_country_code\": \"".$connection->real_escape_string($_POST['country'])."%' AND hosts.ip LIKE '%".$connection->real_escape_string($_POST['host'])."%'");
    }

    $html = "";
    if (isset($hosts) && count($hosts) > 0) {
        $html .= "<table class='table'><tr><th scope='col'>Host</th><th scope='col'>ISP</th><th scope='col'>Country</th><th>Check Date</th></tr>";
        foreach ($hosts as $host) {
            $host['whois'] = str_replace(' ', '', $host['whois']);
            $host['whois'] = str_replace("\n", '', $host['whois']);
            $whois = json_decode($host['whois'])[0];

            $html .= "<tr><td scope='row'><a onclick='showHost(".$host['id'].");return false;' href='' style='cursor: pointer' >".$host['ip']."</a></td><td style='white-space: nowrap; max-width: 250px; overflow: hidden; text-overflow: ellipsis;'>".$whois->asn_description."</td><td>".$whois->asn_country_code."</td><td>".$host['check_date']."</td></tr>";
        }
        $html .= "</table>";
    }


    header('Content-Type: application/json');
    echo json_encode(['count' => $count->fetch_row()[0], 'html' => $html]);
}

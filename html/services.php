<div style="text-align: left;">
<?php
$connection = new mysqli('localhost', 'root', '');

$host = $connection->query("SELECT * FROM pyscanner.hosts WHERE id = ".$_GET['id'])->fetch_assoc();
$services = $connection->query("SELECT * FROM pyscanner.services WHERE ip = '".$host['ip']."' ORDER BY check_date DESC");

echo "<h4>".$host['ip']."</h4>";
echo "<table class=\"table\"><tr><th>Port</th><th>Service</th><th>Status</th><th>Last Checked</th></tr>";
foreach ($services as $service) {
    if (strpos($service['service'], 'https') !== false) {
        echo "<tr><td>".$service['port']."</td><td>".$service['service']."</td><td>".$service['status']."</td><td>".$service['check_date']."</td><td><a target=\"_blank\" href=\"https://".$host['ip'].":".$service['port']."\">".$host['ip']."</a></td></tr>";
    } elseif (strpos($service['service'], 'http') !== false) {
        echo "<tr><td>".$service['port']."</td><td>".$service['service']."</td><td>".$service['status']."</td><td>".$service['check_date']."</td><td><a target=\"_blank\" href=\"http://".$host['ip'].":".$service['port']. "\">".$host['ip']."</a></td></tr>";
    } elseif ($service['service'] == 'ftp') {
        echo "<tr><td>".$service['port']."</td><td>".$service['service']."</td><td>".$service['status']."</td><td>".$service['check_date']."</td><td><a target=\"_blank\" href=\"ftp://".$host['ip'].":".$service['port']. "\">".$host['ip']."</a></td></tr>";
    } else {
        echo "<tr><td>".$service['port']."</td><td>".$service['service']."</td><td>".$service['status']."</td><td>".$service['check_date']."</td></tr>";
    }
}
echo "</table>";

echo $host['whois'];
?>
</div>

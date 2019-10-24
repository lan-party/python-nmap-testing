<?php
setcookie('pyscannerkey', null);

if (isset($_GET['returnto'])) {
    header('Location: '.$_GET['returnto']);
} else {
    header('Location: /');
}

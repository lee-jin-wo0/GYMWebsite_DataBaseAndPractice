<?php
$db = '
(DESCRIPTION =
    (ADDRESS_LIST =
        (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
    )
    (CONNECT_DATA =
        (SID = orcl)
    )
)';

$username = 'DB501_PROJ_G1';
$password = '1234';

$connect = oci_connect($username, $password, $db, 'AL32UTF8');

if (!$connect) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
?>

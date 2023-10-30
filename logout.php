<?php
    session_start();
    session_destroy();
    include_once("connectSQL.php");
    $sql = "DELETE from session where id_dn ='".$_SESSION["id"]."'";
    $result = $conn->query($sql);
    header("location: index.php");
?>
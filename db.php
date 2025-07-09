<?php

    $serverName = "sql313.hstn.me";
    $userName = "	mseet_39394308";
    $password = "Farm2Table";
    $dbName = "	mseet_39394308_farm2table";

    $conn = mysqli_connect($serverName, $userName, $password, $dbName);
    if (!$conn)
    {
        die("Connection failed: " . mysqli_connect_error());
    }

?>

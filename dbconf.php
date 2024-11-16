<?php

define('db_server',"localhost");
define('db_username',"root");
define('db_password',"");
define('db_name',"tuitioncentre");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    $link = mysqli_connect(db_server, db_username, db_password, db_name);

    // echo "Connected to the database sucessfully!";

} catch (mysqli_sql_exception $e) {
    
    echo "ERROR: Connection failed. " . $e->getMessage();
}

?>
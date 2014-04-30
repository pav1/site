<?php
    require_once ($_SERVER['DOCUMENT_ROOT']."/config/dbconfig.php");
    require_once ($_SERVER['DOCUMENT_ROOT']."/admin/Functions/database.php");

    // Create database connection
    $databaseConnection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($databaseConnection->connect_error)
    {
        die("Database connection failed: " . $databaseConnection->connect_error);
    }

    // Create tables if needed.
    prep_DB_content();
?>
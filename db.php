<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "blog"
);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

?>
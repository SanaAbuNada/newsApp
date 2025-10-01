<?php
$servername = "localhost";
$username   = "root";
$password   = "";       // غيّريها إذا عندك باسوورد
$dbname     = "news_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// اضبطي الترميز
$conn->set_charset("utf8mb4");

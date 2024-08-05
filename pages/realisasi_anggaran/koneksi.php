<?php
$hostname_koneksi = "localhost";
$database_koneksi = "sijalanctp";
$username_koneksi = "root";
$password_koneksi = "";
$koneksi = mysqli_connect($hostname_koneksi, $username_koneksi, $password_koneksi);
mysqli_select_db($koneksi, $database_koneksi);

if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($koneksi, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
  {
    $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($koneksi, $theValue) :
      mysqli_real_escape_string($koneksi, $theValue);

    switch ($theType) {
      case "text";
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "long":
      case "int":
        $theValue = ($theValue != "") ? intval($theValue) : "NULL";
        break;
      case "double":
        $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
        break;
      case "date":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "defined":
        $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
        break;
    }
    return $theValue;
  }
}

<?php
$id = $_GET['id'];
// Create database connection
$connect_db = mysqli_connect(localhost, promo, WuuqA3n8e0, promo_test2);
// Check connection
// sql to delete a record
$sql = "DELETE FROM Images WHERE id = $id";
mysqli_query($connect_db, $sql);

if (mysqli_query($connect_db, $sql)) {
    header("location:javascript://history.go(-1)");
} else {
    echo "Errow!";
  }
?>

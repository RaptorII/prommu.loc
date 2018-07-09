<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/theme/css/loadimg/style.css'); ?>
<?php
	// Create database connection
	$connect_db = mysqli_connect(localhost, promo, WuuqA3n8e0, promo_test2);
	$result = mysqli_query($connect_db, "SELECT * FROM images");




	//upload
	if (isset($_POST['upload'])) {
		$types= array('image/jpeg','image/png','image/gif');
		if(in_array($_FILES['image']['type'], $types)) {
		$image = $_FILES['image'] ['name'];
		$text = $_POST['text'];
		$target = "/images/applic/tmp/".basename($image);
		$sql = "INSERT INTO images (name, text) VALUES ('$image','$text')";
		mysqli_query($connect_db, $sql);
		move_uploaded_file($_FILES['image']['tmp_name'],$target);
		echo "<meta http-equiv='refresh' content='0'>";
	}else {echo 'Неверный формат';}
	}
?>

<div class="loadimg">
	<div class="row-loadimg">

			<form class='form' method='post' action='' enctype='multipart/form-data'>
					<input type='file' name='image'>
					<input class='form-text' type='text' name='text' placeholder="Введите имя картинки">
					<input type='submit' name='upload' value='Загрузить'>
					<input type="button" value="Обновить" onclick="window.location.reload()">
			</form>


<?php
	while ($row = mysqli_fetch_array($result)) {
		echo "<div class='content'>";
		echo "<div class='icon'>";
		echo "<a href='/images/applic/tmp/".$row['name']."' target=_blank>";
		echo "<img src='/images/applic/tmp/".$row['name']."' >";
		echo "</a>";
		echo "</div>";
		echo "<p>".$row['text']."</p>";
		echo "<a href='delete.php?id=".$row['id']."'>Удалить</a>";
		echo "</div>";
	}

?>
	</div>
</div>

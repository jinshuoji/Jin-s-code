<?php
//�f�[�^�x�[�X�ɐڑ�����(�~�b�V����4-1�j	
	$dsn = 'mysql:dbname=tbxxxxxxxdb;host=localhost';
	$user = 'tb-xxxxxx';
	$password = '5xxxxxxx';
	$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));


//�e�[�u�������(�~�b�V����4-2�j
	$sql = "CREATE TABLE IF NOT EXISTS MadeByJinShuoji"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "time DATETIME"
	.");";
	$stmt = $pdo->query($sql);

	
//���e�@�\

	$world="world";
	if(isset($_POST["send_message"])){
		if(!empty($_POST["editnumber"])){//�����ҏW�ԍ������M���ꂽ�灁�����ҏW�@�\�N��
			$editnumber=$_POST["editnumber"];
			$id=$editnumber;
			$sql = 'select * from MadeByJinShuoji';
			$stmt = $pdo->query($sql);
			foreach ($stmt as $row) {
				if($row['id']==$id){
					$name = trim($_POST['user_name']);
					$comment = trim($_POST['message']); //�ύX���������O�A�ύX�������R�����g�͎����Ō��߂邱��
					$DATETIME = new DateTime();
    					$DATETIME = $DATETIME->format('Y-m-d H:i:s');
					$sql = 'update MadeByJinShuoji set name=:name,comment=:comment,time=:time where id=:id';
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':name', $name, PDO::PARAM_STR);
					$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->bindValue(':time',$DATETIME,PDO::PARAM_STR);
					$stmt->execute();
					echo '�X�V�������܂����B'.'<br>';
				}else{}
			}
		}
		if(!empty($_POST["password1"])){
		$password1=$_POST["password1"];	
			if($password1 == $world){//�p�X���[�h����v����Ƃ��ɓ��e
				$DATETIME = new DateTime();
    				$DATETIME = $DATETIME->format('Y-m-d H:i:s');
				$sql = $pdo -> prepare("INSERT INTO MadeByJinShuoji (name, comment,time) VALUES (:name, :comment, :time)");
				$sql -> bindParam(':name', $name, PDO::PARAM_STR);
				$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
				$sql -> bindValue(':time',$DATETIME,PDO::PARAM_STR);
				$comment = trim($_POST['message']);
				$name = trim($_POST['user_name']);
				$sql -> execute();
				echo"���e���󂯕t���܂����B".'<br>';
			}else{echo"�p�X���[�h���Ⴂ�܂��B".'<br>';
			}
		}
	}




//�폜�@�\
	if(isset($_POST["delete_message"])){
		$id = $_POST["delete"];
		$password2=$_POST["password2"];
		if($password2 == $world){
			$sql = 'delete from MadeByJinShuoji where id=:id';//�폜
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			echo"�폜���܂����B".'<br>';
		}else{echo"�p�X���[�h���Ⴂ�܂��B".'<br>';
		}
	}



//�ҏW�t�H�[���ɓ��͂��ꂽ���̂��ĕ\������@�\
	if(!empty($_POST["edit"])){//�ҏW�ԍ��̑��M������Ƃ�
		$id=$_POST["edit"];
		$password3=$_POST["password3"];
		$sql = 'select * from MadeByJinShuoji';
		$stmt = $pdo->query($sql);
		foreach ($stmt as $row) {
			if($row['id']==$id){
				if($password3 == $world){
					$editname=$row['name'];
					$editcomment=$row['comment'];
					$editnumber=$row['id'];
				}else{
				echo"�p�X���[�h���Ⴂ�܂��B".'<br>';
				$editnumber="";
				$editname="";
				$editcomment="";
				}
			}else{
				$editnumber="";
				$editname="";
				$editcomment="";
			}
		}
	}

?>

<html>
<head>
<meta charset="utf-8">
<title>5-1����Ă݂�</title>
</head>
<body>
	<?php if(!empty($_POST["edit_message"])and!empty($_POST["password3"])and$_POST["password3"]==$world):?>
		<form action="" method="POST">
			���b�Z�[�W:<input type="text"name="message"value="<?php if(!empty($_POST["edit"])){echo $editcomment;}else{echo"";}?>"><br/>
			���[�U�[��:<input type="text" name="user_name"value="<?php if(!empty($_POST["edit"])){echo $editname;}else{echo"";}?>"><br/>
           		<input type="hidden" name="editnumber"value="<?php if(!empty($_POST["edit"])){echo $editnumber;}else{echo"";}?>">
     			<input type="submit" name="send_message"value="���e"><br/><br/>
		</form>

	<?php else:?>
 		<form action="" method="POST">
			���b�Z�[�W:<input type="text"name="message"value="<?php if(!empty($_POST["edit"])){echo $editcomment;}else{echo"";}?>"><br/>
			���[�U�[��:<input type="text" name="user_name"value="<?php if(!empty($_POST["edit"])){echo $editname;}else{echo"";}?>"><br/>
			�p�X���[�h:<input type="text" name="password1">
           		<input type="hidden" name="editnumber"value="<?php if(!empty($_POST["edit"])){echo $editnumber;}else{echo"";}?>">
    			<input type="submit" name="send_message"value="���e"><br/><br/>
		</form>

		<form action="" method="POST">
			�폜�Ώ۔ԍ�:<input type="text"name="delete"><br/>
			�p�X���[�h:�@<input type="text" name="password2">
 			<input type="submit" name="delete_message"value="�폜"><br/><br/>
		</form>

		<form action="" method="POST">	
			�ҏW�Ώ۔ԍ�:<input type="text"name="edit"><br/>
			�p�X���[�h:�@<input type="text" name="password3">
	 		<input type="submit" name="edit_message"value="�ҏW"><br/><br/>
		</form>
	<?php endif;?>
�ȉ��̓R�����g�ł��F<br/>
----------------------<br/>

</body>
</html>

<?php
//�\���@�\
	$sql = 'SELECT * FROM MadeByJinShuoji';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){//$row�̒��ɂ̓e�[�u���̃J������������
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].",";
        	echo $row['time'].'<br>';
		echo "<hr>";
	}
?>








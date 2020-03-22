<?php
//データベースに接続する(ミッション4-1）	
	$dsn = 'mysql:dbname=tbxxxxxxxdb;host=localhost';
	$user = 'tb-xxxxxx';
	$password = '5xxxxxxx';
	$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));


//テーブルを作る(ミッション4-2）
	$sql = "CREATE TABLE IF NOT EXISTS MadeByJinShuoji"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "time DATETIME"
	.");";
	$stmt = $pdo->query($sql);

	
//投稿機能

	$world="world";
	if(isset($_POST["send_message"])){
		if(!empty($_POST["editnumber"])){//もし編集番号が送信されたら＝＝＝編集機能起動
			$editnumber=$_POST["editnumber"];
			$id=$editnumber;
			$sql = 'select * from MadeByJinShuoji';
			$stmt = $pdo->query($sql);
			foreach ($stmt as $row) {
				if($row['id']==$id){
					$name = trim($_POST['user_name']);
					$comment = trim($_POST['message']); //変更したい名前、変更したいコメントは自分で決めること
					$DATETIME = new DateTime();
    					$DATETIME = $DATETIME->format('Y-m-d H:i:s');
					$sql = 'update MadeByJinShuoji set name=:name,comment=:comment,time=:time where id=:id';
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':name', $name, PDO::PARAM_STR);
					$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->bindValue(':time',$DATETIME,PDO::PARAM_STR);
					$stmt->execute();
					echo '更新完了しました。'.'<br>';
				}else{}
			}
		}
		if(!empty($_POST["password1"])){
		$password1=$_POST["password1"];	
			if($password1 == $world){//パスワードが一致するときに投稿
				$DATETIME = new DateTime();
    				$DATETIME = $DATETIME->format('Y-m-d H:i:s');
				$sql = $pdo -> prepare("INSERT INTO MadeByJinShuoji (name, comment,time) VALUES (:name, :comment, :time)");
				$sql -> bindParam(':name', $name, PDO::PARAM_STR);
				$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
				$sql -> bindValue(':time',$DATETIME,PDO::PARAM_STR);
				$comment = trim($_POST['message']);
				$name = trim($_POST['user_name']);
				$sql -> execute();
				echo"投稿を受け付けました。".'<br>';
			}else{echo"パスワードが違います。".'<br>';
			}
		}
	}




//削除機能
	if(isset($_POST["delete_message"])){
		$id = $_POST["delete"];
		$password2=$_POST["password2"];
		if($password2 == $world){
			$sql = 'delete from MadeByJinShuoji where id=:id';//削除
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			echo"削除しました。".'<br>';
		}else{echo"パスワードが違います。".'<br>';
		}
	}



//編集フォームに入力されたものを再表示する機能
	if(!empty($_POST["edit"])){//編集番号の送信があるとき
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
				echo"パスワードが違います。".'<br>';
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
<title>5-1やってみる</title>
</head>
<body>
	<?php if(!empty($_POST["edit_message"])and!empty($_POST["password3"])and$_POST["password3"]==$world):?>
		<form action="" method="POST">
			メッセージ:<input type="text"name="message"value="<?php if(!empty($_POST["edit"])){echo $editcomment;}else{echo"";}?>"><br/>
			ユーザー名:<input type="text" name="user_name"value="<?php if(!empty($_POST["edit"])){echo $editname;}else{echo"";}?>"><br/>
           		<input type="hidden" name="editnumber"value="<?php if(!empty($_POST["edit"])){echo $editnumber;}else{echo"";}?>">
     			<input type="submit" name="send_message"value="投稿"><br/><br/>
		</form>

	<?php else:?>
 		<form action="" method="POST">
			メッセージ:<input type="text"name="message"value="<?php if(!empty($_POST["edit"])){echo $editcomment;}else{echo"";}?>"><br/>
			ユーザー名:<input type="text" name="user_name"value="<?php if(!empty($_POST["edit"])){echo $editname;}else{echo"";}?>"><br/>
			パスワード:<input type="text" name="password1">
           		<input type="hidden" name="editnumber"value="<?php if(!empty($_POST["edit"])){echo $editnumber;}else{echo"";}?>">
    			<input type="submit" name="send_message"value="投稿"><br/><br/>
		</form>

		<form action="" method="POST">
			削除対象番号:<input type="text"name="delete"><br/>
			パスワード:　<input type="text" name="password2">
 			<input type="submit" name="delete_message"value="削除"><br/><br/>
		</form>

		<form action="" method="POST">	
			編集対象番号:<input type="text"name="edit"><br/>
			パスワード:　<input type="text" name="password3">
	 		<input type="submit" name="edit_message"value="編集"><br/><br/>
		</form>
	<?php endif;?>
以下はコメントです：<br/>
----------------------<br/>

</body>
</html>

<?php
//表示機能
	$sql = 'SELECT * FROM MadeByJinShuoji';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].",";
        	echo $row['time'].'<br>';
		echo "<hr>";
	}
?>








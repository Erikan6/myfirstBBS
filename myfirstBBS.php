<?php
//データベースへの接続
$dsn = 'データベース名';
$user = 'ユーザー名.';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password);

//テーブルの作成
$sql = "CREATE TABLE IF NOT EXISTS post"
." ("
."id INT,"
."name char(32),"
."comment TEXT,"
."pw TEXT"
.");";
$stmt = $pdo -> query($sql);

$id == null;
$name = null;
$comment = null;
$delNum = null;
$editNum = null;
$PW = null;
$delPW = null;
$editPW = null;

if(isset($_POST['name']) && isset($_POST['comment'])){
	if($_POST['name'] !="" && $_POST['comment'] !=""){
		$name = $_POST['name'];
		$comment = $_POST['comment'];
		
		//編集
		if(isset($_POST['editNum2']) && $_POST['editNum2'] != ""){
			$id = $_POST['editNum2'];
			if(isset($_POST['PW']) && $_POST['PW'] != ""){
				$pw = $_POST['PW'];
				$sql = 'SELECT * FROM post';
				$results = $pdo -> query($sql);
				foreach($results as $row){
					if($row['id'] == $id){
						if($row['pw'] == $pw){
							$sql = "update post set name='$name', comment='$comment' where id = $id";
							$result = $pdo -> query($sql);
						}
						else{echo "パスワードが違います。"; }
					}
					else{}
				}
			}
			else{echo "入力漏れがあります。"; }
		}
		//新規投稿
		else if(isset($_POST['PW'])){
			if($_POST['PW'] != ""){
				$PW = $_POST['PW'];
				
				//新規投稿内容をpostに追記
				$sort = 'id';
				$sql = 'SELECT * FROM post ORDER BY ' . $sort;
				$results = $pdo -> query($sql);
				foreach($results as $row){
					$id = $row['id'].',';
				}
				$id = $id + 1;
					
				$sql = $pdo -> prepare("INSERT INTO post (id, name, comment, pw) VALUES (:id,:name,:comment, :pw)");
				$sql -> bindValue(':id', $id, PDO::PARAM_INT);
				$sql -> bindParam(':name', $name, PDO::PARAM_STR);
				$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
				$sql -> bindParam(':pw', $PW, PDO::PARAM_STR);
				$sql -> execute();
							
			}
			else{ echo "パスワードを入力してください。"; }
		}
		else{}
	}
	else{ echo "入力漏れがあります。";}
}
else{}
	
$name = "名前";
$comment = "コメント";

//編集番号の取得とformへの表示
if(isset($_POST['editNum']) && isset($_POST['editPW'])){
	if($_POST['editNum'] != "" && $_POST['editPW'] != ""){
		$editNum = $_POST['editNum'];
		$editPW = $_POST['editPW'];
		
		//編集番号のpwを取り出す
		$sql = 'SELECT * FROM post';
		$results = $pdo -> query($sql);
		foreach($results as $row){
			if($row['id'] == $editNum){
				//入力されたパスワードが取得したパスワードと同じとき、投稿内容を表示する
				if($row['pw'] == $editPW){
							$name = $row['name'];
							$comment = $row['comment'];
				}
				else{ echo "パスワードが違います。"; }
			}
			else{}
		}
	}
	else{ echo "入力漏れがあります。"; }
}
else{}

	
//削除機能
if(isset($_POST['delNum']) && isset($_POST['delPW'])){
	if($_POST['delNum'] != "" && $_POST['delPW'] != ""){
		$delNum = $_POST['delNum'];
		$delPW = $_POST['delPW'];
		//PWチェックとPW削除
		$sql = 'SELECT * FROM post';
		$results = $pdo -> query($sql);
		foreach($results as $row){
			if($row['id'] == $delNum){
				if($row['pw'] == $delPW){
					//postから投稿内容を削除。
					$sql = "delete from post where id=$delNum";
					$result = $pdo -> query($sql);
				}
				else{ echo "パスワードが違います。"; }
			}
			else{}
		}
	}
	else { echo "入力漏れがあります。"; }
}
else{}
?>

<!DOCTYPE html>
<html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>けいじばん</title>
</head>
<body>
<h2>けいじばん</h2>

<h4>＊送信フォーム＊</h4>
<p>新規投稿: 名前、コメント、パスワードを入力して送信してください。<br>
編集: 編集番号指定フォームから編集したい投稿の番号を入力すると、その投稿の名前、コメントが表示されます。編集したら送信すると上書き保存されます。</p>
<form action=myfirstBBS.php method=post>
<p>名前: <input type = "text" name="name" value = "<?=$name?>"></p>
<p>コメント: <input type = "text" name="comment" value = "<?=$comment?>"></p>
<p>パスワード: <input type = "text" name="PW" ></p>
<p><input type = "hidden" name="editNum2" value = "<?=$editNum?>"></p>
<p><input type = "submit" value = "送信" /></p>
</form>
<br>
<h4>＊編集番号指定フォーム＊</h4>
<p>編集したい投稿の番号(一番左端)と投稿時に設定したパスワードを入力してください。編集ボタンを押すと投稿内容が送信フォームに表示され、編集できるようになります。</p>
<form action=myfirstBBS.php method=post>
<p>編集対象番号: <input type = "text" name="editNum" ></p>
<p>パスワード: <input type = "text" name="editPW" ></p>
<p><input type = "submit" value = "編集" /></p>
</form>
<br>
<h4>＊削除フォーム＊</h4>
<p>削除したい投稿の番号(一番左端)を入力してください。投稿時に設定したパスワードを入れて削除ボタンを押すと、投稿は削除されます。</p>
<form action=myfirstBBS.php method=post>
<p>削除対象番号: <input type = "text" name="delNum" ></p>
<p>パスワード: <input type = "text" name="delPW"></p>
<p><input type = "submit" value = "削除" /></p>
</form>
<br>
<h3>↓書き込み↓</h3>
</body>
</html>

<?php
//データを表示
$sort = 'id';
$sql = 'SELECT * FROM post ORDER BY ' . $sort;
$results = $pdo -> query($sql);
foreach($results as $row){
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].'<br>';
}
?>

<!-- この２チャンネルクローンは現在間違えてデータベース名をbooklistにしてしまっています -->
<?php
error_reporting(-1);
ini_set('display_errors', 'On');




$username = 'root';
    $password = 'root';
    // PDO のインスタンスを生成して、MySQLサーバに接続
    $database = new PDO('mysql:host=localhost;dbname=booklist;charset=UTF8;', $username, $password);

    // フォームからユーザーからのコメントが送信されていればデータベースに保存する
    if (array_key_exists('name', $_POST) && array_key_exists('content', $_POST)) {
        // 実行するSQLを作成
        $sql = 'insert into contents (name, content) values (:name, :content)';
        // ユーザ入力に依存するSQLを実行するので、セキュリティ対策をする
        $statement = $database->prepare($sql);
        // ユーザ入力データ($_POST['book_title'])をVALUES(?)の?の部分に代入する
        $statement->bindParam(':name', $_POST['name']);
        $statement->bindParam(':content', $_POST['content']);
        
        // SQL文を実行する
        $statement->execute();
        
        // ステートメントを破棄する
        $statement = null;
        
    }
    if (array_key_exists('del', $_POST)) {
     // 削除よう
    $del = 'delete from booklist.contents';
    $statement_two = $database->prepare($del);
    $statement_two->bindParam(':delete', $_POST['delete']);
    $statement_two->execute();
    $statement_two = null;
    echo "全削除が完了しました！";
    }


    // 一覧にするSQLを指定
    $sql = 'SELECT * FROM contents ORDER BY created_at DESC';
    // SQLを実行する
    $statement = $database->query($sql);
    // 結果レコード（ステートメントオブジェクト）を配列に変換する
    $records = $statement->fetchAll();

    // ステートメントを破棄する
    $statement = null;
    // MySQLを使った処理が終わると、接続は不要なので切断する
    $database = null;
?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Booklist</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    </head>
    <body class="mt-4">
        <div class="container">
            <h1><a href="index.php">卍ちゃんねる</a></h1>
            
            <h2>好きなことを喋ろう</h2>
            <form action="index.php" method="POST" class="form-inline mb-2">
                <div class="form-group mr-2">
                    <label>自分の名前：</label><input type="text" name="name" class="form-control" placeholder="名無しさん" required></p>
                    <lable>コメント：</lable><input type="text" name="content" class="form-control" placeholder="コメント" required>
                </div>
                <button type="submit" name="submit_add_book" class="btn btn-primary">登録</button>
            </form>
            <p></p>
            <form action="index.php" method="post">
    <input type="submit" name="del" value="全削除" >
</form>
            <hr />
            
            <h2>みんなのコメント</h2>
            <ul>
<?php
    if ($records) {
        foreach ($records as $record) {
            $name = $record['name'];
            $content = $record['content'];
            $time = $record['created_at'];
            ?>
            <table style="border-collapse: collapse; width: 100%;" border="1">
<tbody>
<tr>
<th>名前</th>
<td><?php print htmlspecialchars($name, ENT_QUOTES, "UTF-8"); ?> </td>
</tr>
<tr>
<th>コメント</th>
<td><?php print htmlspecialchars($content, ENT_QUOTES, "UTF-8"); ?></td>
</tr>
<tr>
<th>時間</th>
<td><?php print htmlspecialchars($time, ENT_QUOTES, "UTF-8"); ?> </td>
</tr>
</tbody>
</table>
<br>
<br>
<?php
        }
    }else {
        echo "現在は何も投稿されていません。";
    }
?>
            </ul>
        </div>
        
        <!-- BootstrapなどのJavaScript -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.2.0/js/all.js" integrity="sha384-4oV5EgaV02iISL2ban6c/RmotsABqE4yZxZLcYMAdG7FAPsyHYAPpywE9PJo+Khy" crossorigin="anonymous"></script>
    </body>
</html>

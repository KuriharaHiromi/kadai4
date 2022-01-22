<?php
//require_once('index.php');
define( 'FILENAME', './message.txt');
define( 'FILENAME2', './message2.txt');

// 変数の初期化　※nullで値を空にしておき、存在しない変数を読んだり、意図しない挙動を防ぐため
$data2 = null;
$file_handle2 = null;
$comment = array();
$comment_array = array();
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();          




if( $file_handle = fopen( FILENAME,'r') ) {//ファイル名かつパスを指定して開いて、読み込みをする。"r"が読み込み機能
    while( $data = fgets($file_handle) ){//fgetsで$file_handleのファイルから1行ずつデータを込むループ
        $split_data = preg_split( '/\,/', $data);//preg_splitで文字列を分割して配列にする、今回は'で分割、\は,を文字として認識するために使用

        $message = array(//連想配列でそれぞれのデータを入れる
            'view_name' => $split_data[0],//投稿者の部分
            'message' => $split_data[1],//記事の内容
            'file_id' => $split_data[2],//ユニークID
        );
    
        array_unshift( $message_array, $message);//array_unshiftで$message_arrayに$messageの中身を先頭に追加、
        

        //echo $data . "<br>";//$dataを改行して出力※受け取ったタイトル、記事の内容の確認で使用

    }
    // ファイルを閉じる
    fclose( $file_handle);
}


//下は記事に対するコメントのファイルの書き込み、保存
if( $file_handle2 = fopen( FILENAME2, "a") ) {
	// 書き込むデータを作成
    $data2 = $_POST['comment']."\n";

	
    // 書き込み
    fwrite( $file_handle2, $data2);
    // ファイルを閉じる
    fclose( $file_handle2);
}

if( $file_handle2 = fopen( FILENAME2,'r') ) {
    while( $data2 = fgets($file_handle2) ){
           array_unshift( $comment_array, $data2);
    }
    // ファイルを閉じる
    fclose( $file_handle2);

}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>edit.php</title>
</head>
<body>
<a href="http://localhost/">Laravel News</a><!--トップへのリンク-->
<section>
<?php if( !empty($message_array)): ?><!--$message_arrayの中身が空でなければ-->
<?php foreach( $message_array as $value ): ?><!--$message_arrayから入力されたデータを取り出し$valueに入れる-->
<article>
    <div class="info">
        <h2><?php echo $value['view_name']; ?></h2><!--でview_name(タイトル)を出力-->
    </div>
    <p><?php  echo $value['message']; ?></p><!--<ｐ>でmessage(記事)を出力-->
    <p><?php echo $value['file_id']; ?></p><!--ユニークIDの確認用 -->
    <hr><!--下線部-->
</article> 
<?php var_dump($value['view_name']); ?> 
<?php endforeach; ?>
<?php endif; ?>    
</section>
<!--下はコメントの投稿ボタンの作成-->
<form method="post">
	<div>
		<label for="comment"></label>
		<textarea id="comment" name="comment"></textarea>
	</div>
	<input type="submit" name="btn_submit" value="コメント">
</form>
<hr>
<!-- <section>
//<?php if( !empty($comment_array) ): ?>
<?php foreach( $comment_array as $value ): ?>
<article>
    <p><?php echo $value['comment']; ?></p>
    <hr>
</article>
<?php endforeach; ?>
<?php endif; ?>
</section>
</body>
</html> -->


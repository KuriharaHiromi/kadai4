<?php
//require_once('index.php');
define( 'FILENAME', './message.txt');
define( 'FILENAME2', './comment.txt');

// 変数の初期化　※nullで値を空にしておき、存在しない変数を読んだり、意図しない挙動を防ぐため
$data2 = null;
$file_handle2 = null;
$comment = array();
$comment_array = array();
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();      


if(isset($_GET['id'])){
    $article_id = $_GET['id'];
 }


if( $file_handle = fopen( FILENAME,'r')) {//ファイル名かつパスを指定して開いて、読み込みをする。"r"が読み込み機能
    while( $data = fgets($file_handle) ){//fgetsで$file_handleのファイルから1行ずつデータを込むループ
        $split_data = preg_split( '/\,/', $data);//preg_splitで文字列を分割して配列にする、今回は'で分割、\は,を文字として認識するために使用
        $message = array(//連想配列でそれぞれのデータを入れる
            'view_name' => $split_data[0],//投稿者の部分
            'message' => $split_data[1],//記事の内容
            'file_id' => $split_data[2],//ユニークID
        );
    
        array_unshift($message_array, $message);//array_unshiftで$message_arrayに$messageの中身を先頭に追加、
        
        
        
        //echo $data . "<br>";//$dataを改行して出力※受け取ったタイトル、記事の内容の確認で使用

    }
    // ファイルを閉じる
    fclose( $file_handle);
}

$count=count($message_array);

$i=0;
while($i<$count){
    $article = $message_array[$i];
    $i++;
    if($article_id == $article['file_id']){
        break;
    }    
}   







//下は記事に対するコメントのファイルの書き込み、保存
if( $file_handle2 = fopen( FILENAME2, "a") ) {
	// 書き込むデータを作成
    $data2 = $_POST['comment'].",".$_POST['comment_id'].","."\n";
    // 書き込み
    fwrite( $file_handle2, $data2);
    // ファイルを閉じる
    fclose( $file_handle2);
}



if( $file_handle2 = fopen( FILENAME2,'r') ) {
    while( $data2 = fgets($file_handle2) ){
        $split_data2 = preg_split( '/\,/', $data2);
        $comment = array(
           'comment' => $split_data2[0],
           'comment_id' => $split_data2[1],
        );
        array_unshift($comment_array, $comment);
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
<article>
    <div class="info">
        <h2><?php echo $article['view_name']; ?></h2><!--でview_name(タイトル)を出力-->
    </div>
    <p><?php  echo $article['message']; ?></p><!--<ｐ>でmessage(記事)を出力-->
    <p><?php echo $article['file_id']; ?></p><!--ユニークIDの確認用 -->
    <hr><!--下線部-->
</article> 
</section>
<!--下はコメントの投稿ボタンの作成-->
<form method="post">
	<div>
		<label for="comment"></label>
		<textarea id="comment" name="comment"></textarea>
	</div>
	<input type="submit" name="btn_submit" value="コメント">
    <input id="comment_id" type="hidden" name="comment_id" value="<?php echo uniqid();?>">
</form>
<hr>
<section>
<?php if( !empty($comment_array) ): ?><!--$message_arrayの中身が空でなければ-->
<?php $i=0;?>
<?php while( isset($comment_array[$i])):?>
<?php    $value = $comment_array[$i]; ?>
<?php      $i++; ?>
<article>
    <p><?php echo $value['comment']; ?></p>
    <hr>
</article>
<?php endwhile; ?>
<?php endif; ?>
</section>
</body>
</html>


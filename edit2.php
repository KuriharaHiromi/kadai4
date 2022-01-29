<?php
//require_once('index.php');
define( 'FILENAME', './message.txt'); //タイトル、記事、親IDのファイルと紐づいた定数
define( 'FILENAME2', './comment.txt');//コメント、親ID、子IDのファイルと紐づいた定数

// 変数の初期化　※nullで値を空にしておき、存在しない変数を読んだり、意図しない挙動を防ぐため
$data2 = null;
$file_handle2 = null;
$comment = array();
$comments = array();
$comment_array = array();
$comment_value = array();
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();  
$error_message = array();//未入力の内容と配列    

//記事のページのURLのパラメータ(IDの部分)を読み込む処理
//issetで変数の中身に値があれば変数$article_idに取得した値(ID)を代入
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




if( !empty($_POST['btn_submit']) ) {

    if( empty($_POST['comment']) ) {
        $error_message[] = 'コメントは必須です。';
    }
    if( empty($_POST['comment']) ) {//入力した記事の内容が空だったら
        $error_message[] = 'コメントは必須です。';//$error_message[]に「コメントは必須です。」が加えられます。
    }
    if( empty($error_message) ) {//$error_message)が空だったら以下(26~34)の動作は行われる。


//下は記事に対するコメントのファイルの書き込み、保存
        if( $file_handle2 = fopen( FILENAME2, "a") ) {
	        // 書き込むデータを作成
            $data2 = $_POST['comment'].",".$_POST['comment_id'].",".$_POST['article_id'].","."\n";
            // 書き込み
            fwrite( $file_handle2, $data2);
            // ファイルを閉じる
            fclose( $file_handle2);
        }
    }
}


if( $file_handle2 = fopen( FILENAME2,'r') ) {
    while( $data2 = fgets($file_handle2) ){
        $split_data2 = preg_split( '/\,/', $data2);
        $comments = array(
           'comment' => $split_data2[0],
           'comment_id' => $split_data2[1],
           'article_id' => $split_data2[2],

        );
        array_unshift($comment_array, $comments);
    }
    // ファイルを閉じる
    fclose( $file_handle2);

}


$count_comment = count($comment_array);
$i=0;
while($i<$count_comment){
    if($article_id == $comment_array[$i]['article_id']){
        array_unshift($comment, $comment_array[$i]);  
    }
    $i++;
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
<?php if( !empty($error_message) ): ?><!-- $error_messageの中身が空でなければ(「タイトルは必須です。」または「記事は必須です。」が入ってれば) -->
    <div class="error_message"><?php echo $error_message[0]; ?></div>
<?php endif; ?>
<!--下はコメントの投稿ボタンの作成-->
<form method="post">
	<div>
		<label for="comment"></label>
		<textarea id="comment" name="comment"></textarea>
	</div>
	<input type="submit" name="btn_submit" value="コメント">
    <input id="comment_id" type="hidden" name="comment_id" value="<?php echo uniqid();?>">
    <input id="article_id" type="hidden" name="article_id" value="<?php echo $article_id;?>">
</form>
<section>
<?php if( !empty($comment) ): ?><!--$message_arrayの中身が空でなければ-->
<?php $i=0;?>
<?php while( isset($comment[$i])):?>
<?php    $value = $comment[$i]; ?>
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


<?php
// defineで定数FILENAMEを作成./message.txtへのパスの設定
define( 'FILENAME', './message.txt');

// 変数の初期化　※nullで値を空にしておき、存在しない変数を読んだり、意図しない挙動を防ぐため
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$error_message = array();//未入力の内容と配列


if( !empty($_POST['btn_submit']) ) {//投稿した内容(変数)が空でなければ・・・
    //var_dump($_POST);//var_dumpで受け取ったデータを上部に表記※32,33行目の配列を指定する際の確認用
    if( empty($_POST['view_name']) ) {//入力したタイトルの内容が空だったら
		$error_message[] = 'タイトルは必須です。';//$error_message[]に「タイトルは必須です。」が加えられます。
	}

    if( empty($_POST['message']) ) {//入力した記事の内容が空だったら
		$error_message[] = '記事は必須です。';//$error_message[]に「記事は必須です。」が加えられます。
	}

    if( empty($error_message) ) {//$error_message)が空だったら以下(26~34)の動作は行われない。

        if( $file_handle = fopen( FILENAME, "a") ) {//ファイル名かつパスを指定して開いて、書き込みをする。"a"が書き込み機能
            // 書き込むデータを作成
		    $data = $_POST['view_name'].",".$_POST['message'].",".$_POST['file_id'].","."\n";
            
	
		    fwrite( $file_handle, $data);//$file_handleに$dataを書き込む
		    // ファイルを閉じる
		    fclose( $file_handle);
    }   }	
}

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



?>


<!--ここから下が入力フォームの部分-->


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>Laravel News.index</title>
</head>
<body>
<a href="http://localhost/">Laravel News</a>
<h1>さぁ、最新ニュースをシェアしましょう</h1>
<?php if( !empty($error_message) ): ?><!-- $error_messageの中身が空でなければ(「タイトルは必須です。」または「記事は必須です。」が入ってれば) -->
	<ul class="error_message">
        <?php $i = 0;?>
		<?php while(isset($error_message[$i]) ): ?><!--$error_messageの中身を$valueに入れていく-->
			<?php $value = $error_message[$i] ?>
            <li><?php echo $value; ?></li>
        <?php $i++ ?>
		<?php endwhile; ?>
	</ul>
<?php endif; ?>     
<form method="post" onsubmit="return ask()"><!--フォームの作成とmethodで通信方式の指定、今回はpost。onsubmit属性で送信時に関数ask()を呼び出し、ok(true)で投稿処理がされる。-->
    <div>
        <label for="view_name">タイトル</label><!--タイトル部分のフォーム、view_nameの部分にタイトルで入力されたデータが入る-->
        <input id="view_name" type="text" maxlength="30" name="view_name" value=""><!--type属性で30字以内の1行のtextに指定、phpで受け取ったデータを引用するために名前をname属性に-->
    </div>
    <div>
        <label for="message">記事</label><!--記事のフォーム、messageに記事に入力されたデータが入る-->
        <textarea id="message" name="message" cols="50" rows="10"></textarea><!--textareaで複数行、10行50列に設定-->
    </div>
    <input type="submit" name="btn_submit" value="投稿"><!--投稿ボタン-->
    
    <input id="file_id" type="hidden" name="file_id" value="<?php echo uniqid();?>"> <!--echo uniqid('id_', true);で文字と数字の混ざったユニークIDの作成、-->
</form>
<hr>

<section>
<?php if( !empty($message_array) ): ?><!--$message_arrayの中身が空でなければ-->
<?php $i=0;?>
<?php while( isset($message_array[$i])):?>
<?php    $value = $message_array[$i]; ?>
<?php      $i++; ?>
<!--$message_arrayから入力されたデータを取り出し$valueに入れる-->
<article>
    <div class="info">
        <h2><?php echo $value['view_name']; ?></h2><!--<h2>でview_name(タイトル)を出力-->
    </div>
    <p><?php echo $value['message']; ?></p><!--<ｐ>でmessage(記事)を出力-->
    <p><?php echo $value['file_id']; ?></p><!--ユニークIDの確認用-->
    <a href="http://localhost/edit2.php?id=<?php echo $value['file_id']?>">記事全文・コメントを見る</a><!--記事の詳細のリンク作成、$valueのユニークidのキーを指定して個別のページに飛ぶように設定-->
    <hr><!--下線部-->
</article>
<?php endwhile; ?>
<?php endif; ?>    
</section>
<script>
    ask = () => {//関数の設定
        return confirm('投稿してよろしいですか？');//確認ダイアログの出現
    }
</script>
</body>
</html>
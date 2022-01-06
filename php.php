<?PHP
for($i = 1; $i <= 100; $i++){
    if($i % 3 == 0 && $i % 5 == 0){//3倍かつ5倍の時
        echo 'FizzBuzz';//FizzBuzzと表記
    }elseif($i % 3 == 0){//3倍の時
        echo 'Fizz';//Fizzと表記
    }elseif($i % 5 == 0){//5倍の時
        echo 'Buzz';//Buzzと表記
    }else{//でなければ
        echo $i;//該当しない数字を表記
    }
    echo "\n";
}


?>

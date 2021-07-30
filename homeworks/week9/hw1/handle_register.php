<?php
  require_once('conn.php');

  if (empty($_POST['nickname']) || empty($_POST['username']) || empty($_POST['password'])) {
    header('Location: register.php?errCode=1');
    die('資料尚有缺漏，請重新輸入');
  }

  $nickname = $_POST['nickname'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $sql = sprintf('INSERT INTO lavier_week9_users(nickname, username, password) VALUES("%s", "%s", "%s")',$nickname, $username, $password);
  $result = $conn->query($sql);
  if (empty($result)) {
    $code = $conn->errno;  // errno：錯誤訊息代碼
    if($code === 1062) {  // errorCode 1062：匯入資料重複
      header('Location: register.php?errCode=2');
      die('帳號已被註冊，請重新輸入');
    }
    die($conn->error);
  }
  
  header('Location: index.php');
?>
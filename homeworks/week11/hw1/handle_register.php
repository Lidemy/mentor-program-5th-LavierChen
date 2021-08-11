<?php
  session_start();
  require_once('conn.php');

  if (empty($_POST['nickname']) || empty($_POST['username']) || empty($_POST['password'])) {
    header('Location: register.php?errCode=1');
    die('資料尚有缺漏，請重新輸入');
  }

  $nickname = $_POST['nickname'];
  $username = $_POST['username'];
  // hash password
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $sql = 'INSERT INTO lavier_week11_users(nickname, username, password) VALUES(?, ?, ?)';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sss', $nickname, $username, $password);
  $result = $stmt->execute();
  if (empty($result)) {
    $code = $conn->errno;
    if($code === 1062) {
      header('Location: register.php?errCode=2');
      die('帳號已被註冊，請重新輸入');
    }
    die($conn->error);
  }
  
  // if register done, keep status of login
  $_SESSION['username'] = $username;
  header('Location: index.php');
?>
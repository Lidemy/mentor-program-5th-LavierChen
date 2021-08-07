<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  if (empty($_SESSION['username'])) {
    header('Location: index.php?errCode=6');
    die('您尚未登入');
  }

  $nickname = $_POST['nickname'];
  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);

  if ($user['role'] === 'banned') {
    header('Location: index.php?errCode=3');
    die('該使用者被禁止編輯暱稱');
  }

  if (empty($_POST['nickname'])) {
    header('Location: index.php?errCode=2');
    die('請輸入新的暱稱');
  }

  $sql = 'Update lavier_week11_users SET nickname=? WHERE username=?';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $nickname, $username);
  $result = $stmt->execute();
  if (empty($result)) {
    die($conn->error);
  }

  header('Location: index.php');
?>
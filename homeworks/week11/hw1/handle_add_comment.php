<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  if (empty($_SESSION['username'])) {
    header('Location: index.php?errCode=6');
    die('您尚未登入');
  }

  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);
  $content = $_POST['content'];

  if ($user['role'] === 'banned') {
    header('Location: index.php?errCode=4');
    die('該使用者被禁止新增留言');
  }

  if (empty($content)) {
    header('Location: index.php?errCode=1');
    die('請輸入留言');
  }

  $sql = 'INSERT INTO lavier_week11_comments(username, content) VALUES(?, ?)';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $username, $content);
  $result = $stmt->execute();
  if (empty($result)) {
    die($conn->error);
  }
  
  header('Location: index.php');
?>
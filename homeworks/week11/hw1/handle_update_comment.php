<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  if (empty($_POST['content'])) {
    header('Location: update_comment.php?errCode=1&id=' . $_POST['id']);
    die('請輸入留言');
  }

  $id = $_POST['id'];
  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);
  $content = $_POST['content'];
  
  // admin
  if ($user['role'] === 'admin') {
    $sql = 'Update lavier_week11_comments SET content = ? WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $content, $id);
  } else {  // personal
    $sql = 'Update lavier_week11_comments SET content = ? WHERE id = ? AND username = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sis', $content, $id, $username);
  }
  $result = $stmt->execute();
  if (empty($result)) {
    die($conn->error);
  }

  header('Location: index.php');
?>
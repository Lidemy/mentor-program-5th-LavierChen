<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  if (empty($_SESSION['username'])) {
    header('Location: index.php?errCode=6');
    die('您尚未登入');
  }

  if (empty($_GET['id'])) {
    header('Location: index.php');
    exit();
  }

  $id = $_GET['id'];
  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);

  // admin or editor
  if ($user['role'] === 'admin' || $user['role'] === 'editor') {
    $sql = 'UPDATE lavier_week11_comments SET is_deleted = 1 WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
  } else {  // personal
    $sql = 'UPDATE lavier_week11_comments SET is_deleted = 1 WHERE id = ? AND username = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $id, $username);
  }
  $result = $stmt->execute();
  if (empty($result)) {
    die($conn->error);
  }

  header('Location: index.php');
?>
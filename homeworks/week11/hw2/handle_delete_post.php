<?php
  session_start();
  require_once('utils.php');
  require_once('conn.php');

  if (empty($_SESSION['username'])) {
    header('Location: index.php');
    exit();
  }

  if (empty($_GET['id'])) {
    header('Location: admin.php');
    exit();
  }

  $id = $_GET['id'];
  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);

  if ($username) {
    $sql = 'UPDATE lavier_week11_blog_post SET is_deleted = 1 WHERE id = ? AND username = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $id, $username);
    $result = $stmt->execute();
    if (!$result) {
      die ($conn->error);
    }
  }

  header("Location: admin.php");
?>
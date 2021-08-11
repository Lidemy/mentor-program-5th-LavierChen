<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  if (empty($_SESSION['username'])) {
    header('Location: index.php');
    exit();
  } else {
    $username = $_SESSION['username'];
    $user = getUserFromUsername($username);
    if ($user['role'] !== 'admin') {
      header('Location: index.php');
      exit();
    }
  }

  if (empty($_POST['title']) || empty($_POST['content'])) {
    header('Location: edit_post.php?errCode=1');
    die('標題與內容不得為空');
  }

  $title = $_POST['title'];
  $category = $_POST['category'];
  $content = $_POST['content'];

  $sql = 'INSERT INTO lavier_week11_blog_post(username, title, category, content) VALUES(?, ?, ?, ?)';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ssss', $username, $title, $category, $content);
  $result = $stmt->execute();
  if (empty($result)) {
    die($conn->error);
  }
  
  header('Location: index.php');
?>
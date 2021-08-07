<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  if (empty($_POST['id']) || empty($_POST['title']) || empty($_POST['content'])) {
    header('Location: edit_post.php?errCode=1&id=' . $_POST['id']);
    die('標題與內容不得為空');
  }

  $id = $_POST['id'];
  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);
  $title = $_POST['title'];
  $category = $_POST['category'];
  $content = $_POST['content'];
  
  if ($username) {
    $sql = 'Update lavier_week11_blog_post SET title = ?, category = ?, content = ? WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $title, $category, $content, $id);
    $result = $stmt->execute();
  }

  if (empty($result)) {
    die($conn->error);
  }

  header('Location: admin.php');
?>
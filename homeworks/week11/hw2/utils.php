<?php
  require_once('conn.php');

  function getUserFromUsername($username) {
    global $conn;
    $sql = 'SELECT * FROM lavier_week11_blog_admin WHERE username = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $result = $stmt->execute();
    if (empty($result)) {
      die($conn->error);
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row;
  }

  function getPostFromId($id) {
    global $conn;
    $sql = 'SELECT * FROM lavier_week11_blog_post WHERE id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $result = $stmt->execute();
    if (empty($result)) {
      die($conn->error);
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row;
  }

  function getCategory($category) {
    switch ($category) {
      case 'food':
        return '食譜';
      case 'movie':
        return '電影';
      case 'feel':
        return '心情';
      default:
       return '其他';
    }
  }

  function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  }
?>
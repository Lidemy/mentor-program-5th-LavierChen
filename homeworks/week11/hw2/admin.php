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
  }

  if ($user['role'] !== 'admin') {
    header('Location: index.php');
    exit();
  }
?>

<!DOCTYPE html>
<html lang>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理後台</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
  <nav class="navbar">
      <div class="nav_left">
        <div class="nav_logo">
          <img class="nav_img" src="asset/lifestyle.png" width="50px" color="white">
          <h2 class="nav_title">下班一小時</h2>
        </div>
      </div>
      <div class="nav_right">
        <a href="index.php" class="button">返回</a>
        <a href="handle_logout.php" class="button">登出</a>
      </div>
    </nav>
   
    <?php 
      $sql = 'SELECT ' . 
                'P.id AS id, P.username AS username, P.title AS title, ' .  
                'P.category AS category, P.content AS content ' . 
              'FROM lavier_week11_blog_post AS P ' . 
              'LEFT JOIN lavier_week11_blog_admin AS U ' . 
              'ON P.username = U.username ' . 
              'WHERE P.is_deleted IS NULL ' . 
              'ORDER BY P.id DESC';
      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
      if (empty($result)) {
        die($conn->error);
      }
      $result = $stmt->get_result();
    ?>

    <section class="post">
      <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="post_items">
          <div class="post_info admin">
            <div class="post_left">
              <a class="post_category check admin" href="index.php?category=<?php echo $row['category']; ?>">
                <?php echo escape(getCategory($row['category'])); ?>
              </a>
              <a class="post_title admin" href="read_post.php?id=<?php echo $row['id']; ?>">
                <?php echo escape($row['title']); ?>
              </a>
            </div>
            <div class="post_right admin">
              <a href="edit_post.php?id=<?php echo $row['id']; ?>" class="button">編輯</a>
              <a href="handle_delete_post.php?id=<?php echo $row['id']; ?>" class="button">刪除</a>
            </div>
          </div>
          <p class="post_content admin"><?php echo escape($row['content']) ?></p>
        </div>
      <?php } ?>
    </section>
  </body>
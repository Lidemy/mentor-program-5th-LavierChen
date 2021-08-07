<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  $username = NULL;
  $user = NULL;
  if (!empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $user = getUserFromUsername($username);
  }

  $id = NULL;
  $post = NULL;
  if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $post = getPostFromId($id);
  }
  if (empty($post['title'])) {
    header('Location: post_list.php');
    exit();
  }
?>

<!DOCTYPE html>
<html lang>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>下班一小時</title>
    <link rel="stylesheet" href="./normalize.css">
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <!-- navbar -->
    <nav class="navbar">
      <div class="nav_left">
        <div class="nav_logo">
          <img class="nav_img" src="asset/lifestyle.png" width="50px" color="white">
          <h2 class="nav_title">下班一小時</h2>
        </div>
        <div class="nav_list">
          <a href="index.php" class="button">首頁</a>
          <a href="post_list.php" class="button">文章列表</a>
          <a href="index.php?category=food" class="button">食譜</a>
          <a href="index.php?category=movie" class="button">電影</a>
          <a href="index.php?category=feel" class="button">心情</a>
        </div>
      </div>
      <div class="nav_right">
        <?php if (empty($username)) { ?>
          <div class="btn">
            <a href="register.php" class="button">註冊</a>
            <a href="login.php" class="button">登入</a>
          </div>
        <?php } else { ?>
          <div class="btn">
            <?php if ($user['role'] === 'admin') {?>
              <a href="edit_post.php" class="button">發文</a>
              <a href="admin.php" class="button">管理後臺</a>
            <?php } ?>
            <a href="handle_logout.php" class="button">登出</a>
          </div>
        <?php } ?>
      </div>
    </nav>

    <!-- article -->
    <?php 
      $sql = 'SELECT * FROM lavier_week11_blog_post WHERE id = ?';
      $stmt = $conn->prepare($sql);
      $stmt->bind_param('i', $id);
      $result = $stmt->execute();
      if (empty($result)) {
        die($conn->error);
      }
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
    ?>
    
    <section class="read_region">
      <div class="post_items">
        <div class="post_info">
          <div class="post_left">
            <div class="post_category"><?php echo escape(getCategory($row['category'])); ?></div>
            <div class="post_title"><?php echo escape($row['title']); ?></div>
          </div>
          <div class="post_right">
            <div class="post_time"><?php echo escape(substr($row["create_at"], 5, 11)); ?></div>
          </div>
        </div>
        <div class="read_content"><?php echo escape($row['content']) ?></div>
      </div>
    </section>
  </body>
</html>
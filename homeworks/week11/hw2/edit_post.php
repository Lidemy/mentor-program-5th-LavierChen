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

  $id = NULL;
  $post = NULL;
  if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $post = getPostFromId($id);
  }
?>

<!DOCTYPE html>
<html lang>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>下班一小時</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <div class="edit_region">

      <!-- update post or create post --> 
      <?php if ($id) { ?>
        <form method="POST" action="handle_update_post.php">
          <div class="edit_btn">
            <?php
              if (!empty($_GET['errCode'])) {
                $code = $_GET['errCode'];
                $msg = 'error';
                if ($code === '1') {
                  $msg = '標題與內容不得為空';
                } 
                echo '<h3 class="edit_error">錯誤：' . $msg . '</h3>';
              }
            ?>
            <input type="submit" class="post_submit-btn" value="更新">
            <a href="admin.php">返回</a>
            <a id="edit_delete" href="handle_delete_post.php?id=<?php echo $id; ?>">刪除</a>
          </div>
      <?php } else { ?>
        <form method="POST" action="handle_create_post.php">
          <div class="edit_btn">
            <?php
              if (!empty($_GET['errCode'])) {
                $code = $_GET['errCode'];
                $msg = 'error';
                if ($code === '1') {
                  $msg = '標題與內容不得為空';
                } 
                echo '<h3 class="edit_error">錯誤：' . $msg . '</h3>';
              }
            ?>
            <input type="submit" class="post_submit-btn" value="發佈">
            <a href="index.php">返回</a>
          </div>
      <?php } ?>
      
      <?php if (empty($post['title']) && (empty($post['content']))) { ?>
        <!-- create post -->
        <input type="text" class="edit_title" name="title" placeholder="Title" required>
        <div class="category_container">
          <span>Tag：</span>
          <div class="edit_category">
            <?php switch (!empty($post['category'])) {
              case 'food': ?>
                <label><input type="radio" name="category" value="food" checked><span class="category_btn">食譜</span></label>
                <label><input type="radio" name="category" value="movie"><span class="category_btn">電影</span></label>
                <label><input type="radio" name="category" value="feel"><span class="category_btn">心情</span></label>
                <label><input type="radio" name="category" value="others"><span class="category_btn">其他</span></label>
                <?php break;
              case 'movie': ?>
                <label><input type="radio" name="category" value="food"><span class="category_btn">食譜</span></label>
                <label><input type="radio" name="category" value="movie" checked><span class="category_btn">電影</span></label>
                <label><input type="radio" name="category" value="feel"><span class="category_btn">心情</span></label>
                <label><input type="radio" name="category" value="others"><span class="category_btn">其他</span></label>
                <?php break;
              case 'feel': ?>
                <label><input type="radio" name="category" value="food"><span class="category_btn">食譜</span></label>
                <label><input type="radio" name="category" value="movie"><span class="category_btn">電影</span></label>
                <label><input type="radio" name="category" value="feel" checked><span class="category_btn">心情</span></label>
                <label><input type="radio" name="category" value="others"><span class="category_btn">其他</span></label>
                <?php break;
              default: ?>
                <label><input type="radio" name="category" value="food" checked><span class="category_btn">食譜</span></label>
                <label><input type="radio" name="category" value="movie"><span class="category_btn">電影</span></label>
                <label><input type="radio" name="category" value="feel"><span class="category_btn">心情</span></label>
                <label><input type="radio" name="category" value="others" checked><span class="category_btn">其他</span></label>
                <?php break;
            } ?>
          </div>
        </div>
        <textarea class="edit_content" name="content" placeholder="What's your story?" required></textarea>
      <?php } else { ?>
        <input type="text" class="edit_title" name="title" placeholder="Title" value="<?php echo escape($post['title']) ?>" required>
        <div class="category_container">
          <span>Tag：</span>
          <div class="edit_category">
            <?php switch (escape($post['category'])) {
              case 'food': ?>
                <label><input type="radio" name="category" value="food" checked><span class="category_btn">食譜</span></label>
                <label><input type="radio" name="category" value="movie"><span class="category_btn">電影</span></label>
                <label><input type="radio" name="category" value="feel"><span class="category_btn">心情</span></label>
                <label><input type="radio" name="category" value="others"><span class="category_btn">其他</span></label>
                <?php break;
              case 'movie': ?>
                <label><input type="radio" name="category" value="food"><span class="category_btn">食譜</span></label>
                <label><input type="radio" name="category" value="movie" checked><span class="category_btn">電影</span></label>
                <label><input type="radio" name="category" value="feel"><span class="category_btn">心情</span></label>
                <label><input type="radio" name="category" value="others"><span class="category_btn">其他</span></label>
                <?php break;
              case 'feel': ?>
                <label><input type="radio" name="category" value="food"><span class="category_btn">食譜</span></label>
                <label><input type="radio" name="category" value="movie"><span class="category_btn">電影</span></label>
                <label><input type="radio" name="category" value="feel" checked><span class="category_btn">心情</span></label>
                <label><input type="radio" name="category" value="others"><span class="category_btn">其他</span></label>
                <?php break;
              default: ?>
                <label><input type="radio" name="category" value="food" checked><span class="category_btn">食譜</span></label>
                <label><input type="radio" name="category" value="movie"><span class="category_btn">電影</span></label>
                <label><input type="radio" name="category" value="feel"><span class="category_btn">心情</span></label>
                <label><input type="radio" name="category" value="others" checked><span class="category_btn">其他</span></label>
                <?php break;
            } ?>
          </div>
        </div>
        <textarea class="edit_content" name="content" rows="20" cols="80" placeholder="What's your story?" required><?php echo escape($post['content']) ?></textarea>
      <?php } ?>
    </div>
  </body>
</html>

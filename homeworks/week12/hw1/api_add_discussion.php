<?php
  require_once('conn.php');
  header('Content-Type: application/json; charset=utf-8');
  header('Access-Control-Allow-Origin: *');
  date_default_timezone_set('Asia/Taipei');

  $json = array();
  if (empty($_POST['site_key']) || empty($_POST['nickname']) || empty($_POST['content'])) {
    $json = array(
      'ok' => false, 
      'message' => 'Please input missing fields.'
    );
    $response = json_encode($json);
    echo $response;
    die();
  } else {
    $site_key = $_POST['site_key'];
    $nickname = $_POST['nickname'];
    $content = $_POST['content'];
    $create_at = date('Y-m-d H:i:s');
  }

  $sql = 'INSERT INTO lavier_week12_discussions(site_key, nickname, content) VALUES(?, ?, ?)';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sss', $site_key, $nickname, $content); 
  $result = $stmt->execute();

  if (empty($result)) {
    $json = array(
      'ok' => false, 
      'message' => $conn->error
    );
    $response = json_encode($json); 
    echo $response;
    die();
  }

  $json = array(
    'ok' => true, 
    'message' => 'Add discussion successfully.',
    'create_at' => substr($create_at, 5, 11)
  );
  $response = json_encode($json);
  echo $response;
?>
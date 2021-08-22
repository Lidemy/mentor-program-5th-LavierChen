<?php
  require_once('conn.php');
  header('Content-Type: application/json; charset=utf-8');
  header('Access-Control-Allow-Origin: *');

  $json = array();
  if (empty($_GET['site_key'])) {
    $json = array(
      'ok' => false, 
      'message' => 'Please send site_key in url.'
    );
    $response = json_encode($json);
    echo $response;
    die();
  } else {
    $site_key = $_GET['site_key'];
  }

  if (!empty($_GET['cursor'])) {
    $cursor = $_GET['cursor'];
  }

  if (!empty($cursor)) {
    $sql = 'SELECT * FROM lavier_week12_discussions WHERE site_key = ? AND id < ? ORDER BY id DESC LIMIT 6';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $site_key, $cursor);
  } else {
    $sql = 'SELECT * FROM lavier_week12_discussions WHERE site_key = ? ORDER BY id DESC LIMIT 6';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $site_key);
  }
  $result = $stmt->execute();
  
  if (empty($result)) {
    $json = array(
      'ok' => false, 
      'message' => $conn->error
    );
    $response = json_encode($json);
    echo $response;
    die();
  } else {
    $result = $stmt->get_result();
  }

  $discussions = array();
  while ($row = $result->fetch_assoc()) {
    array_push($discussions, array(
      'id' => $row['id'], 
      'nickname' => $row['nickname'], 
      'content' => $row['content'], 
      'create_at' => substr($row['create_at'], 5, 11)
    ));
  }
  $json = array(
    'ok' => true, 
    'discussions' => $discussions
  );
  $response = json_encode($json);
  echo $response;
?>
<?php
  require_once('conn.php');
  header('Content-Type: application/json; charset=utf-8');
  header('Access-Control-Allow-Origin: *');

  $json = array();
  if (empty($_GET['id'])) {
    $json = [
      'message' => 'No todos.'
    ];
    $response = json_encode($json);
    echo $response;
    die();
  } else {
    $id = $_GET['id'];
  }

  $sql = 'SELECT * FROM lavier_week12_todos WHERE id = ?';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $id);
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
  
  $row = $result->fetch_assoc();
  $json = array(
    'ok' => true, 
    'id' => $row['id'], 
    'todos' => $row['todos']
  );
  $response = json_encode($json);
  echo $response;
?>
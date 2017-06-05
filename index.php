<?php

  $action = isset($_POST['action']) ? $_POST['action'] : '';

  switch($action) {
    case 'getAll':
      $content = tomar_archivo('data-1.json', './');
      $response['json'] = $content;
      $response['message'] = 'getAll action was done';
      break;
    case ''
    default:
      $response['message'] = 'The action could not get processed...';
      break;
  }

  function tomar_archivo($nombre, $directorio) {
    $file = fopen($directorio . $nombre, 'r');
    $answer = fread($file, filesize($directorio . $nombre));
    fclose($file);
    return json_decode($answer, true);
  }

  function tomar_campo($nombre_campo) {

  }

  echo json_encode($response);

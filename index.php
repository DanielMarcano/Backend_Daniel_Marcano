<?php

  $action = isset($_POST['action']) ? $_POST['action'] : '';

  switch($action) {
    case 'getAll':
      $content = tomar_archivo('data-1.json', './');
      $response['json'] = $content;
      $response['message'] = 'getAll action was done';
      $response['ciudades'] = tomar_campo('Ciudad');
      $response['tipos'] = tomar_campo('Tipo');
      break;
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

// Ciudad o Tipo
  function tomar_campo($nombre_campo) {
    $articles = tomar_archivo('data-1.json', './');
    $array_campo = [];
    foreach($articles as $article) {
      array_push($array_campo, $article[$nombre_campo]);
    }

    return array_unique($array_campo);
  }

  echo json_encode($response);

<?php

  $action = isset($_POST['action']) ? $_POST['action'] : '';

  switch($action) {
    case 'inicializar_selects':
      $response['ciudades'] = tomar_campo('Ciudad');
      $response['tipos'] = tomar_campo('Tipo');
      break;
    case 'mostrar_todos':
      $content = tomar_archivo('data-1.json', './');
      $response['json'] = $content;
      $response['message'] = 'Se enviaron todos los articulos con éxito!';
      break;
    case 'mostrar_busqueda':
      $ciudad = isset($_POST['ciudad']) ? $_POST['ciudad'] : '';
      $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
      $rango_precio = isset($_POST['rango_precio']) ? $_POST['rango_precio'] : '';

      // Tomamos el rango de precios, y creamos dos variables
      // una con el precio minimo, y otra con el maximo
      $precios = explode(';', $rango_precio);
      $precio_min = $precios[0];
      $precio_max = $precios[1];

      $response['json'] = tomar_busqueda($ciudad, $tipo, $precio_min, $precio_max);
      $response['message'] = 'Se ha realizado la búsqueda con éxito!';
  }

  function tomar_archivo($nombre, $directorio) {
    $file = fopen($directorio . $nombre, 'r');
    $answer = fread($file, filesize($directorio . $nombre));
    fclose($file);

    $answer = json_decode($answer, true);

    foreach($answer as $key => $article) {
      $precio_article = preg_replace('/[\$,]/', '', $article['Precio']);
      $precio_article = floatval($precio_article);
      $answer[$key]['precio_sin_formato'] = $precio_article;
    }

    // return json_decode($answer, true);
    return $answer;
  }

// Tomamos un array del campo que queramos, de nuestro archivo .json
  function tomar_campo($nombre_campo) {
    $articles = tomar_archivo('data-1.json', './');
    $array_campo = [];
    foreach($articles as $article) {
      array_push($array_campo, $article[$nombre_campo]);
    }

    return array_unique($array_campo);
  }

  function tomar_busqueda($ciudad, $tipo, $precio_min, $precio_max) {
    $articles = tomar_archivo('data-1.json', './');
    $array_busqueda = [];
    foreach($articles as $key => $article) {
      // Convertimos $article['Precio'] en un numero
      $precio_article = preg_replace('/[\$,]/', '', $article['Precio']);
      $precio_article = floatval($precio_article);

      // Realizamos ciertas validaciones dependiendo del caso

      // Busca los articulos dentro del rango de precios establecido
      // y guarda SOLO los que correspondan a la búsqueda realizada
      if ($precio_article >= $precio_min && $precio_article <= $precio_max) {
        if ($ciudad != '' && $tipo == '') {
          // Si solo la ciudad fue ingresada
          if ($article['Ciudad'] == $ciudad) {
            $article['precio_sin_formato'] = $precio_article;
            // $article['Precio_Sin_Formato'] = $precio_article;

            array_push($array_busqueda, $article);
          }
        } else if ($ciudad == '' && $tipo != '') {
          // Si solo el tipo fue ingresado
          if ($article['Tipo'] == $tipo) {
            array_push($array_busqueda, $article);
          }
        } else if ($article['Tipo'] == $tipo && $article['Ciudad'] == $ciudad) {
          // Si tanto la ciudad como el tipo fueron ingresadas
          array_push($array_busqueda, $article);
        } else if ($tipo == '' && $ciudad == ''){
          // Si solo se coloca el precio
          array_push($array_busqueda, $article);
        }
      }
    }

    return $array_busqueda;
  }

  echo json_encode($response);

<?php

namespace Controllers\API;

use Core;
use Services\Weather;

class Widget extends Core\APIController {
  public function show($id = 0, $action = "") {
    if ($id == "0" && $action == "data") {
      $weatherService = new Weather();
      $weather = $weatherService->getWeather();
      header('Content-Type: application/json');
      echo json_encode($weather);
    }
  }
}
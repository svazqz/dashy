<?php

namespace Controllers\API;

use Core;

class Config extends Core\APIController {
  public function show() {
    header('Content-Type: application/json');
    echo json_encode(array(
        "backgroundImage" => "bg3.jpg",
        "overlayOpacity" => 0
    ));
  }
}

<?php

namespace Controllers\API;

use Core;

class Config extends Core\APIController {
  private function calculateOpacity() {
    $hour = (int)date('G');
    return ($hour >= 6 && $hour <= 21) ? 0 : 0.7;
  }

  public function show() {
    header('Content-Type: application/json');
    echo json_encode(array(
        "backgroundImage" => "bg3.jpg",
        "overlayOpacity" => $this->calculateOpacity()
    ));
  }
}

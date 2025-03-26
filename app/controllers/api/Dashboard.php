<?php

namespace Controllers\API;

use Core;

class Dashboard extends Core\APIController {
  public function show($id = 0, $action = "") {
    echo "ID: " . $id . "<br>";
    echo "Action: " . $action;
  }
}

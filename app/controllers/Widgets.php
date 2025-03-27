<?php

namespace Controllers;

use Core;

class Widgets extends Core\Controller {

  public function main($n = "Your Name") {
    $this->getView()->widgetsPage();
  }

}

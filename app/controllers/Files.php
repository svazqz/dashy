<?php

namespace Controllers;

use Core;

class Files extends Core\Controller {

  public function main($n = "Your Name") {
    $this->getView()->filesPage();
  }

}

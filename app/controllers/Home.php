<?php

namespace Controllers;

use Core;
use Models;

class Home extends Core\Controller {

  public function main($n = "Your Name") {
    $this->getView()->homePage();
  }

  public function test($var = "ok", $var2 = "ok") {
    echo $var." ".$var2;
  }

}

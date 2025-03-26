<?php

namespace Views;

class Home {

  public function homePage() {
    // echo \Input::getVar("name", "Name");
    \View::renderHTML("home/index", array("name" => "Test name"));
  }

}

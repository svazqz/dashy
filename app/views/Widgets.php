<?php

namespace Views;

class Widgets {

  public function widgetsPage() {
    // echo \Input::getVar("name", "Name");
    \View::renderHTML("widgets/index", array("name" => "Test name"));
  }

}

<?php

namespace Views;

class Files {

  public function filesPage() {
    \View::renderHTML("files/index", array("name" => "Test name"));
  }

}

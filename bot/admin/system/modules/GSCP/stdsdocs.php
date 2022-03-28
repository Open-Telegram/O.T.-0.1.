<?php
/*
SNK GPCL for PHP (General Purpose Class Library) version 2.1
(c) 2008-2013 SNK Software - www.snkey.net
All rights reserved.

stddocs.php
Default document template. Useful for typical web pages. HTML5 compliant.
Classes:
 - TStdDBSDocument - DB-aware document with head, body and foot parts and session support
*/

require_once("stddocs.php");
require_once("session.php");

class TStdDBSDocument extends TDBSDocument {
  public $header;
  public $bodyer;
  public $footer;
  function __construct($title, $closed = true) {
    parent::__construct($title, false, $closed, $redir = "index.php", $closed);
    if ($closed) if (!$this->session->GetIsAdmin()) exit;           
    $this->header = $this->body->AddControl(new THeader(), true);
    $this->bodyer = $this->body->AddControl(new TBodyer(), true);
    $this->footer = $this->body->AddControl(new TFooter(), true);
  } 
}

?>

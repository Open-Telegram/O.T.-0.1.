<?php
/*
SNK GPCL for PHP (General Purpose Class Library) version 2.1
(c) 2008-2013 SNK Software - www.snkey.net
All rights reserved.

stddocs.php
Default document template. Useful for typical web pages. HTML5 compliant.
Classes:
 - TStdBlock - TBlockControl-base class represents standard document block
 - THeader - Upper (head) body part 
 - TBodyer - Central (main) body part
 - TFooter - Lower (bottom) body part
 - TStdDocument - document with head, body and foot parts
 - TStdDBDocument - DB-aware clone of TStdDocument
*/

require_once("controls.php");
require_once("documents.php");

class TStdBlock extends TBlockControl {
  public function __construct($parent, $class = "", $tag = "div") {
    parent::__construct($parent, $tag);
    if ($class!="") $this->SetAttr('class',$class);
  }
}

class THeader extends TStdBlock {
  public $logo;
  public $menu;
  public $home;
  public function __construct($parent = NULL) {
    if (CValues::$doctype == "HTML5") $tag = "header"; else $tag = "div";  
    parent::__construct($parent, "header", $tag);
    if (CValues::$doctype == "HTML5") $tag = "section"; else $tag = "div";  
    $this->logo = $this->AddControl(new TStdBlock($this,"logo",$tag), true); 
    if (CValues::$doctype == "HTML5") $tag = "hgroup"; else $tag = "div";  
    $this->home = $this->AddControl(new TStdBlock($this,"home",$tag), true); 
    if (CValues::$doctype == "HTML5") $tag = "nav"; else $tag = "div";  
    $this->menu = $this->AddControl(new TStdBlock($this,"menu",$tag), true); 
  }
}

class TBodyer extends TStdBlock {
  public $main;
  public $tools;
  public $menu;
  public function __construct($parent = NULL) {
    parent::__construct($parent, "bodyer");
    if (CValues::$doctype == "HTML5") $tag = "nav"; else $tag = "div";  
    $this->menu = $this->AddControl(new TStdBlock($this,"menu",$tag), true); 
    if (CValues::$doctype == "HTML5") $tag = "article"; else $tag = "div";  
    $this->main = $this->AddControl(new TStdBlock($this,"main",$tag), true);
    if (CValues::$doctype == "HTML5") $tag = "aside"; else $tag = "div";  
    $this->tools = $this->AddControl(new TStdBlock($this,"tools",$tag), true); 
  }
}

class TFooter extends TStdBlock {
  public $about;
  public $menu;
  public function __construct($parent = NULL) {
    if (CValues::$doctype == "HTML5") $tag = "footer"; else $tag = "div";  
    parent::__construct($parent, "footer", $tag);
    if (CValues::$doctype == "HTML5") $tag = "nav"; else $tag = "div";  
    $this->menu = $this->AddControl(new TStdBlock($this,"menu",$tag), true); 
    if (CValues::$doctype == "HTML5") $tag = "section"; else $tag = "div";  
    $this->about = $this->AddControl(new TStdBlock($this,"about",$tag), true); 
  }
}

class TStdDocument extends TDocument {
  public $header;
  public $bodyer;
  public $footer;
  function __construct($title) {
    parent::__construct($title);            
    $this->header = $this->body->AddControl(new THeader(), true);
    $this->bodyer = $this->body->AddControl(new TBodyer(), true);
    $this->footer = $this->body->AddControl(new TFooter(), true);
  } 
}

class TStdDBDocument extends TDBDocument {
  public $header;
  public $bodyer;
  public $footer;
  function __construct($title) {
    parent::__construct($title);            
    $this->header = $this->body->AddControl(new THeader(), true);
    $this->bodyer = $this->body->AddControl(new TBodyer(), true);
    $this->footer = $this->body->AddControl(new TFooter(), true);
  } 
}

?>

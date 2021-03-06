<?php
/*
SNK GPCL for PHP (General Purpose Class Library) version 2.1
(c) 2008-2013 SNK Software - www.snkey.net
All rights reserved.

baseontrols.php
Common elements classes
Classes:
 - TAttributes - HTMLattributes helper class
 - TControl - basic control class
 - TNoControl - an virtual element (with no tags)
 - TOnwedControl - controls can contain child elements
 - TNoControlEx - like TNoControl but can contain child controls  
*/

require_once("const.php");
require_once("procs.php");

class TAttributes extends TObject {
  protected $attrlist; 
  public function __construct($parent) {
    parent::__construct($parent);
    $this->attrlist = array();
  }
  public function IsExists($atype) {
    if ($this->attrlist[$atype] != "") return true; else return false;
  }
  public function SetAttr($atype, $avalue, $ssymbols = false) {
    if ($ssymbols) $s = $avalue; else $s = q2html($avalue); 
    $this->attrlist[$atype] = $s;
  }
  public function AddValue($atype, $avalue) {
    $this->attrlist[$atype] = $this->attrlist[$atype] . q2html($avalue);
  }
  public function GetAttr($atype) {
    if ($this->attrlist[$atype] != "") return " $atype=\"{$this->attrlist[$atype]}\""; else return "";
  }
  public function GetAttrValue($atype) {
    if (isset($this->attrlist[$atype])) return $this->attrlist[$atype]; else return ""; 
  }
  public function GetAllAttrs() {
    $txt = "";
    foreach ($this->attrlist as $k => $v) {
      $txt .= $this->GetAttr($k);
    }
    return acc($txt);
  }  
}

class TControl extends TComponent {
  protected $tag;          //e.g. input
  protected $attrs;        //array
  protected $hasclose;     //has close tag or not
  public $content;         //text between open and close tags
  public $inneronly;       //don't output this element tags (useful for DOM innerhtml method)
  public function __construct($parent, $tag = "") {
    parent::__construct($parent);
    $this->tag = $tag;
    $this->attrs = new TAttributes($this);
    $this->hasclose = true;
    $this->inneronly = false;
  }
  public function BlockLevel() {
    return ( in_array($this->tag, array('html','body','head','p','div','table','tr','td','th','tbody','thead','tfoot','form','fieldset','button','object','iframe','marquee','ul','ol','dl','h1','h2','h3','h4','h5','h6','address','section','header','footer','nav','article','aside','hgroup','canvas')));
  }
  public function SetAttr($atype, $avalue, $ssymbols = false) {
    $this->attrs->SetAttr($atype, $avalue, $ssymbols);
  }
  public function SetAttrs($tvals = array()) {    
    foreach ($tvals as $atype => $avalue) {  
      $this->SetAttr($atype, $avalue);
    }
  }
  public function HasAttr($aname, $avalue = "") {
    if ($avalue == "") {
      return $this->attrs->IsExists($aname);
    } else {
      return ($this->attrs->GetAttrValue($aname) == $avalue); 
    }
  }
  public function HasChild() {
    return false; 
  }
  public function Content($text, $ac = true) {
    if ($ac && !CValues::$autoconv) $text = cc($text);
    $this->content = $text;
  }
  public function GetTag() {
    if ($this->inneronly) return "";
    $attrs = "";
    $attrs .= $this->attrs->GetAllAttrs();
    if ( (!$this->hasclose) && (CConst::isxhtml()) ) $attrs .= " /"; 
    return "<$this->tag$attrs>";
  } 
  public function GetCloseTag() {
    if ($this->inneronly) return "";
    if ($this->hasclose) return "</$this->tag>"; else return ""; 
  }           
  public function GetComplete($offset) {
    return $offset.$this->GetTag().acc($this->content).$this->GetCloseTag();
  }
}

class TNoControl extends TControl {
  public function __construct($parent) {
    parent::__construct($parent);
    $this->tag = "";
    $this->hasclose = false;
  }
  public function SetAttr($atype,$avalue) {
    ;
  }
  public function GetTag() {
    return "";
  } 
  public function GetCloseTag() {
    return ""; 
  }           
}

class TSimpleLinkControl extends TControl {
  public function __construct($parent, $href = "", $hint = "") {
    parent::__construct($parent, "a");
    $this->SetAttr("href",$href,true);
    $this->SetAttr("title",$hint);
  }
}

class TBRControl extends TControl {
  public function __construct($parent) {
    parent::__construct($parent, "br");
    $this->hasclose = false;
  }
}

class THRControl extends TControl {
  public function __construct($parent) {
    parent::__construct($parent, "hr");
    $this->hasclose = false;
  }
}

class TOnwedControl extends TControl {
  protected $controls;    //array;
  public $endcontent;  //text between last child close tag and this element close tags 
//NOTE: text from $content placed between this element open tag and first child open tag  
  public function __construct($parent, $tag = "") {
    parent::__construct($parent, $tag);
    $this->controls = array();
    $this->endcontent = "";
  }
  public function ControlCount() {
    return count($this->controls); 
  }
  public function HasChild() {
    return ($this->ControlCount()>0); 
  }
  public function AddControl($ctrl, $andreturn = false) {
    $this->controls[] = $ctrl;
    $ctrl->parentcontrol = $this;
    if ($andreturn) return $ctrl;
  }
  public function CreateChildControl($type) {
    $ctrl = new TControl($this, $type);
    $this->controls[] = $ctrl;
    return $ctrl;
  }
  public function CreateControl($type) {
    $ctrl = new TOnwedControl($this, $type);
    $this->controls[] = $ctrl;
    return $ctrl;
  }
  public function GetControl($name) {
    foreach ($this->controls as $ctrl) {
      if ($ctrl->name == $name) return $ctrl;
    }
    return false;  
  }
  public function GetControlR($name) {
    foreach ($this->controls as $ctrl) {
      if ($ctrl->name == $name) return $ctrl;
      if (method_exists($ctrl,"GetControlR")) { 
        $rctrl = $ctrl->GetControlR($name);
        if ($rctrl) return $rctrl;
      }  
    }
    return false;  
  }
  public function GetControls($tag, $attr = "", $attrval = "") {
    $ret = array();
    foreach ($this->controls as $ctrl) {
      if ( ($ctrl->tag == $tag) && ( ($attr=="") || ($ctrl->HasAttr($attr, $attrval)) ) ) $ret[] = $ctrl;
    }
    return $ret;  
  }
  public function AddNone($text="") {
    $ctrl = new TNoControl($this);
    $ctrl->content = $text;
    $this->AddControl($ctrl);
    return $ctrl;
  }
  public function AddNoneEx($text="") {
    $ctrl = new TNoControlEx($this);
    $ctrl->content = $text;
    $this->AddControl($ctrl);
    return $ctrl;
  }
  public function AddBR() {
    $ctrl = new TBRControl($this);
    $this->controls[] = $ctrl;
    return $ctrl;
  } 
  public function AddHR() {
    $ctrl = new THRControl($this);
    $this->controls[] = $ctrl;
    return $ctrl;
  } 
  public function FreeControl($ctrl) {
    foreach($this->controls as $key => $value) {
      if ($value === $ctrl) unset($this->controls[$key]);
    }
//     unset($this->controls[array_search($ctrl,$this->controls)]);  // don't work in php prior to 5.2.1
  }
  public function GetFirstControl($tag) {
    foreach ($this->controls as $ctrl) {
      if ($ctrl->tag == $tag) return $ctrl;
    }
    return false;  
  }
  public function GetComplete($offset) {
    $this->content = acc($this->content);
    $txt = $this->GetTag().$this->content;
    if ($this->BlockLevel()) { 
      $txt .= "\n";
      $offset = " ";
    } else $offset = "";
    $cc = 0;  
    foreach ($this->controls as $ctrl) {
      $txt .= $offset.$ctrl->GetComplete($offset);
      $cc++;
      if (property_exists($ctrl,'breakln') && $ctrl->breakln) {
        if ($this->breakln) { 
          if (CConst::isxhtml()) $txt .= "<br />";
            else $txt .= "<br>";
        }
      }
      $txt .= "\n";
    }
    return $txt.acc($this->endcontent).$this->GetCloseTag();  
  } 
}

class TNoControlEx extends TOnwedControl {
  public function __construct($parent) {
    parent::__construct($parent, "");
    $this->hasclose = false;
  }
  public function SetAttr($atype,$avalue) {
    ;
  }
  public function GetTag() {
    return "";
  }                                                    
  public function GetCloseTag() {
    return ""; 
  } 
  public function AddLink($href, $hint = "", $hrefastext = false) {
    $ctrl = new TSimpleLinkControl($this, $href, $hint);
    if ($hrefastext) $ctrl->content = $href; 
    if ((!$hrefastext) && ($hint != "")) $ctrl->content = $hint;
    $this->AddControl($ctrl);
    return $ctrl;
  }            
}

?>

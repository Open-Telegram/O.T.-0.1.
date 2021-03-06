<?php
/*
SNK GPCL for PHP (General Purpose Class Library) version 2.1
(c) 2008-2013 SNK Software - www.snkey.net
All rights reserved.

dbcontrols.php
DB-aware elements (these classes are successors of TFMLListControl and TForm/TFieldset)
Classes:
 - TDBList - basic lists interface for access to DB 
 - TDBComboBox (create comboboxes)
 - TDBListBox (create listboxes)
 - TDBCheckList (create checked listboxes)
 - TDBRadioGroup ! 
 - TDBFieldset - db-aware Fieldset
 - TDBForm - db-aware Form
 - TDBListControl - db-aware ol/ul/dl list
 - TDBFormData - TFormData with ability to automatically load from/save to DB 
*/

require_once("forms.php");

class TDBList extends TFMLListControl {
  protected $sql;             //an SQL request string
  protected $db;              //TDBH, introduced in v. 1.0
  protected $itemNumType;     //0=xx (none); 1=xx1,xx2; 2=xx[]
  protected $itemNumBase;     //e.g. xx 
  protected $tableName;       //e.g. Countries
  protected $fvalues;         //e.g. name
  protected $ftexts;          //e.g. longname
  protected $fhints;          //e.g. descr. It is a 3rd arg in Query array! 
  protected $urlFld;          //URL field. If set then items are links (not works with combo&list boxes). It is a 4rd arg in Query array!
  protected $customValueFld;  //if set, then value from this field used to populate value attr     
  protected $limits;          //e.g. id>5 
  protected $orderby;         //e.g. id
  protected $customSQL;       //by default do not set anything to this property
/* Two properties below are used to create 2-levels lists (by using optgroup tag for select). 
It's should set mlLvlFld to database field according level, and mlParFld to field according 
parent group. E.g. for table like that:
cat_id  cat_name  parent_id  level
0       Flowers   0          0
1       Trees     1          0
2       Rose      0          1
3       Orchid    0          1
4       Oak       1          1
5       Beech     1          1      
it's should set mlLvlFld to 'level' and mlParFld to 'parent_id'. */
  protected $mlLvlFld;          
  protected $mlParFld;    
  protected $nomaincats;      //if true main categories is used only to set groups
  public function __construct($parent, $name) {
    parent::__construct($parent, $name);
    if (isset($this->parentcontrol->db)) $this->db = $this->parentcontrol->db;
    $this->multilevel = false;
    $this->mlDelim    = '&nbsp;&nbsp;&nbsp;';
    $this->defaultvt  = true;
    $this->nomaincats = false;
  }
  public function SetNumbering($type,$prefix) {
    $this->itemNumType = $type;
    $this->itemNumBase = $prefix;
  }
  public function SetML($levelFld, $parentFld, $nomaincats = false) {
    if ($levelFld === false) {  
      $this->multilevel = false;
    } else {        
      $this->multilevel = true;
      $this->mlLvlFld   = $levelFld;          
      $this->mlParFld   = $parentFld;    
      $this->nomaincats = $nomaincats;
    }
  }
  public function SetCustomSQL($sql) {
    $this->customSQL = $sql; 
  }
  private function MakeSQL() {
    if ($this->customSQL != "") return $this->customSQL; 
    $a3 = "";
    if ($this->IsML()) $a3 = " , ".$this->mlLvlFld." , ".$this->mlParFld;
    if ($this->fhints != "") $a3 .= " , ".$this->fhints;
    if ($this->urlFld != "") $a3 .= " , ".$this->urlFld;
    if ($this->customValueFld != "") $a3 .= " , ".$this->customValueFld;
    $res = "SELECT $this->fvalues, $this->ftexts $a3 from $this->tableName ";
    if ($this->limits != "") $res = $res." WHERE ".$this->limits;
    $orderitems = "";
    if ($this->IsML()) {
      $orderitems .= " $this->mlParFld, $this->mlLvlFld ";
    } 
    if ($this->orderby != "") { 
      if ($orderitems != "") $orderitems .= " , ";
      $orderitems .= $this->orderby;
    }
    if ($orderitems != "") $orderitems = " ORDER BY ".$orderitems;
    $s=$res.$orderitems;
    return $s;
  }
  public function PrintSQL() {
    echo $this->sql;
  }
  protected function IsML() {
    return ( ($this->mlLvlFld != "") && ($this->mlParFld != "") ); 
  }
  public function Query($table = "", $fields = array(), $limits = "", $orderby = "") {
    if ($table != "") $this->tableName   = $table;
    if (count($fields)>0) $this->fvalues = $fields[0];
    if (count($fields)>1) $this->ftexts  = $fields[1];
    if (count($fields)>2) $this->fhints  = $fields[2];
    if (count($fields)>3) $this->urlFld  = $fields[3]; 
    if ($limits != "") $this->limits     = $limits;
    if ($orderby != "") $this->orderby   = $orderby;
    $this->sql = $this->MakeSQL();      
  }
  public function GetComplete() {
    $this->FillList($this->defaultv);
    return parent::GetComplete();
  }
}

class TDBComboBox extends TDBList {
  public function __construct($parent, $name) {
    parent::__construct($parent, $name);
  }
  protected function FillList($default) {
    if (!isset($this->db)) $this->db = $this->GetParentProperty("db"); 
    $this->db->Query($this->sql);
    if ($default!=0) $this->selecteditem = $default;
    $currentgroup = NULL;
    if ($this->customValueFld != "") { $v = $this->customValueFld; $u = $this->basehref; } 
      else { $v = $this->fvalues; $u = ""; }
    while ( ($this->db) && ($row = $this->db->FetchArray()) ) {
      if ($this->IsML()) {
        if ($row[0] == $row[3]) {
          if ($this->groupsTag) {
            $currentgroup = $this->AddGroup($row[1]);
            if (!$this->nomaincats) $currentgroup->AddItem($u.$row["$v"],$row[1]);
          } else {
            $this->AddItem($row["$v"],$row[1]);
          }
        } else {
          if ($this->groupsTag) {
            $currentgroup->AddItem($u.$row["$v"],$row[1]);
          } else {   
            $this->AddItem($row["$v"],$this->mlDelim.$row[1]);
          }           
        }    
      } else {
        $this->AddItem($row["$v"],$row[1]);
      }      
    }
  }
}

class TDBListControl extends TDBList {
  public function __construct($parent, $type = 0) {
    parent::__construct($parent, "");
    if (!$this->db) $this->db = $this->ParentDocument()->db; //because it may be not a form child
    $this->SetType($type);
    $this->mlDelim = "";
    $this->groupsTag = $this->tag;
    $this->nomaincats = false;
  }
  public function SetType($type) {
    switch ($this->type) {
      case 0: $this->tag = "ul"; $this->ChangeItemTag("li"); break;
      case 1: $this->tag = "ol"; $this->ChangeItemTag("li"); break;
      case 2: $this->tag = "dl"; $this->ChangeItemTag("dt"); $this->itemtag2 = "dd";
    }
  }
  public function AddItem($val, $url = "") {
    $ctrl = new TBlockControl($this, $this->itemtag); //$this->CreateControl($this->itemtag);
    $this->AddControl($ctrl);
    if ($url == "") $ctrl->content = $val;
      else $ctrl->AddLink($url,$val); 
    return $ctrl;
  }   
  public function AddGroup() {
    $ctrl = new TListControl($this);
    $ctrl->tag = $this->groupsTag;
    $this->AddControl($ctrl);
    return $ctrl;
  }
  protected function SetSelected() {
    return true;
  }   
  protected function FillList() {
    $this->db->Query($this->sql);
    $currentgroup = NULL;
    while ( ($this->db) && ($row = $this->db->FetchRow()) ) {
      if ($this->IsML()) {
        if (isset($row[4])) $u = $this->basehref.$row[4]; else $u = "";
        if ($row[0] == $row[3]) {
          if ($this->groupsTag) {
            if (!$this->nomaincats) $this->AddItem($row[1],$u);
            $currentgroup = $this->AddGroup();
          } else {
            $this->AddItem($row[1],$u);
          }
        } else {
          if ($this->groupsTag) {
            $currentgroup->AddItem($row[1],$u);
          } else {   
            $this->AddItem($this->mlDelim.$row[1],$u);
          }           
        }    
      } else {
        //not ML way
        if (isset($row[2])) $u = $this->basehref.$row[2]; else $u = "";           
        $this->AddItem($row[1],$u);
      }
    }
  }
  
/*
   function AddItem($txt="", $txt2="") {
     $ctrl = new TBlockControl($this, $this->itemtag);// $this->CreateControl($this->itemtag);
     $this->AddControl($ctrl);
     if ($txt != "") $ctrl->content = $txt;
     if ($this->type == 2) {
       $ctrl = new TBlockControl($this, $this->itemtag2);
       $this->AddControl($ctrl);
       if ($txt2 != "") $ctrl->content = $txt2;     
     } 
     return $ctrl; //returns DD control for DL list
   }
   function AddItems($texts = array(), $texts2 = array()) {
     $i = 0;
     foreach ($texts as $txt) {
       if (isset($texts[$i])) $s = $texts[$i]; else $s = "";
       if (isset($texts2[$i])) $s2 = $texts2[$i]; else $s2 = "";
       $this->AddItem($s,$s2);
       $i++;
     }
   }
*/
} 

class TDBListBox extends TDBComboBox {
  public function __construct($parent, $name, $size = 4) {
    parent::__construct($parent, $name);
    $this->SetAttr("size",$size);
  }
}

class TDBCheckList extends TDBList {
  protected $subitemtype;
  protected $subitemtag;
  protected $styleclass;    
  protected $mlgrpclass; //class to highlight groups
  public function __construct($parent, $name, $styleclass = "", $mlgrpclass = "") {
    parent::__construct($parent, $name);
    $this->hasclose    = true;
    $this->tag         = "div";
    $this->itemtag     = "span";
    $this->itemNumBase = $name;
    $this->itemNumType = 2;
    $this->styleclass  = $styleclass;
    $this->mlgrpclass  = $mlgrpclass; 
    $this->size        = 4;
    $this->SetSubitems();
    $this->defaultv = array();  // Here defaultv is array because multi-select is supported 
  }
  public function AddItem($val) {
    $ctrl = $this->CreateControl($this->itemtag);
    return $ctrl;
  }
  public function SetSubitems($tag = "input", $type = "checkbox") {
    $this->subitemtag  = $tag;
    $this->subitemtype = $type;
  } 
  protected function SetDefAttrs() {
    if ($this->styleclass != "") {
      $this->SetAttr("class",$this->styleclass);
    } else {
      $x = $this->size*1.25;
      if ($x == 0) $x = 5;
      $this->SetAttr("style","height: {$x}em; width: 20em; overflow-y: scroll; border: solid 1px #79A3B0;");
    } 
    $this->size = 0;
    parent::SetDefAttrs();
  }
  protected function FillList($defaults) {
    $this->db->Query($this->sql);
    if (isset($defaults)) $this->defaultv = $defaults;
    $i=0;
    while ( ($this->db) && ($row = $this->db->FetchRow()) ) {
      $hint= "";
      if ($this->itemNumType>0) {
        $i++;
        if ($this->itemNumType==1) $nameattr = $this->itemNumBase.$i;
        if ($this->itemNumType==2) $nameattr = $this->itemNumBase.'[]';
      } else $nameattr = ""; 
      $item = $this->AddItem($row[0],$row[1]);
      if ($this->fhints != "") {
        if ($this->IsML()) $x=4; else $x=2;
        $item->SetAttr("title",$row[$x]);
      }
      $intag = new TInputControl($item, $nameattr, $this->subitemtype);
      if ($this->defaultv != "")
        if ( in_array($row[0],$this->defaultv) ) $intag->SetAttr("checked","checked");
      if ($this->IsML()) {
         if ($row[0] != $row[3]) $intag->endcontent = $this->mlDelim;
           else if ($this->mlgrpclass != "") $item->SetAttr("class",$this->mlgrpclass);
      }   
      $intag->SetAttr("value",$row[0]);
      $intag->endcontent .= $row[1]."<br />";
      $item->AddControl($intag);
    }
  }
}

class TDBRadioGroup extends TDBCheckList {
  public function __construct($parent, $name, $styleclass = "") {
    parent::__construct($parent, $name, $styleclass);
    $this->subitemtype = "radio";
    $this->itemNumType = 0; 
  } 
}

class TDBFieldset extends TFieldset {
  protected $db;
  public function __construct($parent, $legend) {
    parent::__construct($parent, $legend);
    if ($this->parentcontrol->db) $this->db = $this->parentcontrol->db;
  }
  public function AddDBComboBox($name, $table = "", $fields = array(), $selected="", $limits="") {
    $ctrl = new TDBComboBox($this, $name);
    $ctrl->nameasid = $this->nameasid;
    $ctrl->breakln  = $this->breakln;
    $ctrl->SetSel($selected, $this->values);
    if ($table != "") $ctrl->Query($table, $fields, $limits);
    $this->AddControl($ctrl);
    return $ctrl;
  }
  public function AddDBListBox($name, $size=4, $table = "", $fields = array(), $selected="", $limits="") {
    $ctrl=$this->AddDBComboBox($name, $table, $fields, $selected, $limits);
    $ctrl->size = $size;
    return $ctrl;
  }
  public function AddDBCheckList($name, $class = "", $table = "", $fields = array(), $selected="") {
    $ctrl= new TDBCheckList($this, $name, $class);
    $ctrl->nameasid = $this->nameasid;
    $ctrl->breakln  = $this->breakln;
    $ctrl->SetSel($selected, $this->values);
    if ($table != "") $ctrl->Query($table, $fields);         
    $this->AddControl($ctrl);            
    return $ctrl;
  }
  public function AddFieldset($legend) {
    $ctrl = new TDBFieldset($this, $legend);
    $this->AddControl($ctrl);
    $ctrl->breakln  = $this->breakln;
    $ctrl->nameasid = $this->nameasid;
    return $ctrl;
  }
}

class TDBForm extends TForm {
  protected $db;
  public function __construct($parent, $name = "", $script = "", $type = 0) {
    parent::__construct($parent, $name, $script, $type);
    $pd = $this->ParentDocument();
    if (isset($pd) && ($pd->db)) $this->db = $pd->db;
  }
  public function AddDBComboBox($name, $table = "", $fields = array(), $selected="", $limits="") {
    $ctrl = new TDBComboBox($this, $name);
    $ctrl->nameasid = $this->nameasid;
    $ctrl->breakln  = $this->breakln;
    $ctrl->SetSel($selected, $this->values);
    if ($table != "") $ctrl->Query($table, $fields, $limits);
    $this->AddControl($ctrl);
    return $ctrl;
  }
  public function AddDBListBox($name, $size=4, $table = "", $fields = array(), $selected="", $limits="") {
    $ctrl=$this->AddDBComboBox($name, $table, $fields, $selected, $limits);
    $ctrl->size = $size;
    return $ctrl;
  }
  public function AddDBCheckList($name, $class = "", $table = "", $fields = array(), $selected="") {
    $ctrl= new TDBCheckList($this, $name, $class);
    $ctrl->nameasid = $this->nameasid;
    $ctrl->breakln  = $this->breakln;
    $ctrl->SetSel($selected, $this->values);
    if ($table != "") $ctrl->Query($table, $fields);         
    $this->AddControl($ctrl);            
    return $ctrl;
  }
  public function AddFieldset($legend) {
    $ctrl = new TDBFieldset($this, $legend);
    $this->AddControl($ctrl);
    if (isset($this->values)) $ctrl->values = $this->values;
    $ctrl->breakln  = $this->breakln;
    $ctrl->nameasid = $this->nameasid;
    return $ctrl;
  }
  public function MakeAutoData($types = NULL, $tablename="") {
    $this->values = new TDBFormData($this, NULL, $types, $tablename);
//echo " in MakeAutoData values->idfld: ".$this->values->idfld."<br />";    
  }    
  public function AutoProcess($okctrl, $resctrl = NULL) {
    $resdata = $this->GetSubmitted($resctrl);
    if (isset($resdata) && ($resdata!="")) {
//echo "AutoProcess ResetData<br />";
      $this->values->ResetData();
      return;
    }
    $okdata = $this->GetSubmitted($okctrl);
    if (isset($okdata) && ($okdata!="")) {
      $this->values->SaveToDB($this->GetSubmitted($this->values->idfld));
      if (!$this->values->LoadFromDB()) {
        $this->values->ResetData();
      }
    } else {
//echo "AutoProcess values->idfld:".$this->values->idfld."<br />";                 
      $idval = $this->GetSubmitted($this->values->idfld); 
      if (isset($idval)) {
        if (!$this->values->LoadFromDB()) {
          $this->values->ResetData();
        }
      } 
    }
  }
  public function LinkAutoData($formdata, $tablename="") {
    if (isset($formdata)) $this->values = $formdata;  //NULL if MakeAutoData first
    $this->values->LinkToForm($this);
    $this->values->SetDB($tablename, $this->db);
  }    
}

class TDBFormData extends TFormData {
  protected $db;
  protected $tablename;
//  protected $idval; 
  public $idfld;
//  protected $adata; - from superclass 
//  protected $types; - from superclass 
  public function __construct($parent, $method, $types, $tablename = "") { //need to set method & types 
    parent::__construct($parent, $method, $types);
    $this->tablename = $tablename;
    $this->softtypes = false;
    if (isset($parent)) $this->db = $parent->db; //parent should be TDBForm    
    if (isset($types)) $this->SetID(key($types));//$this->idfld = key($types); 
  }
  public function SetDataTypes($types, $soft = false) {
    parent::SetDataTypes($types, $soft);
  }
  public function SetID($idfld = "") { 
  /*** By default key field is first key=>value from the types array ***/
      if ($idfld != "") $this->idfld = $idfld; 
//echo "in SetID this->idval: {$this->GetIdValue()} / this->idfld: $this->idfld<br />";  
  }
  public function GetIdValue() {
    if (isset($this->adata[$this->idfld])) return $this->adata[$this->idfld];
      else { echo "getvalue <b>fail</b>!"; return NULL; }
  }
  public function SetIdValue($value) {
    if (isset($this->idfld)) $this->adata[$this->idfld] = $value;
//echo "!!!!".$this->adata[$this->idfld];    
  }
  public function SetDB($tablename, $db = NULL) {
    if (isset($db)) $this->db = $db;
    $this->tablename = $tablename;
  }
  public function LoadFromDB($id = NULL) {
    if (isset($id)) $this->SetIdValue($id);
    $sql = " select * from $this->tablename where $this->idfld = ".$this->GetIdValue();
//echo "LoadFromDB: $sql<br />";
    if (!$this->db->Query($sql)) return false;
    $dbu = $this->db->FetchObject();
    if (!$dbu) {
      //echo "<b>DBU FAIL</b><br />";
      return false;
    } else ;//echo "<b>DBU OK</b><br />";
    $this->AssignFrom($dbu);
    return true;
  }
  public function SaveToDB($id = NULL) {
    if (($id === NULL) || (trim($id)=="")) {
      $this->SetIdValue($this->InsertToDB($this->tablename));
    } else {
      if (isset($id)) $this->idval = $id;
      $this->UpdateInDB(" where $this->idfld = ".$this->GetIdValue());
    }
//echo "SaveToDB idfld/idval: $this->idfld = {$this->GetIdValue()}<br />";    
  }
  public function UpdateInDB($checkcond) {
    $s = "";
    foreach ($this->adata as $key => $value) {
      if (!isset($this->types[$key])) continue;
      if (substr($this->types[$key],0,3) == "str") {
        $oq = "'";
        $cq = "'";
        $value = str_replace("'","\'",$value);
      } else if ($this->types[$key] == "bcb") {
        $value = intval($value); //"" to 0, "on" to 1
      } else {
        $oq = "";
        $cq = "";
      }
      if ($s != "") $s .= ", ";
      $s .= "$key = $oq{$value}$cq";  
    }
    $sql = "update $this->tablename set ".$s." $checkcond ";
//echo "<b>UPD:</b>".$sql."<br />";    
    return $this->db->Query($sql);
  }
  public function InsertToDB() {
    $flds = "";
    $vals = "";
    foreach ($this->adata as $key => $value) {
      if (!isset($this->types[$key])) continue;
      if (substr($this->types[$key],0,3) == "str") {
        $oq = "'";
        $cq = "'";
      } else if ($this->types[$key] == "bcb") {
        $value = intval($value); //"" to 0, "on" to 1
      } else {
        $oq = "";
        $cq = "";
      }
      if ($flds != "") $flds .= ", ";
      $flds .= "$key";  
      if ($vals != "") $vals .= ", ";
      $vals .= "$oq{$value}$cq";  
    }
    $sql = "insert into $this->tablename ($flds) values ($vals) ";
//echo "<b>INS:</b>".$sql."<br />".intval(isset($this->db))."<br />";    
    if ($this->db->Query($sql)) {
//      echo "Query ok.<br />";
      return $this->db->Lastval();
    } else {
//      echo "Query fail ".$this->db->GetError()."<br />";
      return NULL;
    }
  }
}

?>

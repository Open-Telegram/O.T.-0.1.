<?php
/*
SNK GPCL for PHP (General Purpose Class Library) version 2.1
(c) 2008-2013 SNK Software - www.snkey.net
All rights reserved.

stdcheck.php                         
Standard auth script        

Classes:
- TCheckLogin - helper class for session auth

Typical usage:
include("stdcheck.php");
include("documents.php");
class TCheckLoginDoc extends TDBSDocument {
  var $checker;
  function __construct($title = "Logging in") {
    parent::__construct($title);
    $this->checker = new TCheckLogin($this);
  }
}
$checkdoc = new TCheckLoginDoc();
if ($checkdoc->checker->SimpleCheck("mylogin","mypassword")) {
  $checkdoc->checker->SetSession("private.php",1);
} else {
  $checkdoc->Redirect("index.php",3);
  $checkdoc->KillSession("Wrong login or password");
}
$checkdoc->PrintAll(true); 
*/

include("db.php");

class TCheckLogin extends TDBBase {
  private $login;
  private $passw;
  private $userid;
  private $crypted = false;
  public $extracond;
  public function __construct($parent, $mode = 1, $maxlen = 32) {
    parent::__construct($parent);
    $this->login = '';
    $this->passw = '';
    if ($mode == 1) { 
      if (isset($_POST["elogin"]) && isset($_POST["epasswd"])) {
        $this->login = trim(safestr($_POST["elogin"],$maxlen));
        $this->passw = trim(safestr($_POST["epasswd"],$maxlen));
      }
    } else {
      if (isset($_GET["elogin"]) && isset($_GET["epasswd"])) {
        $this->login = trim(safestr($_GET["elogin"],$maxlen));
        $this->passw = trim(safestr($_GET["epasswd"],$maxlen));
      }
    }
  }
  public function SetSession($redirectpage = "", $wait = 1) {
    $parentdoc = $this->ParentDocument();
    $parentdoc->session->SetUserdata($this->userid,$this->login);
    if ($redirectpage != "") $parentdoc->Redirect($redirectpage,$wait);
  }
  public function CheckIn() {
    return ( ($this->login != '') && ($this->passw != '') );
  }
  public function SimpleCheck($rlogin,$rpassw) {
    if ( ($rlogin == '') || ($rpassw == '') || (!$this->CheckIn()) ) return false;
    if ( ($this->login == $rlogin) && ($this->passw == $rpassw) ) {
      $this->userid = 1;
      return true;
    } else return false;
  }
  public function DBCheckPlain($table,$fuser,$fpass,$fid) {
    if ( ($table == '') || ($fuser == '') || ($fpass == '') || ($fid == '') || (!$this->CheckIn()) ) return false;
    if ($this->crypted) $pw = "MD5('$this->passw')"; else $pw = "'$this->passw'"; 
    $sql = "select $fid from $table where $fuser = '$this->login' and $fpass = $pw ".$this->extracond;
    $this->connect();
    $this->db->Query($sql);
    $row = $this->db->FetchRow();
    $this->userid = intval($row[0]);
    return ($this->userid > 0);  
  }
  public function DBCheckMD5($table,$fuser,$fpass,$fid) {
    $this->crypted = true;
    return $this->DBCheckPlain($table,$fuser,$fpass,$fid);
  }
}
 
?>

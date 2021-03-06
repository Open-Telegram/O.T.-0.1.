<?php
/*
SNK GPCL for PHP (General Purpose Class Library) version 2.1
(c) 2008-2013 SNK Software - www.snkey.net
All rights reserved.

db.php
DB-aware classes and functions 
Classes:
 - TDBH - DB helper class
 - TDBBase - default abstract class for "non-control" components 
*/
require_once("const.php");
require_once(CValues::$lang.".inc");

class TDBH extends TObject {
  private $dbname;     //database name
  private $dbuser;     //login
  private $dbpass;     //pass
  private $host;       //host
  private $port;       //port
  private $charset;    //db charset, in SQL syntax
  private $engine;     //0 = mysql, 1 = pg, 2 = oci, 3 = ib/fb, 4 = mysqli
  private $flink;      //link to DB
  private $lastres;    //new in 1.6: obtain $res is not required now  
  private $stoponerror;//if true then calls die() else returns false
  protected $qcount;   //counter
  protected $sql;      //last query string 
  public function __construct($usr = '', $pwd = '', $host = 'localhost', $db = "", $engine = 0) {
    $this->dbuser = $usr;
    $this->dbpass = $pwd;
    $this->host = $host;
    $this->charset = CValues::$dbcharset;
    $this->engine = $engine;
    $this->dbname = $db;
    $this->flink = NULL;
    $this->stoponerror = true;
    $this->qcount = 0;
  }
  public function link($stoponerror = true, $alwaysconnect = false) {
    $this->stoponerror = $stoponerror; 
    if (($this->flink == NULL) || ($alwaysconnect)) $this->connect();
    return $this->flink;
  } 
  public function GetError() {
    switch ($this->engine) {
      case 1: { 
        return pg_last_error();
      }
      case 2: { 
        $e = oci_error();
        return $e['code'].": ".$e['message'];
      }
      case 3: {
        return ibase_errcode().": ".ibase_errmsg();
      }
      case 4: {
        return mysqli_errno().": ".mysqli_error();
      }
      default: return mysql_errno().": ".mysql_error();
    }
  }
  //Fast switch database for current connection. Useful for MySQL databases. 
  public function SwitchDB($dbname) {
    $this->dbname = $dbname;
    switch ($this->engine) {
      case 1: { 
        $this->connect();
      }
      case 2: { 
        $this->connect();
      }
      case 3: {
        $this->connect();
      }
      case 4: {
        mysqli_select_db($this->flink, $this->dbname);
      }
      default: mysql_select_db($this->dbname, $this->flink);
    }
  }
  //only mysql and postgresql supported now
  public function Lastval($val = null) {
    if (!$this->flink) return false;
    switch ($this->engine) {
      case 1: { 
        $x = pg_last_oid($val); //means $var is query result. Works only if OID field is enabled.
        if (!$x) {
          //means what $val is a SQL query contains INSERT with nextval() 
          $regExp = preg_match_all("/nextval\('([a-zA-Z0-9_]+)'\)/",$val,$a);
          $seq = $a[1][0];
          $sql = "SELECT currval('$seq')";
          $qry = pg_query($this->flink,$sql);
          if ($qry) {
            $id = pg_fetch_array($qry,null,PGSQL_NUM);
            $x = $id[0];
          } else {
            $x = false;
          }
        }
        return $x; 
      }
      case 2: { 
        return false;
      }
      case 3: {
        return false;
      }
      case 4: {
        return mysqli_insert_id($this->flink);
      }
      default: return mysql_insert_id($this->flink);
    }
  }
  public function FetchObject($res = NULL) {
    if (!$this->flink) return false;
    if (!$res) $res = $this->lastres;
    if (!$res) return false;
    switch ($this->engine) {
      case 1: { 
        return pg_fetch_object($res);
      }
      case 2: { 
        return oci_fetch_object($res);
      }
      case 3: {
        return ibase_fetch_object($res);
      }
      case 4: {
        return mysqli_fetch_object($res);
      }
      default: return mysql_fetch_object($res);
    }
  }
  public function FetchRow($res = NULL) {
    if (!$this->flink) return false;
    if (!$res) $res = $this->lastres;
    if (!$res) return false;
    switch ($this->engine) {
      case 1: { 
        return pg_fetch_row($res);
      }
      case 2: { 
        return oci_fetch_row($res);
      }
      case 3: {
        return ibase_fetch_row($res);
      }
      case 4: {
        return mysqli_fetch_row($res);
      }
      default: return mysql_fetch_row($res);
    }
  }
  public function FetchArray($res = NULL) {
    if (!$this->flink) return false;
    if (!$res) $res = $this->lastres;
    if (!$res) return false;
    switch ($this->engine) {
      case 1: { 
        return pg_fetch_array($res);
      }
      case 2: { 
        return oci_fetch_array($res);
      }
      case 3: {
        return ibase_fetch_assoc($res);
      }
      case 4: {
        return mysqli_fetch_array($res);
      }
      default: return mysql_fetch_array($res);
    }
  }
  public function Query($sql) {
    if (!$this->flink) return false;
    $this->qcount++;
    $this->sql = $sql;
    switch ($this->engine) {
      case 1: { 
        $this->lastres = pg_query($this->flink, $sql);
        if ($this->lastres) return $this->lastres; else return false; 
      }
      case 2: { 
        $stid = oci_parse($this->flink, $sql);
        if (oci_execute($stid)) $this->lastres = $stid; else return false;
        if ($this->lastres) return $this->lastres; else return false; 
      }
      case 3: {
        $this->lastres = ibase_query($this->flink, $sql);
        if ($this->lastres) return $this->lastres; else return false; 
      }
      case 4: {
        $this->lastres = mysqli_query($this->flink, $sql);
        if ($this->lastres) return $this->lastres; else return false; 
      }
      default: {
        $this->lastres = mysql_query($sql, $this->flink);
        if ($this->lastres) return $this->lastres; else return false; 
      }
    }
  }
  public function SelectedRows($res = NULL) {
    if (!$this->flink) return 0;
    if (!$res) $res = $this->lastres;
    if (!$res) return 0;
    switch ($this->engine) {
      case 1: { 
        return pg_num_rows($res);
      }
      case 2: { 
        $sql = $this->sql;
        $this->Query('SELECT COUNT(*) AS NUM_ROWS FROM ('.$sql.')');
        $a = $this->FetchRow();
        $r = intval($a['NUM_ROWS']);
        $this->sql = $sql;
        return $r;
      }
      case 3: {
        return -1; //check this
      }
      case 4: {
        return -1; //check this
      }
      default: return mysql_num_rows($res);
    }
  }               
  public function AffectedRows($res = NULL) {
    if (!$this->flink) return 0;
    if (!$res) $res = $this->lastres;
    if (!$res) return 0;
    switch ($this->engine) {
      case 1: { 
        return pg_affected_rows($res);
      }
      case 2: { 
        return oci_num_rows($res);
      }
      case 3: {
        return -1; //check this
      }
      case 4: {
        return -1; //check this
      }
      default: return mysql_affected_rows($this->flink);
    }
  } 
  public function connect($db = '', $usr = '', $pwd = '', $hst = '') {
    if ($db  != '') $this->dbname = $db;
    if ($usr != '') $this->dbuser = $usr;
    if ($pwd != '') $this->dbpass = $pwd;
    if ($hst != '') $this->host   = $hst;
    if ($this->host   == '') $this->host   = CValues::$server;
    if ($this->dbname == '') $this->dbname = CValues::$dbname;
    if ($this->dbuser == '') $this->dbuser = CValues::$dbuser;
    if ($this->dbpass == '') $this->dbpass = CValues::$dbpass;
    switch ($this->engine) {
      case 1: { 
        $cs = "";
        if ($this->host != "")     $cs .= "host=$this->host ";
        if ($this->port != "")     $cs .= "port=$this->port ";
        if ($this->database != "") $cs .= "dbname=$this->database ";
        if ($this->dbuser != "")   $cs .= "user=$this->dbuser ";
        if ($this->dbpass != "")   $cs .= "password=$this->dbpass ";
        $link = pg_connect($cs);
        break;
      }
      case 2: { 
        $link = oci_connect($this->dbuser,$this->dbpass,$this->database,$this->charset);
        break;
      }
      case 3: {
        $link = ibase_connect($this->database,$this->dbuser,$this->dbpass,$this->charset);
        break;
      }
      case 4: {
        $link = mysqli_connect($this->host,$this->dbuser,$this->dbpass,$this->database);
        if ($this->charset != "") { 
          mysqli_query($link, "set character set {$this->charset}");
          $this->qcount++;
        }
        break;
      }
      default: {
        $link = mysql_connect($this->host,$this->dbuser,$this->dbpass);
        if ($this->dbname != "") mysql_select_db($this->dbname, $link);
        if ($this->charset != "") {
          mysql_query("set character set {$this->charset}", $link);
          $this->qcount++;
        }
      }
    }
    if (!$link) {
      if ($this->stoponerror) die(lc_errconect.$this->GetError()); else return false;
    }
    $this->flink = $link;
    return $link;  
  }    
}

abstract class TDBBase extends TComponent {
  protected $db;
  public function __construct($parent, $db = NULL) {
    parent::__construct($parent);
    if (isset($db)) $this->db = $db;
  } 
  public function connect($db_name = "", $db_user = "", $db_pw = "", $db_host = "") {
    $this->db = new TDBH($db_user, $db_pw, $db_host, $db_name);
    return $this->db->link(false);
  }
}

?>

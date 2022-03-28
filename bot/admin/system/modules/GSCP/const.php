<?php
/*
SNK GPCL for PHP (General Purpose Class Library) version 2.1
(c) 2008-2013 SNK Software - www.snkey.net
All rights reserved.

const.php
Classes:
 - CValues - Set of site-specific default values. Fill free to edit these values.
*/

class CValues {
///**** Language ****///
//Language. Used in the HTML lang attribute and string resources
  public static $lang = "en";
//output HTML charset attribute (defaultcharset on server-side)   
  public static $charset = "Windows-1252";                  
//source document character set
  public static $incharset = "Windows-1252";         
//if set then string values will converted between charset & incharset 
  public static $autoconv = false;
//DB charset                     
  public static $dbcharset = "utf8";
//document type. One of: HTML4, HTML4S, XHTML1, XHTML1S, XTHTML11, HTML5               
  public static $doctype = "XHTML1S";
//copyright string (if autofooter enabled)                 
  public static $copyright = "&copy; 2013 All rights reserved";
///**** Website-specific ****///
//site name
  public static $sitename = "My Site";                
//site URI
  public static $siteuri = "http://www.snkey.net/"; 
//CSS filename to automatic include
  public static $defcss = "default.css";             
//JavaScript filename to automatic include
  public static $defjs = "";              
//replace this value to actual depth related to www root, if required
  public static $basedepth = 1;
//path to upload directory, should have 0777 in *nix                      
  public static $uploadbase = "pub";             
///**** DB-aware ****///
//mysql (0) by default, 1 = postgresql, 2 = oracle, 3 = firebird, 4 = mysqli
  public static $dbengine = 0;                       
//database server (host, or path to file)
  public static $server = "localhost";
//database name               
  public static $dbname = "";              
//database user name
  public static $dbuser = "";                 
//database user password
  public static $dbpass = "";     
}

require_once("cconst.php");
?>

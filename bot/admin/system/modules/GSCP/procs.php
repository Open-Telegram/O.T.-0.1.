<?php
/*
SNK GPCL for PHP (General Purpose Class Library) version 2.1
(c) 2008-2013 SNK Software - www.snkey.net
All rights reserved.

procs.php
Routines:
 - SafeStr - check maxinum string length and remove dangerous symbols 
 - MonAsStr - replcaes integer to mouhth  in Russian
 - WeekAsStr - replcaes integer to weekday in Russian
 - RStringDay - returns date in Russian like "Monday, 14 June 2010" 
 - q2html - replace double quotes to &quot;    
 - AddNulls - adds a 0's before number (e.g. 15 -> 0015, 127 -> 0127)
 - HumanBytes - replaces bytes to kilo- mega- or giga-bytes
 - CheckAlphaNumStr - check string for alphanumeric symbols only
 - GetAlphaNumStr - return string with alphanumeric symbols only
 - NumToPath - replaces a number to a string like nix path, e.g 135 to /1/3/5
 - DateToPath - replaces a date to a string like nix path, e.g may 25, 2011 to /2011/5/25 
 - cc - performs a charset converson on string and arrays (incl. multi-dimensional)
 - ccb - like cc but performs backward conversion
 - StripSpaces - removes all extra spaces from the string
 - InPost/InGet/InPostGet - check data exists in post, in get, or in post and/or get global arrays
 - AddSlash - adds slash (/) to end of string if it not present         
*/

require_once('const.php');

function AddSlash($path) {
  if ($path[strlen($path)-1] != '/') $path .= '/'; 
  return $path;
}

function InPost($fname) {
  return isset($_POST[$fname]);
}

function InGet($fname) {
  return isset($_GET[$fname]);
}

function InPostGet($fname, $inpost = true, $inget = true, $both = false) {
  if ($both) return ( ($inpost && InPost($fname)) && ($inget && inGet($fname)) ); 
    else return ( ($inpost && InPost($fname)) || ($inget && inGet($fname)) ); 
}

function cc($val) {
  if (CValues::$incharset != CValues::$charset) {
    if (is_array($val)) {
      foreach ($val as &$val_el) $val_el = cc($val_el);
      return $val; 
    } else return iconv(CValues::$incharset, CValues::$charset, $val);
  } else return $val;
}

function acc($val) {
  if (CValues::$autoconv) return cc($val);
    else return $val; 
}

function ncc($val) {
  if (!CValues::$autoconv) return cc($val);
    else return $val; 
}

function ccb($val) {
  if (CValues::$incharset != CValues::$charset) {
    if (is_array($val)) {
      foreach ($val as &$val_el) $val_el = ccb($val_el);
      return $val; 
    } else return iconv(CValues::$charset, CValues::$incharset, $val);
  } else return $val;
}

function SafeStr($str,$maxlen=255,$notags=true) {
  if ($notags) $str=strip_tags($str);
  //$str=str_replace('\'','',$str);
  if ($maxlen == 0) $maxlen = 255; 
  return mb_substr($str,0,$maxlen-1,CValues::$charset);
}

function StripSpaces($str) {
  return preg_replace("'\s+'"," ",$str);
}

function CheckAlphaNumStr($str,$maxlen=255) {
  $str = substr(trim($str),0,$maxlen);
  return (preg_match("/^[0-9a-zA-Z]+$/", $str)); 
}

function GetAlphaNumStr($str,$maxlen=255) {
  $str = substr(trim($str),0,$maxlen);
  preg_match("/^[0-9a-zA-Z]+$/", $str, $matches);
  return implode($matches); 
}

function SafeCode($str){
  return str_replace('&','&amp;',$str); 
}

function CutWords($str,$maxlen,$finisher = "...") {
  if (strlen($str) > $maxlen) { 
    $str = mb_substr($str,0,$maxlen,CValues::$charset);
    while (($str[strlen($str)-1] != ' ') && (strlen($str) > 1)) $str = mb_substr($str,0,strlen($str)-2,CValues::$charset);
    $str = mb_substr($str,0,strlen($str)-1,CValues::$charset);
    $str.=$finisher;
  }
  return $str;
}

function q2html($val) {
  return str_replace('"','&quot;',$val);
} 

function t2html($val) {
  $val = str_replace('<','&lt;',$val); 
  return str_replace('>','&gt;',$val);
} 

function DateToPath($dt = 0) {
  if ($dt == 0) $dt = time();
  return date('Y/m/d', $dt); 
}

function NumToPath($str) {
  $path = "";
  $str=strval($str);
  for ($i=0; $i<strlen($str); $i++) {
    if ( in_array($str[$i], array(0,1,2,3,4,5,6,7,8,9)) ) {
      $path .= $str[$i]."/";
    } 
  }           
  return $path;
}

function HumanBytes($bytes, $mode = "a2") {
  $mode = strtolower($mode);
  if (strlen($mode)>1) $pc = intval($mode[1]); else $pc = 2; 
  if (strlen($mode)>0) $mode = $mode[0]; else $mode = "a";
  if (($mode == "b") || (($mode == "a") && ($bytes < 1024))) return $bytes."b";
  if (($mode == "k") || (($mode == "a") && ($bytes < 1024*1024))) return round($bytes/1024,$pc)."Kb"; 
  if (($mode == "m") || (($mode == "a") && ($bytes < 1024*1024*1024))) return round($bytes/1024/1024,$pc)."Mb"; 
  if (($mode == "g") || (($mode == "a") && ($bytes < 1024*1024*1024*1024))) return round($bytes/1024/1024/1024,$pc)."Gb"; 
  return round($bytes/1024/1024/1024/1024,$pc)."Tb";
} 

function AddNulls($num, $len) {
  $new = strval($num);
  while (strlen($new) < $len) $new = "0".$new;
  return $new;
}

function RStringDay($date) {
  $d = date("j");
  $wd = date("w");
  $mon = date("n");
  $year = date("Y");
  return WeekAsStr($wd,0).", $d ".MonAsStr($mon,1)." $year";
}

function MonAsStr($mon, $rcase = 0) {
  if (CValues::$lang == "ru") {
    if ($rcase == 0) {
      switch ($mon) {
        case 1: $s = "������"; break;
        case 2: $s = "�������"; break; 
        case 3: $s = "����"; break;
        case 4: $s = "������"; break;
        case 5: $s = "���"; break;
        case 6: $s = "����"; break;
        case 7: $s = "����"; break;
        case 8: $s = "������"; break;
        case 9: $s = "��������"; break;
        case 10: $s = "�������"; break;
        case 11: $s = "������"; break;
        case 12: $s = "�������";
      };
    } else if ($rcase == 1) {
      switch ($mon) {
        case 1: $s = "������"; break;
        case 2: $s = "�������"; break; 
        case 3: $s = "�����"; break;
        case 4: $s = "������"; break;
        case 5: $s = "���"; break;
        case 6: $s = "����"; break;
        case 7: $s = "����"; break;
        case 8: $s = "�������"; break;
        case 9: $s = "��������"; break;
        case 10: $s = "�������"; break;
        case 11: $s = "������"; break;
        case 12: $s = "�������";
      };
    } else if ($rcase == 2) {
      switch ($mon) {
        case 1: $s = "������"; break;
        case 2: $s = "�������"; break; 
        case 3: $s = "�����"; break;
        case 4: $s = "������"; break;
        case 5: $s = "���"; break;
        case 6: $s = "����"; break;
        case 7: $s = "����"; break;
        case 8: $s = "�������"; break;
        case 9: $s = "��������"; break;
        case 10: $s = "�������"; break;
        case 11: $s = "������"; break;
        case 12: $s = "�������";
      };
    }
    return $s;
  } 
    else 
  {
    return date("F",mktime(0, 0, 0, $mon, 1, 2010));
  }
}

function WeekAsStr($wd, $rcase = 0) {
  if (CValues::$lang == "ru") {
    if ($rcase == 0) {
      switch ($wd) {
        case 1: $s = "�����������"; break;
        case 2: $s = "�������"; break; 
        case 3: $s = "�����"; break;
        case 4: $s = "�������"; break;
        case 5: $s = "�������"; break;
        case 6: $s = "�������"; break;
        case 0: $s = "�����������"; 
      };
    } else if ($rcase == 1) {
      switch ($wd) {
        case 1: $s = "�����������"; break;
        case 2: $s = "�������"; break; 
        case 3: $s = "�����"; break;
        case 4: $s = "�������"; break;
        case 5: $s = "�������"; break;
        case 6: $s = "�������"; break;
        case 0: $s = "�����������";
      }; 
    } else if ($rcase == 2) {
      switch ($wd) {
        case 1: $s = "������������"; break;
        case 2: $s = "��������"; break; 
        case 3: $s = "�����"; break;
        case 4: $s = "��������"; break;
        case 5: $s = "�������"; break;
        case 6: $s = "�������"; break;
        case 0: $s = "�����������"; 
      };
    }
    return $s;
  } 
    else 
  {
    $a = getdate(mktime(0, 0, 0, 8, $wd+1, 2011));
    return $a["weekday"]; 
  }
}

?>

<?PHP
// selfmaking html documentation for pXw4Pa v1.0
/*
THIS FILE IS PART OF THE PXW4PA SOFTWARE
AND IT IS RELEASED UNDER THE TERMS OF THE
CC GNU GPL v.2 LICENSE

pXw4Pa - POOR XML WRAPPER FOR PHP ARRAYS v 1.0
Copyright (C) 2005/2006 yayo (Roberto Correzzola)

This program is free software; you can redistribute it and/or modify it
under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License,
or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the
Free Software Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.
*/

require 'pXw4Pa.php';
$_lang=$_GET['lang'];
$doc=pXw4Pa_read('pXw4Pa.doc.xml');
$flag_pic_alttxt=array('English'=>'bandiera Inglese','Italiano'=>'Italian flag');

if (!$_lang or !array_key_exists($_lang,$doc)) $_lang='uk';
if ($_GET['create_file']=='test') {
	if (!fopen('test.xml','w')) {$writing_test_result=$doc[$_lang]['messages']['writing_test_error'];}
	else{pXw4Pa_write($doc,'test.xml');$writing_test_result=$doc[$_lang]['messages']['writing_test_ok'];}
};
$tr=array('[WTR]'=>$writing_test_result,'[PATH]'=>basename(__file__).'#form','[LANG]'=>$_lang,'&#39;'=>"'",'&#34;'=>'"','&#60;'=>'<','&#62;'=>'>','&#38;'=>'&');

$display='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html lang="'.$_lang.'"><HEAD><META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"><META name="keywords" content="PHP array XML write read function script code data"><META name="description" content="pXw4Pa are 2 really simple PHP functions that can read (and/or write) a PHP array from (or to) an XML file."><META name="author" content="yayo (Roberto Correzzola)"><META name="copyright" content="(C) 2005/2006 yayo (Roberto Correzzola)"><META name="distribution" content="global"><META name="robots" content="all"><META name="rating" content="general"><META http-equiv="content-language" content="'.$_lang.'"><TITLE>pXw4Pa - Poor XML Wrapper for PHP Arrays - v1.0</TITLE><LINK REL="stylesheet" TYPE="text/css" HREF="pXw4Pa.doc.css"></HEAD><body><table width="100%"><tr><td align="center"><table><tr><td><a name="top"></a>';
foreach($doc[$_lang] as $k=>$v){
	if ($k!='header' and $k!='messages') {
		if ($k=='language') {
		$display.='<p class="content_title"><span class="content_title">'.$v['group_label'].'</span></p>';
		foreach($doc as $langk=>$langv) {if($langk!=$_lang) $display.='<p><a class="link" href="'.basename(__file__).'?lang='.$langk.'"><img src="'.substr($langv['language']['language'],0,2).'.png" alt="'.$flag_pic_alttxt[$langv['language']['language']].'">&nbsp;'.$langv['language']['language'].'</a></p>';}
		}else{
		$display.='<p class="'.$k.'"><span class="'.$k.'">'.$v['group_label'].'</span></p>';
		}
	}
		foreach($v as $k2=>$v2){
			if (is_array($v2)){
				foreach($v2 as $k3=>$v3){
					if ($k3=='html') {$v3=strtr($v3,$tr);}else{$v3=htmlentities($v3);$display.='<p class="'.$k.'_'.$k3.'"><span class="'.$k.'_'.$k3.'">';}
					if ($k3=='title') {$display.='<a name="'.substr(preg_replace('`[^a-z]`Usi','',$v3),0,20).'"></a>';}
					elseif ($k3=='back') {$display.='<a href="#top">';}
					elseif ($k3=='link') {$display.='<a href="#'.substr(preg_replace('`[^a-z]`Usi','',$v3),0,20).'">';}
					$display.=str_replace("\n",'<br>',$v3);
					if ($k3=='back' or $k3=='link') {$display.='</a>';}
					if ($k3!='html') $display.='</span></p>';
				}
			}
		}
}
$display.='</td></tr></table></td></tr></table></body></html>';
#echo '<pre>';print_r($doc);
echo $display;
?>

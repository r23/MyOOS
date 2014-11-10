<?PHP
/*
pXw4Pa - poor XML wrapper for PHP arrays v 1.0
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

//————————————  READING FUNCTION ——————————————
function pxw4pa_read($file){
	global 	$_pXw4Pa;
	unset($_pXw4Pa['a'],
		$_pXw4Pa['n'],
		$_pXw4Pa['t'],
		$_pXw4Pa['gn'],
		$_pXw4Pa['pos'],
		$_pXw4Pa['status'],
		$_pXw4Pa['val']);

	$argsnum=func_num_args();
	$args=func_get_args();
	foreach($args as $k=>$v){
		if (!is_array($v) and strtolower($v)=='?v') $verbose='v';
		if (!is_array($v) and strtolower($v)=='?v+') $verbose='v+';
	}


	if (!$file) exit('<div style="width:90%;background-color:#FFDA74;color:#000000;font: 12pt;border:1px solid #000000;padding: 2px 12px 2px 12px;"><b>pXw4Pa ERROR!</b>&nbsp;&nbsp;I miss the array! It seems you forget to submit it to the function!</div>');


	$xml_parser = xml_parser_create();
	xml_parser_set_option ($xml_parser,2,'ISO-8859-1');
	
	xml_set_element_handler($xml_parser,
	create_function(
		'$parser, $current_element, $current_attribs',
			'global 	$_pXw4Pa;
	
			if ($current_element=="GROUP"){
				$_pXw4Pa["gn"][$_pXw4Pa["pos"]]=$current_attribs["NAME"];
				$_pXw4Pa["pos"]++;
			}
			elseif ($current_element=="ENTRY"){
				$_pXw4Pa["status"]=true;
				$_pXw4Pa["n"]=$current_attribs["NAME"];
				if (!($_pXw4Pa["t"]=$current_attribs["TYPE"])) $_pXw4Pa["t"]="string";
				if ($_pXw4Pa["t"]==="NULL") {
					if ($_pXw4Pa["n"]) {
						$a[$_pXw4Pa["pos"]][$_pXw4Pa["n"]]=null;}else{$a[$_pXw4Pa["pos"]][]=null;
					}
				}
			}'
	),create_function(
		'$parser, $current_element',
			'global 	$_pXw4Pa;
			if ($current_element=="GROUP"){
				$_pXw4Pa["pos"]--;
				if ($_pXw4Pa["gn"][$_pXw4Pa["pos"]]) {
					$_pXw4Pa["a"][$_pXw4Pa["pos"]][$_pXw4Pa["gn"][$_pXw4Pa["pos"]]]=$_pXw4Pa["a"][$_pXw4Pa["pos"]+1];
				}else{
					$_pXw4Pa["a"][$_pXw4Pa["pos"]][]=$_pXw4Pa["a"][$_pXw4Pa["pos"]+1];
				}
				unset($_pXw4Pa["a"][$_pXw4Pa["pos"]+1],$_pXw4Pa["gn"][$_pXw4Pa["pos"]]);

			}elseif ($current_element=="ENTRY"){
				if ($_pXw4Pa["n"]) {
					$_pXw4Pa["a"][$_pXw4Pa["pos"]][$_pXw4Pa["n"]]=$_pXw4Pa["val"];
				}else{
					$_pXw4Pa["a"][$_pXw4Pa["pos"]][]=$_pXw4Pa["val"];
				}
					end($_pXw4Pa["a"][$_pXw4Pa["pos"]]);
					settype($_pXw4Pa["a"][$_pXw4Pa["pos"]][key($_pXw4Pa["a"][$_pXw4Pa["pos"]])],$_pXw4Pa["t"]);
				$_pXw4Pa["val"]="";
				$_pXw4Pa["status"]=false;
			}'
	)
);
	xml_set_character_data_handler($xml_parser,
	create_function(
		'$parser, $data',
			'global 	$_pXw4Pa;
			if ($_pXw4Pa["status"]) {
				$_pXw4Pa["val"].=$data;
			}'
	)
);
	
	if (!($fp = fopen($file, "r"))) {
	    die('<div style="width:90%;background-color:#FFDA74;color:#000000;font: 12pt;border:1px solid #000000;padding: 2px 12px 2px 12px;"><b>pXw4Pa ERROR!</b>&nbsp;&nbsp;Cannot open the input XML file!</div>');
	}
	
	$data = fread($fp, filesize($file));
	    if (!xml_parse($xml_parser, $data, feof($fp))) {
		  die(sprintf("<div style=\"background-color:#FFDA74;color:#000000;font: 12pt;border:1px solid #000000;padding: 2px 12px 2px 12px;\"><b>pXw4Pa ERROR!</b>&nbsp;&nbsp;...on the XML file: <b>%s</b> at line <b>%d</b></div>",
				  xml_error_string(xml_get_error_code($xml_parser)),
				  xml_get_current_line_number($xml_parser)));
	    }
	
	xml_parser_free($xml_parser);
		if ($verbose) {
			echo'<div style="width:90%;background-color:#A0CBFF;color:#000000;font: 12pt;border:1px solid #000000;padding: 2px 12px 2px 12px;"><b>pXw4Pa info!</b>&nbsp;&nbsp;File <span style="font-family:monospace;">'.$filename."</span> loaded:</div><pre>";
			if ($verbose=='v') print_r($_pXw4Pa['a'][0][0]);
			if ($verbose=='v+') var_dump($_pXw4Pa['a'][0][0]);
			echo "</pre>";
			}


return ($_pXw4Pa['a'][0][0]);

}




//————————————  WRITING FUNCTION ——————————————
function pxw4pa_write($a){
$tr=array('<'=>'&#60;','>'=>'&#62;','"'=>'&#34;','&'=>'&#38;',);
			global 	$_pXw4Pa;
if (!$_pXw4Pa['dtdfilename']) $_pXw4Pa['dtdfilename']='pXw4Pa.dtd';
	$argsnum=func_num_args();
	$args=func_get_args();
	foreach($args as $k=>$v){
		if (!is_array($v) and strtolower($v)=='?v') $verbose=true;
		if (!is_array($v) and strpos($v,'?')===false) {$filename=$v;}
		if (!is_array($v) and strtolower($v)=='?dtd' or $_pXw4Pa['dtdfilename']!='pXw4Pa.dtd') $_pXw4Pa['dtd']='<!DOCTYPE pXw4Pa SYSTEM "'.$_pXw4Pa['dtdfilename'].'">'."\n";
		if (!is_array($v) and substr(strtolower($v),0,5)=='?css=' and !$_pXw4Pa['xsl']) $_pXw4Pa['css']=substr(strtolower($v),5);
		if (!is_array($v) and substr(strtolower($v),0,5)=='?xsl='){ $_pXw4Pa['xsl']=substr(strtolower($v),5); unset($_pXw4Pa['css']);}
	}
if ($_pXw4Pa['css']) $_pXw4Pa['css']='<?xml-stylesheet type="text/css" href="'.$_pXw4Pa['css'].'"?>'."\n";
if ($_pXw4Pa['xsl']) $_pXw4Pa['xsl']='<?xml-stylesheet type="text/xsl" href="'.$_pXw4Pa['xsl'].'"?>'."\n";

	if (!$a) exit('<div style="width:90%;background-color:#FFDA74;color:#000000;font: 12pt;border:1px solid #000000;padding: 2px 12px 2px 12px;"><b>pXw4Pa ERROR!</b>&nbsp;&nbsp;I miss the array! It seems you forget to submit it to the function!</div>');
	if (!$filename) $filename='pXw4Pa_output.xml';
		
	$xml.='<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>'."\n".$_pXw4Pa['dtd'].$_pXw4Pa['css'].$_pXw4Pa['xsl'].'<pXw4Pa version="1.0">'."\n\t".'<group>';
$x[1]=&$a;
$lev=1;

	while($x){
		foreach($x[$lev] as $k=>$v){
			if($jump){
			unset($x[$lev][$k],$jump);
			}
			elseif (is_array($v)){
				$xml.="\n";
				for ($i=0;$i<=$lev;$i++){$xml.="\t";}
				$xml.='<group name="'.$k.'">';
				$x[$lev+1]=&$x[$lev][$k];
				$lev++;
				break;
			}
			else{
				$t=gettype($v);
				if($t!='NULL'){$e='>'.strtr($v,$tr).'</entry>';}else{$e='/>';}
				$xml.="\n";for ($i=0;$i<=$lev;$i++){$xml.="\t";}
				$xml.='<entry name="'.$k.'" type="'.$t.'"'.$e;
				unset($x[$lev][$k]);
			}
				if(!$x[$lev]) {
					$xml.="\n";
					for ($i=1;$i<=$lev;$i++){$xml.="\t";}
					$xml.='</group>';
					unset($x[$lev]);
					$lev--;
					$jump=TRUE;
					break;
				}

		}
	}
$xml.="\n</pXw4Pa>";

	if (!$file=fopen($filename,'w+')) {exit('<div style="width:90%;background-color:#FFDA74;color:#000000;font: 12pt;border:1px solid #000000;padding: 2px 12px 2px 12px;"><b>pXw4Pa ERROR!</b>&nbsp;&nbsp;Cannot create/open the file!</div>');}
	else{
		fwrite($file,$xml);
		if ($verbose) echo'<div style="width:90%;background-color:#A0CBFF;color:#000000;font: 12pt;border:1px solid #000000;padding: 2px 12px 2px 12px;"><b>pXw4Pa info!</b>&nbsp;&nbsp;File <span style="font-family:monospace;">'.$filename."</span> written:</div><pre>".strtr($xml,array('>'=>'&gt;','<'=>'&lt;'))."</pre>";
		return;
	}
}


//————————————  END OF FILE ——————————————
?>
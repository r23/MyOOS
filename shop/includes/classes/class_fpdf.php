<?php
/* ----------------------------------------------------------------------
   $Id: class_fpdf.php,v 1.1 2007/06/07 16:06:31 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Radek HULAN
   http://hulan.info/blog/
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  /**
   * {@link http://www.fpdf.org/ The official site for fdpf}
   */
   define('FPDF_FONTPATH', 'includes/classes/fpdf/font/');

   require 'includes/classes/fpdf/fpdf.php';


  /**
   * global functions
   */
   function hex2dec($color = "#000000"){
      $tbl_color = array();
      $tbl_color['R']=hexdec(substr($color, 1, 2));
      $tbl_color['G']=hexdec(substr($color, 3, 2));
      $tbl_color['B']=hexdec(substr($color, 5, 2));
      return $tbl_color;
   }

   function px2mm($px){
      return $px*25.4/72;
   }

   function txtentities($html){
     $trans = get_html_translation_table(HTML_ENTITIES);
     $trans = array_flip($trans);
     return strtr($html, $trans);
   }



  /**
   * PDF engine
   *
   * @package   PDF
   * @version   $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/07 16:06:31 $
   */
   class PDF extends FPDF {
     var $B;
     var $I;
     var $U;
     var $HREF;     
     var $fontList;
     var $issetfont;
     var $issetcolor;

    /**
     * Constructor
     */
     function PDF($orientation='P',$unit='mm',$format='A4',$_title,$_url,$_debug=false) {
        $this->FPDF($orientation,$unit,$format);
        $this->B=0;
        $this->I=0;
        $this->U=0;
        $this->HREF='';
        $this->PRE=false;
        $this->SetFont('Arial','',12);
        $this->fontlist=array("Arial","Courier");
        $this->issetfont=false;
        $this->issetcolor=false;
        $this->articletitle=$_title;
        $this->articleurl=$_url;
        $this->debug=$_debug;
        $this->AliasNbPages();
    }



     function Header() {
       global $aLang;

       if (SHOW_BACKGROUND == 'true') {
         $this->Background();
       }
       if (SHOW_WATERMARK == 'true') {
         $this->Watermark();
       }

       $this->SetFont('helvetica','B',18);
       $this->SetLineWidth(0);
       $w = $this->GetStringWidth(STORE_NAME)+6;
       $this->SetTextColor(200,0,0);
       $this->Cell($w,9,STORE_NAME,0,0,'C');

       //Today's date
       $this->SetTextColor(0,0,0);
       $date = strftime(DATE_FORMAT_LONG);

       $this->SetFont('Arial','',9);
       $this->Cell(0,9,$date .'  ',0,1,'R');
       $this->Ln(1);
       $x = $this->GetX();
       $y = $this->GetY();
       $this->Line($x,$y,190,$y);
       $this->Ln(1);
       //Keep Y position
       //$this->y0=$this->GetY();
     }

/*
    function Header() {
        //Select Arial bold 15
        $this->SetTextColor(0,0,0);
        $this->SetFont('Times','',10);
        $this->Cell(0,10,$this->articletitle,0,0,'C');
        $this->Ln(4);
        $this->Cell(0,10,$this->articleurl,0,0,'C');
        $this->Ln(7);
        $this->Line($this->GetX(),$this->GetY(),$this->GetX()+187,$this->GetY());
        //Line break
        $this->Ln(12);
        $this->SetFont('Times','',12);
        $this->mySetTextColor(-1);
    }
*/



    function WriteHTML($html,$bi) {
        //remove all unsupported tags
        $this->bi=$bi;
        if ($bi)
            $html=strip_tags($html,"<a><img><p><br><font><tr><blockquote><h1><h2><h3><h4><pre><red><blue><ul><li><hr><b><i><u><strong><em>");
        else
            $html=strip_tags($html,"<a><img><p><br><font><tr><blockquote><h1><h2><h3><h4><pre><red><blue><ul><li><hr>");
        $html=str_replace("\n",' ',$html); //replace carriage returns by spaces
        // debug
        if ($this->debug) { echo $html; exit; }

        $html = str_replace('&trade;','',$html);
        $html = str_replace('&copy;','',$html);
        $html = str_replace('&euro;','',$html);

        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        $skip=false;
        foreach($a as $i=>$e)
        {
            if (!$skip) {
                if($this->HREF)
                    $e=str_replace("\n","",str_replace("\r","",$e));
                if($i%2==0)
                {
                    // new line
                    if($this->PRE)
                        $e=str_replace("\r","\n",$e);
                    else
                        $e=str_replace("\r","",$e);
                    //Text
                    if($this->HREF) {
                        $this->PutLink($this->HREF,$e);
                        $skip=true;
                    } else
                        $this->Write(5,stripslashes(txtentities($e)));
                } else {
                    //Tag
                    if (substr(trim($e),0,1)=='/')
                        $this->CloseTag(strtoupper(substr($e,strpos($e,'/'))));
                    else {
                        //Extract attributes
                        $a2=explode(' ',$e);
                        $tag=strtoupper(array_shift($a2));
                        $attr=array();
                        foreach($a2 as $v) if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$',$v,$a3)) {
                            $attr[strtoupper($a3[1])]=$a3[2];
                        }
                        $this->OpenTag($tag,$attr);
                    }
                }
            } else {
                $this->HREF='';
                $skip=false;
            }
        }
    }

    function OpenTag($tag,$attr) {
        //Opening tag
        switch($tag){
            case 'STRONG':
            case 'B':
                if ($this->bi)
                    $this->SetStyle('B',true);
                else
                    $this->SetStyle('U',true);
                break;
            case 'H1':
                $this->Ln(5);
                $this->SetTextColor(150,0,0);
                $this->SetFontSize(22);
                break;
            case 'H2':
                $this->Ln(5);
                $this->SetFontSize(18);
                $this->SetStyle('U',true);
                break;
            case 'H3':
                $this->Ln(5);
                $this->SetFontSize(16);
                $this->SetStyle('U',true);
                break;
            case 'H4':
                $this->Ln(5);
                $this->SetTextColor(102,0,0);
                $this->SetFontSize(14);
                if ($this->bi)
                    $this->SetStyle('B',true);
                break;
            case 'PRE':
                $this->SetFont('Courier','',11);
                $this->SetFontSize(11);
                $this->SetStyle('B',false);
                $this->SetStyle('I',false);
                $this->PRE=true;
                break;
            case 'RED':
                $this->SetTextColor(255,0,0);
                break;
            case 'BLOCKQUOTE':
                $this->mySetTextColor(100,0,45);
                $this->Ln(3);
                break;
            case 'BLUE':
                $this->SetTextColor(0,0,255);
                break;
            case 'I':
            case 'EM':
                if ($this->bi)
                    $this->SetStyle('I',true);
                break;
            case 'U':
                $this->SetStyle('U',true);
                break;
            case 'A':
                $this->HREF=$attr['HREF'];
                break;
            case 'IMG':
                if(isset($attr['SRC']) and (isset($attr['WIDTH']) or isset($attr['HEIGHT']))) {
                    if(!isset($attr['WIDTH']))
                        $attr['WIDTH'] = 0;
                    if(!isset($attr['HEIGHT']))
                        $attr['HEIGHT'] = 0;
                    $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
                    $this->Ln(3);
                }
                break;
            case 'LI':
                $this->Ln(2);
                $this->SetTextColor(190,0,0);
                $this->Write(5,'      ');
                $this->mySetTextColor(-1);
                break;
            case 'TR':
                $this->Ln(7);
                $this->PutLine();
                break;
            case 'BR':
                $this->Ln(2);
                break;
            case 'P':
                $this->Ln(5);
                break;
            case 'HR':
                $this->PutLine();
                break;
            case 'FONT':
                if (isset($attr['COLOR']) and $attr['COLOR']!='') {
                    $coul=hex2dec($attr['COLOR']);
                    $this->mySetTextColor($coul['R'],$coul['G'],$coul['B']);
                    $this->issetcolor=true;
                }
                if (isset($attr['FACE']) and in_array(strtolower($attr['FACE']), $this->fontlist)) {
                    $this->SetFont(strtolower($attr['FACE']));
                    $this->issetfont=true;
                }
                break;
        }
    }

    function CloseTag($tag) {
        //Closing tag
        if ($tag='H1' || $tag='H2' || $tag='H3' || $tag='H4'){
            $this->Ln(6);
            $this->SetFont('Times','',12);
            $this->SetFontSize(12);
            $this->SetStyle('U',false);
            $this->SetStyle('B',false);
            $this->mySetTextColor(-1);
        }
        if ($tag='PRE'){
            $this->SetFont('Times','',12);
            $this->SetFontSize(12);
            $this->PRE=false;
        }
        if ($tag='RED' || $tag='BLUE')
            $this->mySetTextColor(-1);
        if ($tag='BLOCKQUOTE'){
            $this->mySetTextColor(0,0,0);
            $this->Ln(3);
        }
        if($tag=='STRONG')
            $tag='B';
        if($tag=='EM')
            $tag='I';
        if((!$this->bi) && $tag=='B')
            $tag='U';
        if($tag=='B' or $tag=='I' or $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
        if($tag=='FONT'){
            if ($this->issetcolor==true) {
                $this->SetTextColor(0,0,0);
            }
            if ($this->issetfont) {
                $this->SetFont('Times','',12);
                $this->issetfont=false;
            }
        }
    }

     function SetStyle($tag, $enable) {
       $this->$tag+=($enable ? 1 : -1);
       $style='';
       foreach(array('B', 'I', 'U') as $s)
         if($this->$s>0) $style .= $s;
       $this->SetFont('', $style);
     }

     function PutLink($URL,$txt) {
       //Put a hyperlink
       $this->SetTextColor(0,0,255);
       $this->SetStyle('U',true);
       $this->Write(5,$txt,$URL);
       $this->SetStyle('U',false);
       $this->mySetTextColor(-1);
     }

     function RotatedText($x,$y,$txt,$angle) {
       //Text rotated around its origin
       $this->Rotate($angle,$x,$y);
       $this->Text($x,$y,$txt);
       $this->Rotate(0);
     }

     function Footer() {
       global $aLang;

       $this->SetY(-20);
       $footer_color_cell=explode(",",FOOTER_CELL_BG_COLOR);
       $this->SetFillColor($footer_color_cell[0], $footer_color_cell[1], $footer_color_cell[2]);
       $this->MultiCell(0,5,"",0,'L',1);
       $x=$this->GetX();
       $y=$this->GetY();
       $this->SetLineWidth(0.25);
       $this->Line($x,$y,190,$y);
       $this->SetFont('Arial','',9);
       $this->Cell($w,9,STORE_NAME .' - '. STORE_OWNER_EMAIL_ADDRESS .' - '. OOS_HTTP_SERVER,0,0,'L','','mailto:'. STORE_OWNER_EMAIL_ADDRESS);
       $this->Cell(0, 10, $aLang['text_page'] . $this->PageNo() . '/{nb}', 0, 0, 'R');
     }

     function Background() {
       $bg_color=explode(",",PAGE_BG_COLOR);
       $this->SetFillColor($bg_color[0], $bg_color[1], $bg_color[2]);
       $this->Rect($this->lMargin,0,190,$this->h,'F');
     }

     function Watermark() {
       $this->SetFont('arial','B',80);
       $watermark_color=explode(",",PAGE_WATERMARK_COLOR);
       $this->SetTextColor($watermark_color[0], $watermark_color[1], $watermark_color[2]);
       $ang=30;
       $cos=cos(deg2rad($ang));
       $wwm=($this->GetStringWidth(STORE_NAME)*$cos);
       $this->RotatedText(($this->w-$wwm)/2,210,STORE_NAME,$ang);
     }

     function CheckPageBreak($h) {
       //Creates a new page if needed
       if ($this->GetY()+$h>$this->PageBreakTrigger)
       $this->AddPage($this->CurOrientation);
     }

     function LineString($x,$y,$txt,$cellheight) {
       //calculate the width of the string
       $stringwidth=$this->GetStringWidth($txt);

       //calculate the width of an alpha/numerical char
       $numberswidth=$this->GetStringWidth('1');
       $xpos=($x+$numberswidth);
       $ypos=($y+($cellheight/2));
       $this->Line($xpos,$ypos,($xpos+$stringwidth),$ypos);
     }


     function ShowImage(&$width,&$height,$path) {
       $width=min($width,MAX_IMAGE_WIDTH);
       $height = (IMAGE_KEEP_PROPORTIONS != 0 ? $height : min($height,MAX_IMAGE_HEIGHT));
       $this->SetLineWidth(1);
       $this->Cell($width,$height,"",0,0);
       $this->SetLineWidth(0.2);
       $pos=strrpos($path,'.');
       $type=substr($path,$pos+1);
       if ($type=='jpg' or $type=='jpeg' or $type=='png') {
         $this->Image($path,($this->GetX()-$width)+1, $this->GetY(), $width, $height);
       } else {
         $this->SetDrawColor(230,230,230);
         $this->x = $this->GetX()-$width;
         $this->SetTextColor(230,230,230);
         $this->Cell($width,$height,'No image',1,0,C);
         $this->SetTextColor(0);
         $this->SetDrawColor(0);
       }
     }

     function NbLines($w,$txt) {
       //Calculate number of lines for a "w" width Multicell
       $cw=&$this->CurrentFont['cw'];
       if ($w == 0)
         $w = $this->w-$this->rMargin-$this->x;
         $wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
       $s = str_replace("\r",'',$txt);
       $nb = strlen($s);
       if ($nb>0 and $s[$nb-1]=="\n")
        $nb--;
       $sep = -1;
       $i = 0;
       $j = 0;
       $l = 0;
       $nl = 1;
       while ($i<$nb) {
         $c=$s{$i};
         if ($c=="\n") {
                  $i++;
                  $sep = -1;
                  $j = $i;
                  $l = 0;
                  $nl++;
                  continue;
                }
                if ($c==' ')
        $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
          if($sep==-1)
          {
            if($i==$j)
              $i++;
          }
          else
            $i=$sep+1;
          $sep=-1;
          $j=$i;
          $l=0;
          $nl++;
        }
        else
          $i++;
       }
       return $nl;
     }

     function CalculatedSpace($y1,$y2,$imageheight) {
       //Si les commentaires sont - importants que l'image au niveau de l'espace d'affichage
       if (($h2=$y2-$y1) < $imageheight) {
         $this->Ln(($imageheight-$h2)+3);
       } else {
         $this->Ln(3);
       }
     }

    function PutLine() {
        $this->Ln(2);
        $this->Line($this->GetX(),$this->GetY(),$this->GetX()+187,$this->GetY());
        $this->Ln(3);
    }

    function mySetTextColor($r,$g=0,$b=0){
        static $_r=0, $_g=0, $_b=0;

        if ($r==-1)
            $this->SetTextColor($_r,$_g,$_b);
        else {
            $this->SetTextColor($r,$g,$b);
            $_r=$r;
            $_g=$g;
            $_b=$b;
        }
    }

    function PutMainTitle($title) {
        if (strlen($title)>55)
            $title=substr($title,0,55)."...";
        $this->SetTextColor(33,32,95);
        $this->SetFontSize(20);
        $this->SetFillColor(255,204,120);
        $this->Cell(0,20,$title,1,1,"C",1);
        $this->SetFillColor(255,255,255);
        $this->SetFontSize(12);
        $this->Ln(5);
    }

    function PutMinorHeading($title) {
        $this->SetFontSize(12);
        $this->Cell(0,5,$title,0,1,"C");
        $this->SetFontSize(12);
    }

    function PutMinorTitle($title,$url='') {
        $title=str_replace('http://','',$title);
        if (strlen($title)>70)
            if (!(strrpos($title,'/')==false))
                $title=substr($title,strrpos($title,'/')+1);
        $title=substr($title,0,70);
        $this->SetFontSize(16);
        if ($url!='') {
            $this->SetStyle('U',false);
            $this->SetTextColor(0,0,180);
            $this->Cell(0,6,$title,0,1,"C",0,$url);
            $this->SetTextColor(0,0,0);
            $this->SetStyle('U',false);
        } else
            $this->Cell(0,6,$title,0,1,"C",0);
        $this->SetFontSize(12);
        $this->Ln(4);
   }
 }
?>

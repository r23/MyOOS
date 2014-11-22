</td>
    <td><img src="{$theme_image}/trans.gif" border="0" alt=" " width="5" height="1"></td>
    <td width="150" valign="top"><table border="0" width="150" cellspacing="0" cellpadding="2">
{foreach item=block from=$oos_blockright}
   {include file="phoenix/system/_block.tpl"}
{/foreach}
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<table width="870" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right" class="footer_navigation_background" nowrap="nowrap">&nbsp;&nbsp;</td>
    <td width="5" class="footer_navigation_background"><img src="{$theme_image}/footer_navigation_right.gif" alt=" " width="5" height="19" ></td>
  </tr>
</table>

<br>
<br><div class="smallText" align="center">

</div>

		{*
			GERMAN:
			Diese Rueckverlinkung darf nur entfernt werden,
			wenn Sie eine MyOOS Lizenz besitzen.
			:: Lizenzbedingungen: 
			http://www.myoos.de/Projektbezogene-Gebuehr-p-38.html

			ENGLISH:
			This back linking maybe only removed,
			if you possess a MyOOS Lizenz license.
			:: License conditions: 
			http://www.myoos.de/Projektbezogene-Gebuehr-p-38.html
		*}

<div class="footer" align="center">
<div align="center" class="smallText">Copyright &copy; 2003 - {$smarty.now|date_format:"%Y"} <a href="{$smarty.const.OOS_HTTP_SERVER}">{$smarty.const.STORE_NAME}</a>.  All rights reserved.</div>
<div align="center" class="smallText"><a href="http://www.oos-shop.de" target="_blank">MyOOS [Shopsystem]</a> is Free Software released under the <a href="http://www.gnu.org" target="_blank">GNU/GPL License.</a></div>
</div>

</body>
</html>

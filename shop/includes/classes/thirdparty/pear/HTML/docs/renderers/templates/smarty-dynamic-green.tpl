<!--  $Id: smarty-dynamic-green.tpl,v 1.1 2007/06/08 15:14:29 r23 Exp $ -->

<tr>
    <td align="right" valign="top" class="green"><b>{$element.label}:</b></td>
    <td valign="top" align="left" class="green">
    {if $element.error}<font color="red">{$element.error}</font><br />{/if}
    {$element.html}{if $element.required}<font color="red">*</font>{/if}
    </td>
</tr>

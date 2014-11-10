<?xml version="1.0" encoding="ISO-8859-1"?>
<!--
THIS XSL FILE IS PART OF THE PXW4PA SOFTWARE
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
-->
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/pXw4Pa/group">
	<html>
	<head>
		<title>XML file made with pXw4Pa v1.0 (http://pxw4pa.sourceforge.net/) formatted with XSL</title>
	<style>
		BODY{background-color:#cccccf;font-family:monospace;margin:24px;}
		SPAN.v{background-color:#eeeeef;padding:1px;}
		SPAN.sign{font-size:8pt;}
		TABLE.t{width:100%;border-left:3px solid #000000;}
		TABLE.a{border:1px solid #000000;}
		TD.b{vertical-align:top; padding:2px 12px 2px 12px;background-color:#aabbdd;}
		TD.i{vertical-align:top; padding:2px 12px 2px 12px;background-color:#aaddaa;}
		TD.d{vertical-align:top; padding:2px 12px 2px 12px;background-color:#ddd8aa;}
		TD.n{vertical-align:top; padding:2px 12px 2px 12px;background-color:#888888;}
		TD.t{vertical-align:top; padding:2px 12px 2px 12px;background-color:#cccccf;}
		TD.ak{vertical-align:top; padding:2px 12px 2px 12px;background-color:#ddaaaa;}
		TD.av{vertical-align:top; padding:0px;background-color:#ddaaaa;}
		SPAN.b{border:1px solid #000000;background-color:#aabbdd;}
		SPAN.i{border:1px solid #000000;background-color:#aaddaa;}
		SPAN.d{border:1px solid #000000;background-color:#ddd8aa;}
		SPAN.n{border:1px solid #000000;background-color:#888888;}
		SPAN.t{border:1px solid #000000;background-color:#cccccf;}
		SPAN.a{border:1px solid #000000;background-color:#ddaaaa;}
		PRE{margin:0px;}
	</style>
	</head>
	<body>
	<h2>stylesheet made with TABLES</h2>
	<table rules="all" frame="box" class="a"><tbody>
		<xsl:apply-templates/> 
	</tbody></table>
	<br/>
	<hr/>
	legend:&#160;&#160;
	<span class="b">&#160;&#160;</span>&#160;boolean&#160;&#160;&#160;
	<span class="i">&#160;&#160;</span>&#160;integer&#160;&#160;&#160;
	<span class="d">&#160;&#160;</span>&#160;double (float)&#160;&#160;&#160;
	<span class="n">&#160;&#160;</span>&#160;NULL (undefined)&#160;&#160;&#160;
	<span class="t">&#160;&#160;</span>&#160;string&#160;&#160;&#160;
	<span class="a">&#160;&#160;</span>&#160;subarrays&#160;&#160;&#160;
	<br/>
	<span class="sign"><br/>XML file created and formatted with pXw4Pa v1.0
	<br/><a href="http://pxw4pa.sourceforge.net/index.php">http://pxw4pa.sourceforge.net/index.php</a></span>
	</body>
	</html>
</xsl:template>

<xsl:template match="group">
		<tr>
		<td class="ak"><span class="v"><xsl:value-of select="@name"/></span></td>
		<td class="av">
		<table rules="all" class="t"><tbody>
			<xsl:apply-templates match="group"/>
		</tbody></table>
		</td></tr>
</xsl:template>

<xsl:template match="entry">

<tr>
	<xsl:if test="@type='NULL'">
		<td colspan="2" class="n"><xsl:choose><xsl:when test="not(@name)"><i>?</i></xsl:when><xsl:otherwise><span class="v"><xsl:value-of select="@name"/></span></xsl:otherwise></xsl:choose></td>
		</xsl:if>
	<xsl:if test="@type='integer'">
		<td class="i"><xsl:choose><xsl:when test="not(@name)"><i>?</i></xsl:when><xsl:otherwise><span class="v"><xsl:value-of select="@name"/></span></xsl:otherwise></xsl:choose></td>
		<td class="i"><span class="v"><xsl:value-of select="."/></span></td>
		</xsl:if>
	<xsl:if test="@type='double'">
		<td class="d"><xsl:choose><xsl:when test="not(@name)"><i>?</i></xsl:when><xsl:otherwise><span class="v"><xsl:value-of select="@name"/></span></xsl:otherwise></xsl:choose></td>
		<td class="d"><span class="v"><xsl:value-of select="."/></span></td>
		</xsl:if>
	<xsl:if test="@type='boolean' and .='1'">
		<td class="b"><xsl:choose><xsl:when test="not(@name)"><i>?</i></xsl:when><xsl:otherwise><span class="v"><xsl:value-of select="@name"/></span></xsl:otherwise></xsl:choose></td>
		<td class="b"><i>true</i></td>
		</xsl:if>
	<xsl:if test="@type='boolean' and .!='1'">
		<td class="b"><xsl:choose><xsl:when test="not(@name)"><i>?</i></xsl:when><xsl:otherwise><span class="v"><xsl:value-of select="@name"/></span></xsl:otherwise></xsl:choose></td>
		<td class="b"><i>false</i></td>
		</xsl:if>
	<xsl:if test="@type='string'">
		<td class="t"><xsl:choose><xsl:when test="not(@name)"><i>?</i></xsl:when><xsl:otherwise><span class="v"><xsl:value-of select="@name"/></span></xsl:otherwise></xsl:choose></td>
		<td class="t"><pre><span class="v"><xsl:value-of select="."/></span></pre></td>
		</xsl:if>
</tr>
</xsl:template>

</xsl:stylesheet>
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
		<title>XML file made with pXw4Pa v1.0 (http://pxw4pa.sourceforge.net/), formatted with XSL</title>
	<style>
		BODY{background-color:#cccccf;font-family:monospace;margin:24px;color:#666666;}
		PRE{margin:0px;}
		SPAN.sign{font-size:8pt;color:#000000;}
		SPAN.k{margin:0px;font-weight:bold;color:#006600;}
		SPAN.k2{margin:0px;font-weight:bold;color:#660000;}
		SPAN.v{margin:0px;color:#000000;font-weight:bold;}
		SPAN.v2{font-style:italic;color:#000000;font-weight:bold;}
		SPAN.type{font-size:8pt;font-style:italic;margin:0px 2px 0px 0px;}
		SPAN.type2{font-size:8pt;font-style:italic;margin:0px 6px;color:#000000;font-weight:bold;}
		DIV.ak{margin:0px;clear:both;float:left;}
		DIV.av{font-family:monospace;margin:12px 0px 12px 32px;clear:both;float:left;}
		DIV.ev{margin:0px 0px 2px 0px;clear:both;float:left;}
		H2{color:#000000;}
	</style>
	</head>
<body>
<h2>text based stylesheet</h2> 
<table><tbody><tr><td>
	<hr/><br/>
<xsl:apply-templates/> 
</td></tr><tr><td>
	<hr/>
	<span class="sign"><br/>XML file created and formatted with pXw4Pa v1.0
	<br/><a href="http://pxw4pa.sourceforge.net/index.php">http://pxw4pa.sourceforge.net/index.php</a></span>
</td></tr></tbody></table>
</body>
</html>
</xsl:template>

<xsl:template match="group">
	<div class="ak">
		<span class="k2">"<xsl:value-of select="@name"/>"</span>
	</div>
	<div class="av">
			<xsl:apply-templates match="group"/>
	</div>
</xsl:template>

<xsl:template match="entry">
<div class="ev">
	<xsl:if test="@type='NULL'">
		<xsl:choose><xsl:when test="not(@name)"><i>? </i></xsl:when><xsl:otherwise>"<span class="k"><xsl:value-of select="@name"/></span>"=</xsl:otherwise></xsl:choose><span class="type2">NULL</span>
		</xsl:if>
	<xsl:if test="@type='integer'">
		<xsl:choose><xsl:when test="not(@name)"><i>? </i></xsl:when><xsl:otherwise>"<span class="k"><xsl:value-of select="@name"/></span>"=</xsl:otherwise></xsl:choose><span class="type">int</span>=<span class="v"><xsl:value-of select="."/></span>
		</xsl:if>
	<xsl:if test="@type='double'">
		<xsl:choose><xsl:when test="not(@name)"><i>? </i></xsl:when><xsl:otherwise>"<span class="k"><xsl:value-of select="@name"/></span>"=</xsl:otherwise></xsl:choose><span class="type">doub</span>=<span class="v"><xsl:value-of select="."/></span>
		</xsl:if>
	<xsl:if test="@type='boolean' and .='1'">
		<xsl:choose><xsl:when test="not(@name)"><i>? </i></xsl:when><xsl:otherwise>"<span class="k"><xsl:value-of select="@name"/></span>"=</xsl:otherwise></xsl:choose><span class="type">bool</span>=<span class="v2">TRUE</span>
		</xsl:if>
	<xsl:if test="@type='boolean' and .!='1'">
		<xsl:choose><xsl:when test="not(@name)"><i>? </i></xsl:when><xsl:otherwise>"<span class="k"><xsl:value-of select="@name"/></span>"=</xsl:otherwise></xsl:choose><span class="type">bool</span>=<span class="v2">FALSE</span>
		</xsl:if>
	<xsl:if test="@type='string'"><pre><xsl:choose><xsl:when test="not(@name)"><i>? </i></xsl:when><xsl:otherwise>"<span class="k"><xsl:value-of select="@name"/></span>"=</xsl:otherwise></xsl:choose><span class="type">str</span>="<span class="v"><xsl:value-of select="."/></span>"</pre>
		</xsl:if>
</div>
<br/>
</xsl:template>

</xsl:stylesheet>


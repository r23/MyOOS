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
<xsl:output method="text" version="1.0"
encoding="iso-8859-1" indent="yes"/>
<xsl:template match="/pXw4Pa/group">
TEXT ONLY stylesheet
#############################
<xsl:apply-templates/> 
-----------------------------------------------
XML file created and formatted with pXw4Pa v1.0
http://pxw4pa.sourceforge.net/index.php
</xsl:template>

<xsl:template match="group">"<xsl:value-of select="@name"/>"<xsl:apply-templates match="group"/></xsl:template>

<xsl:template match="entry">
<xsl:if test="@type='NULL'">
<xsl:choose><xsl:when test="not(@name)">? = </xsl:when><xsl:otherwise>"<xsl:value-of select="@name"/>" =</xsl:otherwise></xsl:choose>NULL</xsl:if>
<xsl:if test="@type='integer'">
<xsl:choose><xsl:when test="not(@name)">? = </xsl:when><xsl:otherwise>"<xsl:value-of select="@name"/>" =</xsl:otherwise></xsl:choose>int = <xsl:value-of select="."/></xsl:if>
<xsl:if test="@type='double'">
<xsl:choose><xsl:when test="not(@name)">? = </xsl:when><xsl:otherwise>"<xsl:value-of select="@name"/>" =</xsl:otherwise></xsl:choose>doub = <xsl:value-of select="."/></xsl:if>
<xsl:if test="@type='boolean' and .='1'">
<xsl:choose><xsl:when test="not(@name)">? = </xsl:when><xsl:otherwise>"<xsl:value-of select="@name"/>" =</xsl:otherwise></xsl:choose>bool = TRUE</xsl:if>
<xsl:if test="@type='boolean' and .!='1'">
<xsl:choose><xsl:when test="not(@name)">? = </xsl:when><xsl:otherwise>"<xsl:value-of select="@name"/>" =</xsl:otherwise></xsl:choose>bool = FALSE</xsl:if>
<xsl:if test="@type='string'"><xsl:choose><xsl:when test="not(@name)">? = </xsl:when><xsl:otherwise>"<xsl:value-of select="@name"/>" = </xsl:otherwise></xsl:choose>str = "<xsl:value-of select="."/>"</xsl:if>
</xsl:template>
</xsl:stylesheet>


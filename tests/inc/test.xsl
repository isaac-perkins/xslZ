﻿<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
 <xsl:output method="html" version="1.0" encoding="utf-8" omit-xml-declaration="yes" standalone="no" indent="no" doctype-public="html"/>
  <xsl:template match="/"><p><xsl:value-of select="this/is/a"/></p></xsl:template>
</xsl:stylesheet>

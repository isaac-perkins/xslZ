# xslz
![!Scrutinizer](https://scrutinizer-ci.com/g/isaac-perkins/xslZ/badges/quality-score.png?b=master)
![Coverage](https://scrutinizer-ci.com/g/isaac-perkins/xslZ/badges/coverage.png?b=master)
![Build](https://scrutinizer-ci.com/g/isaac-perkins/xslZ/badges/build.png?b=master)
![License: MIT](https://img.shields.io/github/license/mashape/apistatus.svg)

Simple helper for XSLT.

#### Usage

##### Transform:
``` php

  $xslX = new XslZ;

  echo $xslX->transform('xslFile.xsl', 'xmlFile.xml');
```

##### Set Parameters:
``` php

  $xslX = new XslZ;

  $xsl = new \DomDocument;
  $xsl->load('xslFile.xsl');

  $xslX->setParams([
	   "param" => 1,
	   "param2" => "pass to xsl"
  ]);

  $outputToFile = __DIR__ . '/transformationResult.html';

  echo $xslX->transform($xsl, '<test>data</test>', $outputToFile);
```

##### Call custom PHP functions:
``` php
  $xslX = new XslZ;

  $xml = new \DomDocument;
  $xml->loadXML('<test>data</test>');

  $xsl = new DomDocument;
  $xsl->load(__DIR__ . '/file.xsl');


  $params = [
	   "param" => 1,
	   "param2" => "pass to xsl"
  ];

  $functionsFile = __DIR__ . '/functions.php';


  echo $xslX->transform($xsl, $xml, null, $params, $functionsFile);
```
Use function within XSL stylesheet
``` xml
<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl" version="1.0">
  <xsl:param name="test-param"/>
  <xsl:output method="html" version="1.0" encoding="utf-8" omit-xml-declaration="yes" standalone="no" indent="no" doctype-public="html"/>
  <xsl:template match="/"><p><xsl:value-of select="php:function ('customFunction', $test-param)"/></p></xsl:template>
</xsl:stylesheet>
```

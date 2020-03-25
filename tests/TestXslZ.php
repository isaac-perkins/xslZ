<?php
namespace XslZ\Tests;

use XslZ\XslZ;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class TestXslZ extends TestCase {


  function testCreate()
  {
      $xslZ = new XslZ;

      $this->assertEquals(true, is_object($xslZ));
  }

  function testDom()
  {
      $xslZ = new XslZ;

      $dom = $xslZ->getDom(self::getTestXmlString());

      $answer = '<?xml version="1.0"?>
<this><is><a>test</a></is></this>
';
      $this->assertEquals($answer, $dom->saveXML());
  }

  function testTransformFromDomObject()
  {
      $xslZ = new XslZ;

      $answer = '<!DOCTYPE html PUBLIC "html">
<p>test</p>
';
      $this->assertEquals($answer, $xslZ->transform(self::getXsl(), self::getXml()));
  }

  function testTransformFromFile()
  {
      $xslZ = new XslZ;

      $answer = '<!DOCTYPE html PUBLIC "html">
<p>test</p>
';

      $this->assertEquals($answer, $xslZ->transform(__DIR__ . '/inc/test.xsl', self::getXml()));
  }

  function testTransformFromString()
  {
        $xslZ = new XslZ;

        $answer = '<!DOCTYPE html PUBLIC "html">
<p>test</p>
';

        $this->assertEquals($answer, $xslZ->transform(self::getXsl(), self::getTestXmlString()));
  }

  function testTransformToFile()
  {
    $xslZ = new XslZ;

    $answer = '<!DOCTYPE html PUBLIC "html">
<p>test</p>
';

    $output = __DIR__ . '/test-ouput.html';

    $xslZ->transform(self::getXsl(), self::getTestXmlString(), $output, null, null);

    $this->assertEquals($answer, file_get_contents($output));

    unlink($output);
  }

  function testAddParams()
  {
      $xslZ = new XslZ;

      $xslZ->addParam('test-param', 'value');

      $this->assertEquals(['test-param' => 'value'], $xslZ->getParams());

  }

  function testTransformParams()
  {
      $xslZ = new XslZ;

      $answer = '<!DOCTYPE html PUBLIC "html">
<p>value</p>
';
      $params = ['test-param' => 'value'];

      $testParamXsl = __DIR__ . '/inc/test-params.xsl';
      
      $this->assertEquals($answer, $xslZ->transform($testParamXsl, self::getXml(), null, $params));

      $xslZ->addParam('test-param', 'value');

      $this->assertEquals($answer, $xslZ->transform($testParamXsl, self::getXml()));

  }

  function testFunctions()
  {
    $xslZ = new XslZ;

    $answer = '<!DOCTYPE html PUBLIC "html">
<p xmlns:php="http://php.net/xsl">result</p>
';

    $xslZ->addParam('test-param', 'result');

    $this->assertEquals($answer, $xslZ->transform(__DIR__ . '/inc/test-functions.xsl', self::getXml(), null, [], __DIR__ . '/inc/functions.php'));
}

  static function getXsl()
  {
      $xsl = new \DomDocument;
      $xsl->load(__DIR__ . '/inc/test.xsl');
      return $xsl;
  }

  static function getXml()
  {
      $xml = new \DomDocument;
      $xml->loadXML(self::getTestXmlString());
      return $xml;
  }

  static function getTestXmlString()
  {
      return '<this><is><a>test</a></is></this>';
  }

}

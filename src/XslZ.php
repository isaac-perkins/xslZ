<?php
/**
 * XslX
 * XSLT Helper
 * @author   Isaac Perkins<isaac.perkins.1@gmail.com>
 *
 **
 */
namespace XslZ;

class XslZ
{

    protected $params = [];

    private $functions;

    /**
    * Get set params
    */

    public function getParams()
    {
        return $this->params;
    }

    /**
      * Set parameters array
    */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
   *  Append a parameter
   */
    public function addParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * Retrieve a parameter
    */
    public function getParam($key)
    {
        if (!isset($this->params[$key]))
        {
            return false;
        }
        return $this->params[$key];
    }

    /**
     * Set functions file
    */
    public function setFunctions($functions)
    {
        $this->functions = $functions;
    }

    /**
     * Get function file
    */
    public function getFunctions()
    {
        return $this->functions;
    }


    /**
     * Fetch Document from path/string/object
    */
    public function getDom($fileObjectString)
    {
      if(is_object($fileObjectString)) {
          return $fileObjectString;
      }

      $dom = new \DomDocument;
      if(is_file($fileObjectString)) {
          $dom->load($fileObjectString);
      } else {
          $dom->loadXML($fileObjectString);
      }
      return $dom;
    }

    /**
     *   xsl transformation
    */
    public function transform($xsl, $xml, $outputFile = null, $params = [], $functions = null)
    {
        $xsl = $this->getDom($xsl);
        $xml = $this->getDom($xml);

        $xslt = new \XSLTProcessor;

        ini_set("xsl.security_prefs", 0);
        $xslt->setSecurityPrefs(0);
        $xslt->importStylesheet($xsl);

        $this->setXslParams($xslt, $params);

        if ($functions || isset($this->functions)) {
          $this->setFunctionsInclude($functions);
          $xslt->registerPHPFunctions();
        }

        if (isset($outputFile))  {
            $rv = $xslt->transformToUri($xml, $outputFile);
        } else {
            $rv = $xslt->transformToXML($xml);
        };

        return $rv;
    }

    /**
     * Set functions file to include
    */
    private function setFunctionsInclude(String $functions = null)
    {
        if ($functions) {
            require_once ($functions);
        }

        if (isset($this->functions)) {
            require_once ($this->functions);
        };
    }

    /**
     * Set xsl parameters
    */
    private function setXslParams($xslt, $params = [])
    {
        $params = array_merge($this->params, $params);

        foreach ($params as $key => $value)
        {
            $xslt->setParameter('', $key, $value);
        }
    }
}

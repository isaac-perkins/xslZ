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
    * @return array
    */

    public function getParams()
    {
        return $this->params;
    }

    /**
    * Set parameters array
    * @param array $parameters
    */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
   *  Append a parameter
   *  @param $key
   *  @param $value
   */
    public function addParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    /**
     * Retrieve a parameter
     * @param $key
     * @return mixed
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
     * @param String Path to php file containing functions to be available to xsl
    */
    public function setFunctions($functions)
    {
        $this->functions = $functions;
    }

    /**
     * Get function file
     * @return Path to php file containing functions to be available to xsl
    */
    public function getFunctions()
    {
        return $this->functions;
    }

    /**
     * Set transormation output file
     * @param  Path to php file containing functions to be available to xsl
    */
    public function setOutputFile(Path $outputFile)
    {
      $this->outputFile = $outputFile;
    }

    /**
     * Get transformation output file
     * @return String Path to php file containing functions to be available to xsl
    */
    public function getOutputFile()
    {
      return $this->outputFile;
    }

    /**
     * Get DomDocument from path/string/object
     * @return DomDocument
    */
    public function getDom($fileOrOject)
    {
      if(is_object($fileOrOject)) {
          return $fileOrOject;
      }

      $dom = new \DomDocument;
      if(is_file($fileOrOject)) {
          $rv = $dom->load($fileOrOject);
      } else {
          $rv = $dom->loadXML($fileOrOject);
      }
      return $dom;
    }

    /**
     *   xsl transformation
     *   @param $xsl path, string or object
     *   @param $xml path, string or object
     *   @param $outputFile  path/to/transformed/file
     *   @param $params associative array of xsl parameters
     *   @param $functons path/to/php/functions/called/in/xslt
     *   @return mixed
     */
    public function transform($xsl, $xml, $outputFile = null, $params = [], $functions = null)
    {
        $xsl = $this->getDom($xsl);
        $xml = $this->getDom($xml);

        $xslt = new \XSLTProcessor;

        ini_set("xsl.security_prefs", 0);
        $xslt->setSecurityPrefs(0);
        $xslt->importStylesheet($xsl);

        if ($params || $this->params) {
            $this->setXslParams($xslt, $params);
        };

        if($functions || isset($this->functions)) {
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
     * @param Path to php file containing functions to be available to xsl
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
     * @param XSLT Proccessor object
     * @param parameters to pass to xsl
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

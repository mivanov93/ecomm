<?php

namespace Ecomm\Utils;

use Exception;

/**
 * Description of JsonCfg
 *
 * @author x0r
 */
class JsonCfg {

    private $cfgArray = [];

    public function __construct($jsonCfgFile) {
        if (!file_exists($jsonCfgFile)) {
            throw new Exception($jsonCfgFile . " doesn't exist");
        }
        $this->cfgArray = json_decode(file_get_contents($jsonCfgFile), true);
        if (array_key_exists('log.level', $this->cfgArray)) {
            $constName = $this->cfgArray["log.level"];
            $this->cfgArray["log.level"] = constant($constName);
        }
    }

    public function getAsArray() {
        return $this->cfgArray;
    }

    //put your code here
}

<?php

namespace Kant1;

class JSONReference {
    protected $reference = null;
    protected $resolved = false;

    public function __construct($ref){
        $this->setReference($ref);
    }
    public function resolve(){
        if (!$this->resolved && filter_var($this->reference, FILTER_VALIDATE_URL)){

        }elseif (!$this->resolved){
            $int = explode('/', $this->reference);
            if ($int[0] == '#')
                unset($int[0]);
            $this->resolved = self::arrayPath(JSONGlobal::$root, array_values($int));
        }
        return $this->resolved;
    }
    public function getReference(){
        return $this->reference;
    }
    public function setReference($data){
        if (!is_string($data) || empty($data))
            throw new Exception('INVALID REFERENCE', 500);
        $this->reference = $data;
    }
    static public function arrayPath(&$array, $path = array()) {
        $ref = $array;
        foreach ($path as $key)
            $ref = $ref[$key];
        return $ref;
    }
}

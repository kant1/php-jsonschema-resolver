<?php

namespace Kant1;

class JSONElement extends \ArrayIterator{
    public function __construct($struct=null){
        if (is_array($struct)){
            parent::__construct($struct);
        }
    }
    ### Iterator
    public function current() {
        $el = parent::current();
        if (is_a($el, 'Kant1\JSONReference'))
            return $el->resolve();
        return $el;
    }
    public function offsetGet($index){
        $el = parent::offsetGet($index);
        if (is_a($el, 'Kant1\JSONReference')) {
            return $el->resolve();
        }
        return $el;
    }
    ### SETTER
    public function setContent($data){
        if (is_array($data)){
            parent::__construct($data);
        }
    }
    ### PARSING FUNC
    static public function parseStructure($struct, $level=0, &$root=null) {
        $out = array();
        if ($level == 0)
            $root = new JSONElement();
        foreach($struct as $key=>$value){
            if ($key === '$ref' && is_scalar($value)) {
                return new JSONReference($value, $root);
            } else {
                $out[$key] = (is_scalar($value))?$value:self::parseStructure($value, $level+1, $root);
            }
        }
        if ($level == 0){
            $root->setContent($out);
            JSONGlobal::$root = $root;
            return $root;
        }
        return new JSONElement($out);
    }
    static public function fromString($str){
        if (!is_string($str) || empty($str)){
            throw new \Exception('INVALID JSON STRING', 404);
        }
        $raw = json_decode($str, true);
        $error = json_last_error_msg();
        if ($error != 'No error')
            throw new \Exception('INVALID JSON STRING : '.$error, 404);
        $ret = self::parseStructure($raw);
        return $ret;
    }
    static public function fromFile($path){
        if (!is_file($path) && !filter_var($path, FILTER_VALIDATE_URL)){
            throw new \Exception('INVALID JSON FILE', 404);
        }
        return self::fromString(file_get_contents($path));
    }
}

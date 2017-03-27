<?php


namespace CityNexus\DataStore;

class Typer
{

    public function type( $object )
    {
        if(gettype($object) == "object") { return 'datetime'; }
        elseif(gettype($object) == "integer") { return 'integer'; }
        elseif(gettype($object) == "boolean") { return 'boolean'; }
        elseif(gettype($object) == "double") { return $this->isIntegerOrFloat($object); }
        elseif(gettype($object) == "string") { return $this->isStringTypes($object); }
        return null;
    }

    public function isIntegerOrFloat($object)
    {
        $integer = intval($object);
        if($integer == $object) return 'integer';
        else return 'float';
    }

    public function isStringTypes($object)
    {
        switch (strtolower(trim($object)))
        {
            case 'true':
                return 'boolean';
            case 'false':
                return 'boolean';
        }

        if (strlen($object) > 100) {
            return 'text';
        }
        else {
            return 'string';
        }
    }


}
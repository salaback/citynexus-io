<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 5/31/17
 * Time: 12:53 PM
 */

namespace App\Exceptions\TableBuilder;

class CreateTableBuilderException extends TableBuilderException
{
    public function __construct()
    {
        $message = $this->create(func_get_args());
        parent::__construct($message);
    }
}
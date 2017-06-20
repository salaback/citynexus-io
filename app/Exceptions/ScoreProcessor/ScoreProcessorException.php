<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 5/31/17
 * Time: 12:36 PM
 */

namespace App\Exceptions\TableBuilder;

use \Exception;

abstract class ScoreProcessorException extends Exception
{

    protected $id;
    protected $details;

    public function __construct($message)
    {
        parent::__construct($message);
    }

    protected function create(array $args)
    {
        $this->id = array_shift($args);
        $error = $this->errors($this->id);
        $this->details = vsprintf($error['context'], $args);
        return $this->details;
    }

    private function errors($id)
    {
        $data= [
            'no_option' => [
                'context'  => 'Option given doesn\'t exist',
            ]
        ];
        return $data[$id];
    }
}

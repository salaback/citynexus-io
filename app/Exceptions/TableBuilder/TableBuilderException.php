<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 5/31/17
 * Time: 12:36 PM
 */

namespace App\Exceptions\TableBuilder;

use \Exception;

abstract class TableBuilderException extends Exception
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
            'field_exists' => [
                'context'  => 'Data set already has a field with that name.',
            ],
            'create_schema_failed' =>
            [
                'context' => 'The schema for the client you are trying to create could not be built'
            ],
            'migration_table_failed' => [
                'context' => 'Creating a migration table in the schema specified was not possible.'
            ]
        ];
        return $data[$id];
    }
}

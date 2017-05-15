<?php


namespace App\DataStore;


use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TableBuilder
{
    public function createTable($table)
    {
        if(isset($table->table_name))
        {
            $table_name = $table->table_name;
        }
        else{
            $table_name = 'cnd_' . $this->cleanName($table->table_title);
        }

        $fields = $table->schema;

        if(!$this->tableExists($table_name)) {
            Schema::create($table_name, function (Blueprint $table) use ($fields) {
                // Create table's index id file
                $table->increments('id');
                $table->integer('upload_id');
                $table->integer('property_id')->unsigned()->nullable();
                $table->integer('entity_id')->unsigned()->nullable();

                foreach ($fields as $field) {
                    $type = $field['type'];
                    $table->$type($field['key'])->nullable();
                }
                $table->timestamps();
            });
        }

        return $table_name;
    }

    public function syncTable($table)
    {

        if($table->table_name == null or !$this->tableExists($table->table_name)) {
            return $this->createTable($table);
        }
        else {
            Schema::table($table->table_name, function (Blueprint $blueprint) use ($table) {
                // Create table's index id file
                foreach ($table->schema as $key => $field) {
                    if(!Schema::hasColumn($table->table_name, $key))
                    {
                        $type = $field->type;
                        $blueprint->$type($field->key)->nullable();
                    }
                }
            });
        }

        return $table->table_name;
    }

    public function cleanName($name)
    {
        $return = preg_replace("/[^a-zA-Z0-9_ -%][().'!][\/]/s", '', $name);
        $return = strtolower($return);
        $return = str_replace(["'", "`", "!"], '',$return);
        $return = str_replace(["/", " ", "-"], '_',$return);
        return $return;
    }

    public function tableExists($table_name)
    {
        $test =  DB::table('information_schema.tables')
                    ->where('table_schema', config('database.connections.tenant.schema'))
                    ->where('table_name', $table_name)
                    ->first();

        if($test != null)
            return true;
        else
            return false;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 7/7/17
 * Time: 7:39 PM
 */

namespace App\DataStore\Observers;

use App\DataStore\Model\DataSet;
use App\DataStore\TableBuilder;

class DataSetObserver
{

    protected $tableB;
    public function __construct()
    {
        $this->tableB = new TableBuilder();
    }

    public function created(DataSet $dataSet)
    {
        $this->tableB->createTable($dataSet);
        $this->tableB->syncTable($dataSet);
    }

    public function updated(DataSet $dataSet)
    {
        $this->tableB->syncTable($dataSet);
    }

}
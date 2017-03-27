<?php

namespace CityNexus\DataStore;

use Illuminate\Database\Eloquent\Model;

class Uploader extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dataset_id',
        'upload_id',
        'name',
        'settings',
        'type',
        'description',
        'frequency',
        'filters'
    ];

    protected $casts = [
        'settings' => 'array',
        'filters' => 'array',
        'syncs' => 'array',
        'map' => 'array'
    ];

    /**
     * Table name
     */
    protected $table = 'cn_uploader';

    /**
     * The data points uploaded during upload
     *
     */

    public function data()
    {
        return $this->hasMany('\CityNexus\DataStore\DataSet');
    }

    public function uploads()
    {
        return $this->hasMany('\CityNexus\DataStore\Upload');
    }

    /**
     *
     * Add a new sync array to the list of syncs
     *
     * @param $new
     * @return bool
     */
    public function addSync($new)
    {
        $syncs = (array) $this->syncs;
        $syncs[] = $new;
        $this->syncs = $syncs;
        $this->save();
        return true;
    }

    public function hasSyncClass($class)
    {
        if($this->sync != null) {
            foreach($this->syncs as $i)  {
                if($i['class'] == $class) return true;
            }
        }
        return false;
    }

    public function getSyncClass($class)
    {
        if($this->sync != null) {
            foreach($this->syncs as $i)  {
                if($i['class'] == $class) return $i;
            }
        }
        return false;
    }

    public function dataset()
    {
        return $this->belongsTo('\CityNexus\DataStore\DataSet');
    }

}

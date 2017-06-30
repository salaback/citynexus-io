<?php

namespace App\DataStore\Model;

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
        'map',
        'syncs',
        'description',
        'frequency',
        'filters'
    ];

    protected $casts = [
        'settings' => 'array',
        'filters' => 'array',
        'syncs' => 'array',
        'map' => 'array',
    ];

    /**
     * Table name
     */
    protected $table = 'cn_uploaders';

    /**
     * The data points uploaded during upload
     *
     */

    public function dataset()
    {
        return $this->belongsTo(DataSet::class, 'dataset_id');
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class);
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
        if($this->syncs != null) {
            foreach($this->syncs as $i)  {
                if($i['class'] == $class) return true;
            }
        }
        return false;
    }

    public function getSyncClass($class)
    {
        if($this->syncs != null) {
            foreach($this->syncs as $i)  {
                if(isset($i['class']) && $i['class'] == $class) return $i;
            }
        }
        return false;
    }


}

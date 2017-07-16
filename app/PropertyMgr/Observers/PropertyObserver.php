<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 7/7/17
 * Time: 7:39 PM
 */

namespace App\PropertyMgr\Observers;

use App\Jobs\Geocode;
use App\PropertyMgr\Model\Property;
use Illuminate\Foundation\Bus\DispatchesJobs;

class PropertyObserver
{
    use DispatchesJobs;

    public function __construct()
    {

    }

    public function created(Property $property)
    {
        dispatch(new Geocode($property->id));
    }

    public function updated(Property $property)
    {
        dispatch(new Geocode($property->id));
    }

}
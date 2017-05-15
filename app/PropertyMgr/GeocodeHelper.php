<?php

namespace App\PropertyMgr;


use Geocoder\Laravel\Facades\Geocoder;
use Illuminate\Support\Facades\DB;
use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Polygon;

class GeocodeHelper
{

    public function property($id)
    {

        $property = Property::find($id);

        if($property->location == null)
        {

            $results = Geocoder::geocode($property->fullAddress);

            $address = $results->all()[0];


            DB::statement("SET search_path TO '" . config('database.connections.tenant.schema') . "','public'");
            
            if($address != null)
            {
                $property->location = new Point($address->getLatitude(), $address->getLongitude());
                $property->cords = ['lat' => $address->getLatitude(), 'lng' => $address->getLongitude()];
                $property->save();
            }

        }
    }
}
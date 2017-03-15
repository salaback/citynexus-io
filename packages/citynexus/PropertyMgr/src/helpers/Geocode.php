<?php

namespace CityNexus\PropertyMgr;


use Phaza\LaravelPostgis\Geometries\LineString;
use Phaza\LaravelPostgis\Geometries\Point;
use Phaza\LaravelPostgis\Geometries\Polygon;
use Toin0u\Geocoder\Facade\Geocoder;

class Geocode
{

    public function property($id)
    {

        $property = Property::find($id);

        if($property->location == null)
        {
            $results = Geocoder::geocode($property->fullAddress);

            $address = $results->all()[0];

            if($address != null)
            {

                $property->location = new Point($address->getLatitude(), $address->getLongitude());

                $property->save();
            }

        }
    }
}
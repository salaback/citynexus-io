<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 12/18/16
 * Time: 9:35 AM
 */

namespace CityNexus\PropertyMgr;


use App\Jobs\Geocode;
use Illuminate\Support\Facades\DB;

class PropertySync
{
    public function addPropertyID($data, $sync)
    {
        // find raw address element and send to address sync

        $return = [];

        foreach ($data as $row) {
            switch ($sync->type) {
                case 'unparsed':
                    $row['property_id'] = $this->unparsedAddress($row, $sync);
                    break;

                case 'parsed':
                    $row['property_id'] = $this->parsedAddress($row, $sync);
                    break;
            }
            $return[] = $row;
        }

        return $return;

    }

    public function parseFullAddress($address)
    {
        return (array) DB::connection('public')->select("SELECT * FROM standardize_address('tiger.pagc_lex', 'tiger.pagc_gaz', 'tiger.pagc_rules', '" . $address . "')")[0];
    }


    public function unparsedAddress($row, $sync)
    {
        $address = $this->rawUnparsedAddress($row, $sync);

        return $this->getPropertyId($address);
    }


    public function parsedAddress($row, $sync)
    {

        $raw_address = $this->rawParsedAddress($row, $sync);

        return $this->getPropertyId($raw_address);
    }

    private function rawParsedAddress($row, $sync)
    {
        $full_address = strtoupper(trim($row[$sync->street_number]));

        $full_address = $full_address . ' ' . strtoupper(trim($row[$sync->street_name]));

        if(isset($row[$sync->street_type]))
        {
            $full_address = $full_address . ' ' . strtoupper(trim($row[$sync->street_type]));

        }
        if(isset($row[$sync->unit]))
        {
           $full_address = $full_address . ' ' . strtoupper(trim($row[$sync->unit]));
        }

        if (isset($row[$sync->city]) && $row[$sync->city] != null)
            $full_address = $full_address . ', ' . strtoupper(trim($row[$sync->city]));
        else
            $full_address = $full_address . ', ' . strtoupper(trim($sync->default_city));

        if (!isset($sync->WithCityState)) {
            if (!isset($sync->StateInCity)) {
                if (isset($row[$sync->state]) && $row[$sync->state] != null)
                    $full_address = $full_address . ' ' . strtoupper(trim($row[$sync->state]));
                elseif(isset($sync->default_state))
                    $full_address = $full_address . ' ' . strtoupper(trim($sync->default_state));
            }

            if (!isset($sync->PostalCodeInCity)) {
                if (isset($row[$sync->postal_code]) && $row[$sync->postal_code] != null)
                    $full_address = $full_address . ' ' . strtoupper(trim($row[$sync->postal_code]));
                elseif(isset($sync->default_postal_code))
                    $full_address = $full_address . ' ' . strtoupper(trim($sync->default_postal_code));
            }
        }

        return $this->parseFullAddress($full_address);
    }

    /**
     *
     * Create new property record from standard address
     *
     * @param $address
     * @return mixed
     */
    public function getPropertyId($address)
    {
        if(is_string($address))
        {
            $address = $this->parseFullAddress($address);
        }

        // first or create  address record
        $addy = array_filter($address);
        if(!isset($addy['unit'])) $addy['unit'] = null;

        $addy = Address::firstOrCreate($addy);

        // if property id is set, return id
        if($addy->property_id != null) return $addy->property_id;

        // if address has unit create unit
        if($address['unit'] != null) $property_id = $this->createNewUnit($address);

        // if not a unit create a building
        else{

            $property_id = $this->createNewBuilding($address);
        }

        $addy->property_id = $property_id;
        $addy->save();

        return $property_id;

    }

    /**
     *
     * create new unit from stadard address
     *
     * @param $address
     * @return mixed
     */
    private function createNewUnit($address)
    {
        // create unit property
        $unit = [
                'address'   => $this->makeAddress($address),
                'unit'      => $address['unit'],
                'city'      => $address['city'],
                'state'     => $address['state'],
                'postcode'  => $address['postcode'],
                'country'   => $address['country'],
                'is_unit'   => true,
        ];

        $address['unit'] = null;

        $building = Property::find($this->getPropertyId($address));

        $unit['building_id'] = $building->id;
        if($building->location != null) $unit['location'] = $building->location;

        return $this->createProperty($unit);
    }

    /**
     *
     * Create new building from standard address
     *
     * @param $address
     * @return mixed
     */
    public function createNewBuilding($address)
    {
        $property = [
            'address' => $this->makeAddress($address),
            'city' => $address['city'],
            'state' => $address['state'],
            'country' => $address['country'],
            'postcode' => $address['postcode'],
            'is_building' => true,
        ];

        return $this->createProperty($property);
    }

    private function createProperty($property)
    {
        $property = Property::firstOrNew($property);

        // if a new property, geocode it
        if($property->location == null) {
            $property->save();
            dispatch(new Geocode($property->id));
        }

        $property->save();

        return $property->id;
    }

    /**
     *
     * Create a single line address from parsed address
     *
     * @param $stdAddress
     * @return null|string
     */
    private function makeAddress($stdAddress)
    {
        $return = null;

        if(isset($stdAddress['house_num'])) $return = $stdAddress['house_num'];
        if(isset($stdAddress['predir']) && $stdAddress['predir'] != null) $return = $return . ' ' . $stdAddress['predir'];
        if(isset($stdAddress['qual']) && $stdAddress['qual'] != null) $return = $return . ' ' . $stdAddress['qual'];
        if(isset($stdAddress['name']) && $stdAddress['name'] != null) $return = $return . ' ' . $stdAddress['name'];
        if(isset($stdAddress['suftype']) && $stdAddress['suftype'] != null) $return = $return . ' ' . $stdAddress['suftype'];
        if(isset($stdAddress['sufdir']) && $stdAddress['sufdir'] != null) $return = $return . ' ' . $stdAddress['sufdir'];
        if(isset($stdAddress['sufdir']) && $stdAddress['sufdir'] != null) $return = $return . ' ' . $stdAddress['sufdir'];
        if(isset($stdAddress['predir']) && $stdAddress['predir'] != null) $return = $return . ' ' . $stdAddress['predir'];

        return $return;
    }


    /**
     *
     * Create parsed address from a raw unparsed address
     *
     * @param $row
     * @param $sync
     * @return array
     */
    private function rawUnparsedAddress($row, $sync)
    {
        $return = [];

        // make uppercase
        $full_address = strtoupper(trim($row[$sync->full_address]));


        // if a city is set and is not null, use it.
        if (isset($row[$sync->city]) && $row[$sync->city] != null)
            $full_address = $full_address . ', ' . strtoupper(trim($row[$sync->city]));
        else
            $full_address = $full_address . ', ' . strtoupper(trim($sync->default_city));

        // if city, state, and postal code not in full address
        if (!isset($sync->WithCityState)) {

            // if state  not is city field
            if (!isset($sync->StateInCity)) {

                // if state is offered and is not null use it
                if (isset($row[$sync->state]) && $row[$sync->state] != null)
                    $full_address = $full_address . ' ' . strtoupper(trim($row[$sync->state]));
                elseif(isset($sync->default_state))

                    // else, if state is not offered, format and use default
                    $full_address = $full_address . ' ' . strtoupper(trim($sync->default_state));
            }

            // if postal code was not in the city
            if (!isset($sync->PostalCodeInCity)) {

                // and postal code is set use it
                if (isset($row[$sync->postal_code]) && $row[$sync->postal_code] != null)
                    $full_address = $full_address . ' ' . strtoupper(trim($row[$sync->postal_code]));
                elseif(isset($sync->default_postal_code))
                    // else format and use default
                    $full_address = $full_address . ' ' . strtoupper(trim($sync->default_postal_code));
            }

        }

        return $this->parseFullAddress($full_address);
    }



}
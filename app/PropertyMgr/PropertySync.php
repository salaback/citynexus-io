<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 12/18/16
 * Time: 9:35 AM
 */

namespace App\PropertyMgr;


use App\Jobs\Geocode;
use App\DataStore\Model\Upload;
use App\PropertyMgr\Model\Address;
use App\PropertyMgr\Model\Property;
use App\PropertyMgr\Model\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PropertySync
{
    /*
     *
     * Needs testing
     *
     */
    public function addPropertyID($data, $syncs)
    {
        // find raw address element and send to address sync
        $return = [];

        $sync = $syncs[0];

        if(isset($sync['full_address'])) {

            foreach ($data as $row) {

                if ($result = $this->unparsedAddress($row, $sync)) {
                    $row['property_id'] = $result;
                } else {
                    $row['property_id'] = null;
                }
                $return[] = $row;
            }
        }else {
            foreach ($data as $row) {
                if($result = $this->parsedAddress($row, $sync))
                {
                    $row['property_id'] = $result;
                } else {
                    $row['property_id'] = null;
                }
                $return[] = $row;
            }
        }
        return $return;
    }

    public function addTags($data, $syncs)
    {
        foreach($syncs as $sync)
        {
            switch ($sync['method'])
            {
                case 'value':
                    $this->valueTags($data, $sync);
                    break;
                case 'comma':
                    $this->valueTags($data, $sync, true);
            }
        }

    }

    private function valueTags($data, $sync, $comma = false)
    {
        $tags = [];
        $insert =[];
        foreach($data as $row)
        {
            if(isset($row['property_id']) && $row[$sync['key']] != null)
            {
                if($comma)
                {
                    $parts = explode(',', $row[$sync['key']]);

                    foreach($parts as $part)
                    {
                        $tags[trim($part)] = $row['property_id'];
                    }
                }
                else
                {
                    $tags[$row[$sync['key']]][] = $row['property_id'];
                }
            }
        }

        foreach($tags as $tag => $ids)
        {
            $tag = Tag::firstOrCreate(['tag' => $tag]);

            foreach($ids as $id)
            {
                $insert[] = [
                    'tag_id' => $tag->id,
                    'tagables_type' => '\\App\\PropertyMgr\\Model\\Property',
                    'tagables_id' => $id,
                ];
            }

        }

        DB::table('cn_tagables')->insert($insert);

        return true;
    }


    /*
     *
     * Needs testing
     *
     */
    public function addCreatedAt($data, $sync)
    {
        foreach($data as $key => $item)
        {
            if(!isset($data[$key][$sync['created_at']]))
                $data[$key]['created_at'] = Carbon::now();
            else
            {
                $data[$key]['created_at'] = $data[$key][$sync['created_at']];

            }
        }
        return $data;
    }

    /*
     *
     * Passed test 7/27/2017
     *
     * Takes is a full address and sends through the tiger address parser
     * returning address array which can be added to DB
     *
     */
    public function parseFullAddress($address)
    {
        $address = $this->addressFilters($address);

        $return = (array) DB::connection('public')->select("SELECT * FROM standardize_address('tiger.pagc_lex', 'tiger.pagc_gaz', 'tiger.pagc_rules', '" . $address . "')")[0];

        $return['house_num'] = explode(' ', $return['house_num'])[0];

        $return['unit'] = $this->cleanUnit($return['unit']);

        return $return;
    }

    /*
     *
     * Needs testing
     *
     */
    public function addressFilters($address)
    {
        $address = str_replace("'", "", $address);

        $filters = ['LYNN ST EXT' => 'LYNN STREET EXTENSION'];

        foreach($filters as $needle => $thread)
        {
            $address = str_replace($needle, $thread, $address);
        }
        return $address;
    }

    /*
     *
     * Needs testing
     *
     */
    public function unparsedAddress($row, $sync)
    {
        $address = $this->rawUnparsedAddress($row, $sync);
        return $this->getPropertyId($address);
    }


    /*
     *
     * Needs testing
     *
     */
    public function parsedAddress($row, $sync)
    {

        $raw_address = $this->rawParsedAddress($row, $sync);

        return $this->getPropertyId($raw_address);
    }

    /*
     *
     * Needs testing
     *
     */
    private function rawParsedAddress($row, $sync)
    {
        $full_address = strtoupper(trim($row[$sync['house_number']]));

        $full_address = $full_address . ' ' . strtoupper(trim($row[$sync['street_name']]));

        if(isset($sync['street_type']) && isset($row[$sync['street_type']]))
        {
            $full_address = $full_address . ' ' . strtoupper(trim($row[$sync['street_type']]));

        }
        if(isset($sync['unit']) && isset($row[$sync['unit']]))
        {

           $full_address = $full_address . ' ' . $this->cleanUnit(strtoupper(trim($row[$sync['unit']])));
        }

        if (isset($sync['city']) && isset($row[$sync['city']]) && $row[$sync['city']] != null)
            $full_address = $full_address . ', ' . strtoupper(trim($row[$sync['city']]));
        elseif(isset($sync['default_city']))
            $full_address = $full_address . ', ' . strtoupper(trim($sync['default_city']));
        else
            $full_address = $full_address . ', ' . strtoupper(config('client.city'));


        if (!isset($sync['WithCityState'])) {
            if (!isset($sync['StateInCity'])) {
                if (isset($sync['state']) && isset($row[$sync['state']]) && $row[$sync['state']] != null)
                    $full_address = $full_address . ' ' . strtoupper(trim($row[$sync['state']]));
                elseif(isset($sync['default_state']))
                    $full_address = $full_address . ' ' . strtoupper(trim($sync['default_state']));
                else
                    $full_address = $full_address . ' ' . strtoupper(trim(config('client.city')));

            }

            if (!isset($sync['PostcodeInCity'])) {
                if (isset($sync['postcode']) && isset($row[$sync['postcode']]) && $row[$sync['postcode']] != null)
                    $full_address = $full_address . ' ' . strtoupper(trim($row[$sync['postcode']]));
                elseif(isset($sync['default_postcode']))
                    $full_address = $full_address . ' ' . strtoupper(trim($sync['default_postcode']));
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

        $addy = Address::firstOrCreate($addy);

        // if property id is set, return id
        if($addy->property_id != null) {
            return $addy->property_id;
        }

        // if address has unit create unit
        if($address['unit'] != null) $property_id = $this->createNewUnit($addy->toArray());

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
        $unit['address']   = $this->makeAddress($address);
        $unit['unit']      = $address['unit'];
        if(isset($unit['city'])) $unit['city'] = $address['city']; else $unit['city'] = null;
        if(isset($unit['state']))$unit['state']     = $address['state']; else $unit['state'] = null;
        if(isset($unit['postcode']))$unit['postcode']  = $address['postcode']; else $unit['postcode'] = null;
        if(isset($unit['country']))$unit['country']  = $address['country']; else $unit['country'] = null;
        $unit['is_unit']   = true;

        $address['unit'] = null;

        if($this->getPropertyId($address))
        {
            $building = Property::find($this->getPropertyId($address));
        }
        else
            return false;

        $unit['building_id'] = $building->id;

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
        $property['address']   = $this->makeAddress($address);
        if(isset($address['city'])) $property['city'] = $address['city']; else $property['city'] = null;
        if(isset($address['state']))$property['state']     = $address['state']; else $property['state'] = null;
        if(isset($address['postcode']))$property['postcode']  = $address['postcode']; else $property['postcode'] = null;
        if(isset($address['country']))$property['country']  = $address['country']; else $property['country'] = null;
        $property['is_building'] = true;

        return $this->createProperty($property);
    }

    private function createProperty($parts)
    {
        if(isset($parts['is_building']) && $parts['is_building'])
            $property = Property::firstOrNew([
                'address' => $parts['address'],
                'is_building' => true
            ]);
        elseif(isset($parts['unit']) && $parts['unit'])
            $property = Property::firstOrNew([
                'address' => $parts['address'],
                'unit' => $parts['unit'],
                'is_unit' => true,
                'building_id' => $parts['building_id']
            ]);


        if($property->city == null || $property->state == null || $property->postcode == null || $property->county == null)
        {
            if(isset($parts['city']))
            {
            $property->city = $parts['city'];
            }
            if(isset($parts['state']))
            {
                $property->state = $parts['state'];
            }
            if(isset($parts['postcode']))
            {
                $property->postcode = $parts['postcode'];
            }
            if(isset($parts['country']))
            {
                $property->country = $parts['country'];
            }
        }

        if(!$property->exists && session('upload_id') != null)
        {
            $property->save();
            $upload = Upload::find(session('upload_id'));
            $new_ids = $upload->new_property_ids;
            $new_ids[] = $property->id;
            $upload->new_property_ids = $new_ids;
            $upload->save();
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
        $full_address = strtoupper(trim($row[$sync['full_address']]));

        // if a city is set and is not null, use it.
        if (isset($sync['city']) && isset($row[$sync['city']]) && $row[$sync['city']] != null)
            $full_address = $full_address . ', ' . strtoupper(trim($row[$sync['city']]));
        elseif(isset($sync['default_city']))
            $full_address = $full_address . ', ' . strtoupper(trim($sync['default_city']));
        else{
            $full_address = $full_address . ', ' . config('client.city');
        }

        // if city, state, and postal code not in full address
        if (!isset($sync['WithCityState'])) {

            // if state  not is city field
            if (!isset($sync['StateInCity'])) {

                // if state is offered and is not null use it
                if (isset($sync['state']) && isset($row[$sync['state']]) && $row[$sync['state']] != null)
                    $full_address = $full_address . ' ' . strtoupper(trim($row[$sync['state']]));
                elseif(isset($sync['default_state']))

                    // else, if state is not offered, format and use default
                    $full_address = $full_address . ' ' . strtoupper(trim($sync['default_state']));
                else
                    $full_address = $full_address . ', ' . config('client.state');
            }

            // if postal code was not in the city
            if (!isset($sync['PostalCodeInCity'])) {
                // and postal code is set use it
                if (isset($sync['postal_code']) && isset($row[$sync['postal_code']]) && $row[$sync['postal_code']] != null)
                    $full_address = $full_address . ' ' . strtoupper(trim($row[$sync['postal_code']]));
                elseif(isset($sync['default_postal_code']))
                    // else format and use default
                    $full_address = $full_address . ' ' . strtoupper(trim($sync['default_postal_code']));
            }

        }

        return $this->parseFullAddress($full_address);
    }

    public function mergeProperties($new_id, $old_ids)
    {
        // Check if an array of old ids exists, otherwise convert to an array
        if(!is_array($old_ids))
        {
            $old_ids[] = $old_ids;
        }


        foreach($old_ids as $i)
        {
            DB::table('citynexus_images')->where('property_id', $i)->update(['property_id' => $id]);
            DB::table('citynexus_notes')->where('property_id', $i)->update(['property_id' => $id]);
            DB::table('citynexus_raw_addresses')->where('property_id', $i)->update(['property_id' => $id]);
            DB::table('citynexus_taskables')->where('citynexus_taskable_id', $i)->where('citynexus_taskable_type', 'CityNexus\CityNexus\Property')->update(['citynexus_taskable_id' => $id]);
            DB::table('property_tag')->where('property_id', $i)->update(['property_id' => $id]);

            foreach ($datasets as $tn) {
                if (Schema::hasTable($tn)) {
                    DB::table($tn)->where('property_id', $i)->update(['property_id' => $id]);
                }
            }
        }
    }

    public function cleanUnit($unit)
    {
        $parts = explode(' ', $unit);
        $apt_names = ['UNIT' => 'UNIT', '#' => '#', 'UT' => 'UNIT', 'APT' => 'APARTMENT', 'APT.' => 'APARTMENT', 'APARTMENT' => 'APARTMENT', 'NO' => 'NUMBER', 'NO.' => 'NUMBER', 'NUMBER' => 'NUMBER', 'LOT' => 'LOT'];
        $return = '';
        foreach ($parts as $k => $i)
        {
            if(!isset($apt_names[$i]))
            {
                $return = trim($return . ' ' . $i);
            }
        }

        return $return;
    }
}
<?php


namespace App\PropertyMgr;

use App\Jobs\Geocode;
use App\PropertyMgr\Model\EntityAddress;
use App\PropertyMgr\Property;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EntitySync
{
    public function __construct()
    {
        $this->titles = [
            'MR' => 'MR',
            'MISTER' => 'MR',
            'MS' => 'MS',
            'MISS' => 'MISS',
            'MISSES' => 'MRS',
            'MRS' => 'MRS',
            'DR' => 'DR',
            'LT' => 'LT',
            'CAPT' => 'CAPT',
            'CMDR' => 'CMDR',
            'CPL' => 'CPL',
            'FATHER' => 'FATHER',
            'GEN' => 'GEN',
            'PASTOR' => 'PASTOR',
            'HON' => 'HON',
            'HONORABLE' => 'HON',
            'MAJ' => 'MAJ',
            'PROF' => 'PROF',
            'PROFESSOR' => 'PROF',
            'SEN' => 'SEN',
            'SENATOR' => 'SEN',
            'SGT' => 'SGT'

        ] ;
        $this->suffixes = [
            'SR' => 'SR',
            'SENIOR' => 'SR',
            'JR' => 'JR',
            'JUNIOR' => 'JR',
            'II' => 'II',
            'III' => 'III',
            'MD' => 'M.D.',
            'PHD' => 'Ph.D.',
            '(RET)' => '(RET)',
            'CPA' => 'C.P.A.',
            'DDS' => 'D.D.S.',
            'ESP' => "ESP",
            'ESQ' => 'ESQ.',
            'RN' => 'R.N.',
            'PA' => 'P.A.',
            'MBA' => 'MBA'
        ];
        $this->structures = [
            'TRUST' => 'TRUST',
            'LLC' => 'LLC',
            'LC' => 'LC',
            'COMPANY' => 'COMPANY',
            'LTD' => 'LTD',
            'PC' => 'PC',
            'INC' => 'INC',
            'CORP' => 'CORP',
            'INCORPORATED' => 'INCORPORATED',
            'LIMITED' => 'LIMITED',
            'CORPORATION' => 'CORPORATION',
            'LLP' => 'LLP'
        ];

        $this->states = array(
        'AL'=>'ALABAMA',
        'AK'=>'ALASKA',
        'AS'=>'AMERICAN SAMOA',
        'AZ'=>'ARIZONA',
        'AR'=>'ARKANSAS',
        'CA'=>'CALIFORNIA',
        'CO'=>'COLORADO',
        'CT'=>'CONNECTICUT',
        'DE'=>'DELAWARE',
        'DC'=>'DISTRICT OF COLUMBIA',
        'FM'=>'FEDERATED STATES OF MICRONESIA',
        'FL'=>'FLORIDA',
        'GA'=>'GEORGIA',
        'GU'=>'GUAM GU',
        'HI'=>'HAWAII',
        'ID'=>'IDAHO',
        'IL'=>'ILLINOIS',
        'IN'=>'INDIANA',
        'IA'=>'IOWA',
        'KS'=>'KANSAS',
        'KY'=>'KENTUCKY',
        'LA'=>'LOUISIANA',
        'ME'=>'MAINE',
        'MH'=>'MARSHALL ISLANDS',
        'MD'=>'MARYLAND',
        'MA'=>'MASSACHUSETTS',
        'MI'=>'MICHIGAN',
        'MN'=>'MINNESOTA',
        'MS'=>'MISSISSIPPI',
        'MO'=>'MISSOURI',
        'MT'=>'MONTANA',
        'NE'=>'NEBRASKA',
        'NV'=>'NEVADA',
        'NH'=>'NEW HAMPSHIRE',
        'NJ'=>'NEW JERSEY',
        'NM'=>'NEW MEXICO',
        'NY'=>'NEW YORK',
        'NC'=>'NORTH CAROLINA',
        'ND'=>'NORTH DAKOTA',
        'MP'=>'NORTHERN MARIANA ISLANDS',
        'OH'=>'OHIO',
        'OK'=>'OKLAHOMA',
        'OR'=>'OREGON',
        'PW'=>'PALAU',
        'PA'=>'PENNSYLVANIA',
        'PR'=>'PUERTO RICO',
        'RI'=>'RHODE ISLAND',
        'SC'=>'SOUTH CAROLINA',
        'SD'=>'SOUTH DAKOTA',
        'TN'=>'TENNESSEE',
        'TX'=>'TEXAS',
        'UT'=>'UTAH',
        'VT'=>'VERMONT',
        'VI'=>'VIRGIN ISLANDS',
        'VA'=>'VIRGINIA',
        'WA'=>'WASHINGTON',
        'WV'=>'WEST VIRGINIA',
        'WI'=>'WISCONSIN',
        'WY'=>'WYOMING',
        'AE'=>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
        'AA'=>'ARMED FORCES AMERICA (EXCEPT CANADA)',
        'AP'=>'ARMED FORCES PACIFIC'
    );

    }

    public function parseName($name, $format = null)
    {
        // capitalize name
        $name = strtoupper($name);

        $ln_test = explode(',', $name);

        // remove periods and commas
        $name = str_replace('.', '', $name);
        $name = str_replace(',', '', $name);

        // explode name
        $parts = explode(' ', $name);

        // check for a company structure
        if (isset($this->structures[end($parts)])) {
            $return = $this->parseCompany($parts);

        // check if the first name listed is followed by a comma
        } elseif($ln_test[0] == $parts[0] || $format == 'LastFirstM') {
            $return = $this->parseLastNameFirst($parts);

        } else {
            $return = $this->parsePerson($parts);
        }

        return $return;
    }

    private function parseLastNameFirst($parts)
    {
        // set last name
        $return['last_name'] = $parts[0];

        if(count($parts) == 3)
        {
            if(isset($this->titles[$parts[1]]))
            {
                $return['title'] = $this->titles[$parts[1]];
                $return['first_name'] = $parts[2];
            }
            $return['first_name'] = $parts[1];

            $return['middle_name'] = $parts[2];
        }

        return $return;
    }

    private function parseCompany($parts)
    {

        $return = ['company_name' => '', 'company_structure' => ''];

        // If last part of name is type of company structure, add to the company structure
        while(isset($this->structures[end($parts)]))
        {
            $return['company_structure'] = trim($return['company_structure'] . ' ' . $this->structures[array_pop($parts)]);
        }

        // after structure captured, use the rest of items as comapny name
        while(count($parts) > 0)
        {
            $return['company_name'] = trim(array_pop($parts) . ' ' . $return['company_name']);
        }

        return $return;
    }


    private function parsePerson($parts)
    {

        $return = [];

        // check for titles or suffixes
        if(isset($this->titles[$parts[0]]))
        {
            $return['title'] = $this->titles[$parts[0]];
            unset($parts[0]);
        }

        if(isset($this->suffixes[end($parts)]))
        {
            $return['suffix'] = $this->suffixes[array_pop($parts)];
        }

        // reset the pointer
        $parts = array_values($parts);

        // count names
        $word_count = count($parts);

        // build array

        switch ($word_count)
        {
            case 1:
                $return['last_name'] = $parts[0];
                break;
            case 2:
                $return['first_name'] = $parts[0];
                $return['last_name'] = $parts[1];
                break;
            case 3:
                $return['first_name'] = $parts[0];
                $return['middle_name'] = $parts[1];
                $return['last_name'] = $parts[2];
                break;
            default:
                $return['first_name'] = $parts[0];
                unset($parts[0]);
                $return['last_name'] = null;
                while(count($parts) >0)
                {
                    $return['last_name'] = $return['last_name'] . array_shift($parts);
                }
        }

        // return
        return $return;
    }

    public function syncAddress($address, $sync)
    {
        if($sync['type'] == 'parsed')
        {
            $newAddress = $this->syncParsedAddress($address, $sync);
        }

        elseif ($sync['type'] == 'unparsed')
        {
            $newAddress = $this->syncUnparsedAddress($address, $sync);
        }

        return $newAddress;
    }

    private function syncParsedAddress($parts, $sync)
    {
        $address = $parts[$sync['house_number']];
        $city = null;
        $state = null;
        $postcode = null;

        $address .= ' ' . $parts[$sync['street_name']];

        if(!isset($sync['TypeInStreetName']) && $sync['street_type'] != null)
            $address .= ' ' . $parts[$sync['street_type']];

        if(!isset($sync['UnitInStreetName']) && $parts[$sync['unit']])
            $address .= ', Unit ' . $parts[$sync['unit']];


        if(isset($parts[$sync['city']]) && $parts[$sync['city']] != null)
        {
            $city = $parts[$sync['city']];
        }
        if(isset($sync['default_city']))
        {
            $city = $sync['default_city'];
        }


        if(isset($sync['StateInCity'])){
            $temp = $this->removeStateFromCity($city, $sync);
            $city = $temp['city'];
            $state = $temp['state'];
            $postcode = $temp['postcode'];
        }


        if($sync['state'] != null || isset($sync['default_state']))
        {

            if($sync['state'] != null && isset($parts[$sync['state']]) && $parts[$sync['state']] != null)
            {
                $state = $parts[$sync['state']];
            }elseif (isset($sync['default_state']))
            {
                $state = $sync['default_state'];
            }
        }

        if($sync['postcode'] != null || $postcode == null)
        {

            if($sync['postcode'] != null && isset($parts[$sync['postcode']]) && $parts[$sync['postcode']] != null)
            {
                $postcode = $parts[$sync['postcode']];
            }elseif(isset($sync['default_postcode']))
            {
                $postcode = $sync['default_postcode'];
            }
        }

        return [
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'postcode' => $postcode
        ];
    }

    private function removeStateFromCity($city, $sync)
    {
        $parts = explode(' ', $city);
        $return = [
            'city' => null,
            'state' => null,
            'postcode' => null
        ];

        foreach($parts as $k => $i)
        {
            $abv = strtoupper($i);
            if(isset($this->states[$abv]))
            {
                $return['state'] = $abv;
                unset($parts[$k]);
                if(isset($sync['PostcodeInCity']) && preg_match('/(^\d{5}(?:[\s]?[-\s][\s]?\d{4})?$)/', $parts[$k+1]))
                {
                    $return['postcode'] = $parts[$k + 1];
                }
                break;
            }
            else
            {
                $return['city'] = $i . ' ';
            }
        }

        // remove trailing comma and or spaces

        $return['city'] = preg_replace('/,+$/', '', trim($return['city']));

        return $return;

    }

}
<?php


namespace CityNexus\PropertyMgr;

use App\Jobs\Geocode;
use CityNexus\PropertyMgr\Property;
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
    }

    public function parseName($name)
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
        } elseif($ln_test[0] == $parts[0]) {
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

}
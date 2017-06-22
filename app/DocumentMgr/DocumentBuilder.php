<?php

namespace App\DocumentMgr;

use App\DocumentMgr\Model\Document;
use App\DocumentMgr\Model\DocumentTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DocumentBuilder
{
    public function buildDocument($id, $models = array())
    {
        $template = DocumentTemplate::find($id);
        $data = $this->createDataArray($template->body);
        $data = $this->loadDataArray($data, $models);
        $body = $this->createBody($template->body, $data);

        return Document::create([
            'cn_document_template_id' => $template->id,
            'body' => $body,
            'history' => [Carbon::now()->toDateTimeString() => ['action' => 'created']],
            'created_by' => Auth::id()
        ]);
    }
    public function createDataArray($body)
    {
        $data = [];

        $elements = explode('&lt;&lt;', $body);

        foreach($elements as $key => $item)
        {
            $bits = explode(' ', $item);
            if($bits[0] == "-") $data = $this->extractElementForArray($bits[1], $data);

        }

        return $data;
    }

    public function loadDataArray($data, $models = array())
    {

        foreach($data as $item => $key)
        {
            foreach($key as $i => $k) {
                switch ($item) {
                    case 'building':
                        if(isset($models['property']))
                        $data[$item][$i] = $this->building($i, $models['property']);
                        break;

                    case 'unit':
                        if(isset($models['property']))
                            $data[$item][$i] = $this->unit($i, $models['property']);
                        break;

                    case 'entity':
                        if(isset($models['entity']))
                            $data[$item][$i] = $this->entity($i, $models['entity']);
                        break;

                    case 'sender':
                        if(isset($models['sender']))
                            $data[$item][$i] = $this->sender($i, $models['sender']);
                        break;

                    case 'misc':
                        $data[$item][$i] = $this->misc($i, $models);
                        break;
                }
            }
        }

        return $data;
    }

    private function createBody($body, $data)
    {
        $return = '';
        $parts = explode('&lt;&lt;', $body);
        foreach($parts as $key => $item)
        {
            $bits = explode(' ', $item);
            if($bits[0] == '-')
            {
                $bits[1] = $this->replaceKey($bits[1], $data);
                unset($bits[0]);
                $bits[2] = str_replace('-&gt;&gt;', '', $bits[2]);
                $bits[1] = $bits[1] . $bits[2];
                unset($bits[2]);

            }

            $return .= implode(' ', $bits);
        }

        return $return;
    }

    private function replaceKey($key, $data)
    {
        $bits = explode(':', $key);

        return $data[$bits[0]][$bits[1]];
    }
    private function building($i, $model)
    {
        if(!$model->is_building)
            $model = $model->building;
        switch ($i)
        {
            case "address":
                return $model->oneLineAddress;
                break;

            case "units":
                return $model->units->count();
                break;

            default:
                return $model->$i;
        }
    }


    private function unit($i, $model)
    {
        switch ($i)
        {
            case "address":
                return $model->oneLineAddress;
                break;

            default:
                return $model->$i;
        }
    }

    private function entity($i, $model)
    {
        switch ($i)
        {
            case 'full_name':
                return $model->name;
            default:
                return $model->$i;
        }
    }

    private function sender($i, $model)
    {
        switch ($i)
        {
            case 'full_name':
                return $model->fullname;
            default:
                return $model->$i;
        }
    }



    private function misc($i, $models)
    {
        switch ($i)
        {
            case 'document_id':
                if(isset($models['template']))
                    return $models['template']->id;
                else
                    return null;
                break;

            case 'queue_id':
                if(isset($models['queue']))
                    return $models['queue']->id;
                else
                    return null;
                break;

            case 'printed_at':
                if(isset($models['queue']) && $models['queue']->printed_at)
                    return $models['queue']->printed_at;
                else
                    return null;
                break;

            case 'created_at':
                if(isset($models['queue']) && $models['queue']->created_at)
                    return $models['queue']->created_at;
                else
                    return null;
                break;

        }
    }

    private function extractElementForArray($element, $data)
    {
        $bits = explode(':', $element);

        $data[$bits[0]][$bits[1]] = null;

        return $data;
    }

}
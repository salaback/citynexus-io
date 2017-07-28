<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 4/13/17
 * Time: 11:51 AM
 */

namespace App\Services;


use App\Client;
use App\PropertyMgr\Model\Entity;
use App\Tag;
use App\DataStore\Model\DataSet;
use App\PropertyMgr\Model\Comment;
use App\PropertyMgr\Model\File;
use App\PropertyMgr\Model\Property;
use Illuminate\Support\Facades\DB;

class IndexSearch
{
    public function run($id = null)
    {
        if($id == null)
        {
            $clients = Client::all();
        }
        else
        {
            $clients[] = Client::find($id);
        }

        foreach($clients as $client)
        {
            $client->logInAsClient();

            try
            {
                $this->indexProperties();
            }
            catch (\Exception $e)
            {
                print $e->getMessage();
            }

            try
            {
                $this->indexTags();
            }
            catch (\Exception $e)
            {
                print $e->getMessage();
            }

            try
            {
                $this->indexFiles();
            }
            catch (\Exception $e)
            {
                print $e->getMessage();
            }

            try
            {
                $this->indexDataSets();
            }
            catch (\Exception $e)
            {
                print $e->getMessage();
            }

            try
            {
                $this->indexComments();
            }
            catch (\Exception $e)
            {
                print $e->getMessage();
            }

            try
            {
                $this->indexEntities();
            }
            catch (\Exception $e)
            {
                print $e->getMessage();
            }
        }
    }

    private function indexProperties()
    {
        $properties = Property::where('is_building', 1)->get();

        if($properties->count() > 0)
        {
            $index = array();
            foreach($properties as $property)
            {
                $property->units->count() > 0 ? $building = 'Building' : $building = 'House';

                $index[] =[
                    'type' => $building,
                    'search' => ucwords(strtolower($property->oneLineAddress)),
                    'link' => '/properties/' . $property->id
                ];
            }

            DB::table('search_results')->where('type', 'Building')->delete();
            DB::table('search_results')->where('type', 'House')->delete();
            DB::table('search_results')->insert($index);
        }
    }

    private function indexEntities()
    {
        $entities = Entity::all();

        if($entities->count() > 0)
        {
            $index = array();
            foreach($entities as $entity)
            {
                $index[] =[
                    'type' => 'Entity',
                    'search' => ucwords(strtolower($entity->name)),
                    'link' => '/entity/' . $entity->id
                ];
            }

            DB::table('search_results')->where('type', 'Entity')->delete();
            DB::table('search_results')->insert($index);
        }
    }

    private function indexTags()
    {
        $tags = Tag::all();

        if($tags->count() > 0)
        {
            $index = array();
            foreach($tags as $tag)
            {
                $index[] =[
                    'type' => 'Tag',
                    'search' => $tag->tag,
                    'link' => route('tag.show', [$tag->id])
                ];
            }

            DB::table('search_results')->where('type', 'Tag')->delete();
            DB::table('search_results')->insert($index);
        }
    }


    private function indexDataSets()
    {
        $datasets = DataSet::all();

        if($datasets->count() > 0)
        {
            $index = array();
            foreach($datasets as $dataset)
            {
                $index[] =[
                    'type' => 'Data Set',
                    'search' => $dataset->name,
                    'link' => '/dataset/' . $dataset->id
                ];
            }

            DB::table('search_results')->where('type', 'Data Set')->delete();
            DB::table('search_results')->insert($index);
        }
    }

    private function indexComments()
    {
        $comments = Comment::all();

        if($comments->count() > 0)
        {
            $index = array();
            foreach($comments as $comment)
            {
                $index[] =[
                    'type' => 'Comment',
                    'search' => $comment->title . ' ' . $comment->comment,
                    'link' => route('comments.show', [$comment->id])
                ];
            }

            DB::table('search_results')->where('type', 'Comment')->delete();
            DB::table('search_results')->insert($index);
        }
    }

    private function indexFiles()
    {
        $files = File::all();

        if($files->count() > 0)
        {
            $index = array();
            foreach($files as $file)
            {
                $index[] =[
                    'type' => 'File',
                    'search' => $file->caption . ' ' . $file->description,
                    'link' => route('files.show', [$file->id])
                ];
            }

            DB::table('search_results')->where('type', 'File')->delete();
            DB::table('search_results')->insert($index);
        }
    }
}
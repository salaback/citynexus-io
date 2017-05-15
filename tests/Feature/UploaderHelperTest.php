<?php

namespace Tests\Feature;

use App\Services\MultiTenant;
use Carbon\Carbon;
use App\DataStore\Jobs\ProcessData;
use App\DataStore\Model\DataSet;
use App\DataStore\UploadHelper;
use App\DataStore\Model\Upload;
use App\DataStore\Model\Uploader;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UploaderHelperTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCSVUpload()
    {
        Queue::fake();

        $uploadHelper = new UploadHelper();

        $uploadHelper->csvUpload(999, []);

        Queue::assertPushed(ProcessData::class, function($job)
        {
           return $job->upload_id == 999;
        });
    }
}

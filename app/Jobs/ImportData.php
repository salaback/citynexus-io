<?php
namespace App\Jobs;
use App\Jobs\Job;
use CityNexus\CityNexus\TableBuilder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Toin0u\Geocoder\Facade\Geocoder;


class ImportData extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    private $table;
    private $source;
    private $target_schema;


    /**
     * Create a new job instance.
     *
     * @param string $data
     * @param Property $upload_id
     */
    public function __construct($table, $source, $target_schema)
    {
        $this->table = $table;
        $this->source = $source;
        $this->target_schema = $target_schema;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(TableBuilder $tableBuilder)
    {
        $importDb = $this->source;

        config([
            'database.connections.import' => $importDb,
            'database.connections.tenant.schema' => $this->target_schema,
            'database.default' => 'tenant'
        ]);

        $tableBuilder->create($this->table);


        DB::connection('import')->table($this->table->table_name)->orderBy('id')->chunk(500, function($data)
        {
            $first = array_shift($data)->id;
            $last = last($data)->id;

            $this->dispatch(new ImportDbChunk($first, $last, $this->table->table_name, $this->source, $this->target_schema));

        });


    }
}
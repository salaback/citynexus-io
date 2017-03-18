<?php
namespace App\Jobs;
use App\Jobs\Job;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Toin0u\Geocoder\Facade\Geocoder;


class ImportDb extends Job implements ShouldQueue
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
    public function handle()
    {
        DB::reconnect();

        config([
            'database.connections.import' => $this->source,
            'database.connections.tenant.schema' => $this->target_schema,
        ]);

        if(!Schema::hasColumn($this->table, 'id')){
            $this->dispatch(new ImportDbChunk(null, null, $this->table, $this->source, $this->target_schema));
        }
        else
        {
            DB::connection('import')->table($this->table)->orderBy('id')->chunk(250, function($data)
            {

                $first = array_shift($data)->id;

                if(count($data) > 1)
                {
                    $last = last($data)->id;
                }
                else
                {
                    $last = $first;
                }

                DB::statement("SET search_path TO 'public'");

                $this->dispatch(new ImportDbChunk($first, $last, $this->table, $this->source, $this->target_schema));

            });
        }

    }
}

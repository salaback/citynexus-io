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


class ImportDb extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;


    protected $table;
    protected $source;
    protected $schema;


    /**
     * Create a new job instance.
     *
     * @param $table
     * @param $source
     * @param $schema
     * @internal param $target_schema
     * @internal param string $data
     * @internal param Property $upload_id
     */
    public function __construct($table, $source, $client_schema)
    {
        $this->table = $table;
        $this->source = $source;
        $this->schema = $client_schema;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        config([
            'database.connections.import' => $this->source,
            'database.connections.tenant.schema' => $this->schema,
        ]);

        if(!Schema::hasColumn($this->table, 'id')){
            $this->dispatch(new ImportDbChunk(null, null, $this->table, $this->source, $this->schema));
        }
        else
        {

            DB::connection('import')->table($this->table)->orderBy('id')->chunk(250, function($data)
            {

                $first = $data->first()->id;

                if(count($data) > 1)
                {
                    $last = $data->last()->id;
                }
                else
                {
                    $last = $first;
                }
                DB::statement("SET search_path TO 'public'");
                $this->dispatch(new ImportDbChunk($first, $last, $this->table, $this->source, $this->schema));

            });
        }

    }
}

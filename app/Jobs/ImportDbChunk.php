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
use Toin0u\Geocoder\Facade\Geocoder;


class ImportDbChunk extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;


    private $table;
    private $source;
    private $target_schema;
    private $first;
    private $last;


    /**
     * Create a new job instance.
     *
     * @param string $data
     * @param Property $upload_id
     */
    public function __construct($first, $last, $table, $source, $target_schema)
    {
        $this->table = $table;
        $this->source = $source;
        $this->target_schema = $target_schema;
        $this->first = $first;
        $this->last = $last;
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


        if($this->first == null or $this->last == null)
        {
            $data = DB::connection('import')->table($this->table)
                ->get();
        }
        else
        {
            $data = DB::connection('import')->table($this->table)
                ->where('id', '>=', $this->first)
                ->where('id', '<=', $this->last)
                ->get();
        }


        $data = collect($data)->map(function($x){ return (array) $x; })->toArray();

        DB::statement("SET search_path TO " . $this->target_schema . ',public');

        DB::table($this->table)->insert($data);

    }
}

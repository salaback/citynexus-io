<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    protected $connection = 'public';

    protected $fillable = ['name', 'domain', 'schema', 'migrated_at','active', 'settings'];

    protected $casts = [
        'active' => 'boolean',
        'settings' => 'array',
        'info' => 'array'
    ];

    protected $dates = ['migrated_at', 'created_at', 'updated_at'];


    public function version()
    {
        return $this->belongsTo(Version::class);
    }

    public function logInAsClient()
    {
        $settings = $this->settings;

        // set tenant db
        config([
            'client' => $settings,
            'client.id' => $this->id,
            'database.connections.tenant.schema' => $this->schema,
            'schema' => $this->schema,
        ]);

        DB::disconnect('tenant');
        DB::reconnect('tenant');

        return true;
    }
}

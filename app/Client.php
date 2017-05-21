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
            'domain' => $this->domain,
        ]);

        DB::disconnect('tenant');
        DB::reconnect('tenant');

        return true;
    }

    /**
     *
     * Add user to organization
     *
     * @param $user
     * @param array $options
     * @return string
     */
    public function addUser($user, $options = array())
    {
        try {

            $options = [
                'title' => $options['title'] ?: null,
                'department' => $options['department'] ?: null
            ];

            $user->addMembership($this->domain, $options, true);

        }
        catch (\Exception $e)
        {
            return 'error';
        }

        return 'added';
    }
}

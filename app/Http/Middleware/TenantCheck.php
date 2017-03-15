<?php

namespace App\Http\Middleware;

use App\Client;
use App\Organization;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TenantCheck
{
    public function __construct()
    {
        $this->except_urls = [
            '/login',
        ];
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // check that we aren't testing or in an excluded uri
        $domain = $request->capture()->server->get('HTTP_HOST');

        $client = Client::where('domain', $domain)->first();

        if ($client)
        {
            $settings = $client->settings;

            // set tenant db
            config([
                'client' => $settings,
                'database.connections.tenant.schema' => $client['schema'],
                'database.default' => 'tenant'
            ]);
        }

        return $next($request);
    }
}

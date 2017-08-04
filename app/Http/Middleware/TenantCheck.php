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
            '/logout'
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
            $client->logInAsClient();
        }

        if(function_exists('newrelic_add_custom_parameter'))
            newrelic_add_custom_parameter('client_id', $client->id);

        return $next($request);
    }
}

<?php

namespace MyPluginNamespace\App\Http\Middleware;

use MyPluginNamespace\WaxFramework\Routing\Contracts\Middleware;
use WP_REST_Request;

class EnsureIsUserAdmin implements Middleware
{
    /**
    * Handle an incoming request.
    *
    * @param  WP_REST_Request  $wp_rest_request
    * @return bool
    */
    public function handle( WP_REST_Request $wp_rest_request ): bool {
        return true;
        return current_user_can( 'manage_options' );
    }
}
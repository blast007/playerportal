<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class ACL {
    private $require_authentication;

    public function __construct($settings) {
        $this->require_authentication = $settings['require_authentication'] ?? false;
    }

    public function __invoke(Request $request, Response $response, callable $next) {
        $allow = true;
        $message = '';

        // Does this route require authentication?
        if ($this->require_authentication && !isset($_SESSION['user']['user_id'])) {
            $allow = false;
            $message = 'You must be logged in to access this page.';
        }


        // If the user is allowed to access this route, pass it through
        if ($allow === true) {
            return $next($request, $response);
        } else {
            // Otherwise, set the flash message and redirect to the login page
            $_SESSION['flash_message'] = $message;
            return $response->withRedirect('/login?return_to='.urlencode($request->getUri()));
        }
    }
}
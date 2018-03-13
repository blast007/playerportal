<?php declare(strict_types=1);

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->map(['get', 'post'], '/login', function(Request $request, Response $response, array $args) {
    // Check the login
    if ($request->isPost()) {
        $username = $request->getParsedBodyParam('username');
        $password = $request->getParsedBodyParam('password');

        if (!$username || !$password) {
            $this->view['error'] = 'Invalid username or password.';
        } else {
            // Search for a user
            $user = $this->db
                ->getModel('\App\Model\PlayerPortal\PublicSchema\UsersModel')
                ->findWhere('username ~* $*', compact('username'));

            if ($user->count() !== 1 || $this->password->verifyHash($password, $user->get(0)['password']) !== true) {
                $this->view['error'] = 'Invalid username or password.';
            } else {
                $u = $user->get(0);
                $_SESSION['user'] = [
                    'username' => $u['username'],
                    'user_id' => $u['id'],
                    'ip_address' => $_SERVER['REMOTE_ADDR']
                ];

                return $response->withRedirect('/');
            }
        }
    }

    return $this->view->render($response, 'login.twig');
})->setName('login');

$app->get('/logout', function (Request $request, Response $response, array $args) {
    unset($_SESSION['user']);
    return $response->withRedirect('/');
})->setName('logout');

$app->group('/organizations', function() {
    $this->get('', function (Request $request, Response $response, array $args) {
        return $this->view->render($response, 'Organizations/index.twig');
    })->setName('organizations');

})->add(new \App\Middleware\ACL([
    'require_authentication' => true
]));

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'index.twig');
})->setName('home');
<?php
declare(strict_types=1);

namespace App;

use App\HTTP\AddTask;
use App\HTTP\Admin\Auth;
use App\HTTP\Admin\Edit;
use App\HTTP\Admin\Logout;
use App\HTTP\ShowTask;
use App\HTTP\NotFound;
use App\Middleware\MustBeAuth;
use App\Middleware\Session;
use Cekta\Routing\Nikic\DispatcherBuilder;
use Cekta\Routing\Nikic\Handler;
use Cekta\Routing\Nikic\ProviderHandler;
use Cekta\Routing\Nikic\ProviderMiddleware;

class Matcher extends \Cekta\Routing\Nikic\Matcher
{
    public function __construct(
        ProviderHandler $providerHandler,
        ProviderMiddleware $providerMiddleware
    ) {
        $builder = new DispatcherBuilder();
        $builder->get('/', ShowTask::class, Session::class);
        $builder->get('/logout', Logout::class, Session::class, MustBeAuth::class);
        $builder->get('/auth', Auth::class, Session::class);
        $builder->get('/add', AddTask::class, Session::class);
        $builder->get('/edit/{id:\d+}', Edit::class, Session::class, MustBeAuth::class);
        $builder->post('/auth', Auth::class, Session::class);
        $builder->post('/add', AddTask::class, Session::class);
        $builder->post('/edit/{id:\d+}', Edit::class, Session::class, MustBeAuth::class);
        parent::__construct(
            new Handler(NotFound::class),
            $builder->build(),
            $providerHandler,
            $providerMiddleware
        );
    }

}

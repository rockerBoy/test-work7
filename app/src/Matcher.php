<?php
declare(strict_types=1);

namespace App;

use App\HTTP\AddTask;
use App\HTTP\AddTaskForm;
use App\HTTP\AdminAuth;
use App\HTTP\AdminAuthForm;
use App\HTTP\Logout;
use App\HTTP\ShowTask;
use App\HTTP\NotFound;
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
        $builder->get('/', ShowTask::class);
        $builder->get('/logout', Logout::class);
        $builder->get('/auth', AdminAuthForm::class);
        $builder->get('/add', AddTaskForm::class);
        $builder->post('/auth', AdminAuth::class);
        $builder->post('/add', AddTask::class);
        parent::__construct(
            new Handler(NotFound::class),
            $builder->build(),
            $providerHandler,
            $providerMiddleware
        );
    }

}

<?php
declare(strict_types=1);

namespace App\HTTP;

use Doctrine\DBAL\Connection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Zend\Diactoros\Response\RedirectResponse;

class AddTask implements RequestHandlerInterface
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig, Connection $connection)
    {
        $this->twig = $twig;
        $this->connection = $connection;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $this->connection->insert('tasks', [
            'username' => $body['username'],
            'email' => $body['email'],
            'description' => $body['description']
        ]);
        return new RedirectResponse('/');
    }
}

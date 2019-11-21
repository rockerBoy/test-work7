<?php
declare(strict_types=1);

namespace App\HTTP;

use Doctrine\DBAL\Connection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;

class ShowTask implements RequestHandlerInterface
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Environment $twig, Connection $connection)
    {
        $this->connection = $connection;
        $this->twig = $twig;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        session_start();

        return new HtmlResponse($this->twig->render('ShowTask.twig', [
            'tasks' => $this->connection->fetchAll('SELECT * FROM tasks '),
            'username' => $_SESSION['username'] ?? null
        ]));
    }
}

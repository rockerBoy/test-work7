<?php
declare(strict_types=1);

namespace App\HTTP;

use Doctrine\DBAL\Connection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Zend\Diactoros\Response\HtmlResponse;

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
        $body = $request->getQueryParams();
        session_start();
        $page = $body['page'] ?? 1;
        $inPage = 3;
        $count = $this->connection->fetchColumn('SELECT count(*) from tasks');
        $from = $inPage * ($page - 1);
        return new HtmlResponse($this->twig->render('ShowTask.twig', [
            'tasks' => $this->connection->fetchAll("SELECT * FROM tasks LIMIT $from, $inPage"),
            'pager' => [
                'current' => $page,
                'hasNext' => $count > $from + $inPage,
                'hasPrev' => $page > 1
            ],
            'username' => $_SESSION['username'] ?? null
        ]));
    }
}

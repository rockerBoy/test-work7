<?php
declare(strict_types=1);

namespace App\HTTP;

use Doctrine\DBAL\Connection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class AdminEdit implements RequestHandlerInterface
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
        $this->twig = $twig;
        $this->connection = $connection;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        session_start();
        if (empty($_SESSION['username'])) {
            return new RedirectResponse('/');
        }
        if ($request->getMethod() === 'POST') {
            $body = $request->getParsedBody();
            $record = [
                'username' => $body['username'],
                'email' => $body['email'],
                'description' => $body['description']
            ];
            if (!empty($body['finished_at'])) {
                $record['finished_at'] = date("Y-m-d H:i:s");
            } else {
                $record['finished_at'] = null;
            }
            $this->connection->update(
                'tasks',
                $record,
                ['id' => $request->getAttribute('id')]);
            return new RedirectResponse('/');
        }
        return new HtmlResponse($this->twig->render('adminEdit.twig', [
            'task' => $this->connection->fetchAssoc('SELECT * FROM tasks WHERE id = :id', [
                'id' => $request->getAttribute('id')
            ])
        ]));
    }
}

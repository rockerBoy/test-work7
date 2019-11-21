<?php
declare(strict_types=1);

namespace App\HTTP\Admin;

use Doctrine\DBAL\Connection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class Edit implements RequestHandlerInterface
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
        if ($request->getMethod() === 'POST') {
            $body = $request->getParsedBody();
            $record = [
                'username' => $body['username'],
                'email' => $body['email'],
                'description' => $body['description'],
                'status' => (int)!empty($body['status'])
            ];
            $task = $this->connection->fetchAssoc('SELECT * FROM tasks WHERE id = :id', [
                'id' => $request->getAttribute('id')
            ]);
            $edited = false;
            $checks = ['username', 'email', 'description'];
            foreach ($checks as $check) {
                if ($task[$check] !== $body[$check]) {
                    $edited = true;
                }
            }
            if ($edited) {
                $record['is_edit'] = true;
            }

            $this->connection->update(
                'tasks',
                $record,
                ['id' => $request->getAttribute('id')]);
            return new RedirectResponse('/');
        }
        return new HtmlResponse($this->twig->render('admin/edit.twig', [
            'task' => $this->connection->fetchAssoc('SELECT * FROM tasks WHERE id = :id', [
                'id' => $request->getAttribute('id')
            ])
        ]));
    }
}

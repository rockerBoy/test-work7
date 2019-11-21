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
    /**
     * @var int
     */
    private $page;
    /**
     * @var string
     */
    private $sort;
    /**
     * @var string
     */
    private $order;

    public function __construct(Environment $twig, Connection $connection)
    {
        $this->connection = $connection;
        $this->twig = $twig;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $query = $request->getQueryParams();
        $builder = $this->connection->createQueryBuilder()->from('tasks');
        $builder->select('*');
        $whishListSorts = ['username', 'email', 'status'];
        $this->sort = '';
        $this->order = 'asc';
        if (!empty($query['sort']) && in_array($query['sort'], $whishListSorts)) {
            if (!empty($query['order']) && ($query['order'] === 'asc' || $query['order'] === 'desc')) {
                $this->order = $query['order'];
            }
            $builder->orderBy($query['sort'], $this->order);
            $this->sort = $query['sort'];
        }
        $this->page = $query['page'] ?? 1;
        $inPage = 3;
        $count = $this->connection->fetchColumn('SELECT count(*) from tasks');
        $from = $inPage * ($this->page - 1);
        $builder->setFirstResult($from);
        $builder->setMaxResults($inPage);
        return new HtmlResponse($this->twig->render('showTask.twig', [
            'tasks' => $this->connection->fetchAll($builder->getSQL(), $builder->getParameters()),
            'pager' => [
                'current' => $this->page,
                'hasNext' => $count > $from + $inPage,
                'hasPrev' => $this->page > 1
            ],
            'sort' => $this->sort,
            'order' => $this->order,
            'sorts' => [
                'username' => $this->getUrlOrder('username'),
                'email' => $this->getUrlOrder('email'),
                'status' => $this->getUrlOrder('status')
            ],
            'username' => $request->getAttribute('username')
        ]));
    }

    private function getUrlOrder(string $target): string
    {
        $url = "?page={$this->page}&sort=$target";
        if ($this->sort === $target && $this->order === 'asc') {
            $url .= '&order=desc';
        }
        return $url;
    }
}

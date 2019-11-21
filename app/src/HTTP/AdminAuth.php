<?php
declare(strict_types=1);

namespace App\HTTP;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;

class AdminAuth implements RequestHandlerInterface
{
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        if (array_key_exists('username', $body)
            && array_key_exists('password', $body)
            && $body['username'] === 'admin'
            && $body['password'] === '123'
        ) {
            session_start();
            $_SESSION['username'] = 'admin';
            return new RedirectResponse('/');
        }
        return new HtmlResponse($this->twig->render('adminAuthForm.twig'));
    }
}

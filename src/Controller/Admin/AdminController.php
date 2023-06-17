<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_home',methods: ['GET'])]
class AdminController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}

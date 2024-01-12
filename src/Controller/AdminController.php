<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin')]
class AdminController extends AbstractController
{

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    #[Route('/flag', name: '.flag', methods: ['GET'])]
    public function index(): Response
    {

        $flag = $this->params->get('admin_flag', false);

        return $this->render('admin/index.html.twig', [
            'flag' => $flag
        ]);
    }
}

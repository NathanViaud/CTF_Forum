<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/post', name: 'app.posts')]
class PostController extends AbstractController
{
    public function __construct(
        private PostRepository $repo,
        private EntityManagerInterface $em
    ) {}

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $this->repo->findAllOrderedByNewest()
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response | RedirectResponse
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $post->setIp($request->getClientIp());
            $post->setCreatedAt(new \DateTimeImmutable());

            $this->em->persist($post);
            $this->em->flush();

            $this->addFlash('success', 'Post created successfully!');

            return $this->redirectToRoute('app.posts.index');
        }

        return $this->render('post/create.html.twig', [
            'form' => $form
        ]);

    }
}

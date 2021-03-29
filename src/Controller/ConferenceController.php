<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Conference;
use App\Form\CommentFromType;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ConferenceController extends AbstractController
{
    private $twig;
    private $commentRepository;
    private $conferenceRepository;

    public function __construct(Environment $twig, ConferenceRepository $conferenceRepository, CommentRepository $commentRepository)
    {
        $this->twig = $twig;
        $this->commentRepository = $commentRepository;
        $this->conferenceRepository = $conferenceRepository;
    }


    public function index(ConferenceRepository $conferenceRepository): Response
    {
        return new Response($this->twig->render('conference/index.html.twig', [
            'conferences' => $conferenceRepository->findAll(),
        ]));
    }

    public function show(Request $request, string $slug): Response
    {
        $conference = $this->conferenceRepository->findOneBy(["slug" => $slug]);
        if($conference === null) {
            return new Response("NO existe", 200);
        }
        else{
            
        $comment = new Comment();
        $form = $this->createForm(CommentFromType::class, $comment);

        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $this->commentRepository->getCommentPaginator($conference, $offset);

        return new Response($this->twig->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            'comment_form' => $form->createView(),
        ]));
        }
    }
}

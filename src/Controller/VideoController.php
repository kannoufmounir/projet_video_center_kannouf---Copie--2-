<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Form\SearchType;
use App\Model\SearchData;
use Knp\Component\Pager\Paginator;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/')]
class VideoController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(Request $request, VideoRepository $videoRepository, PaginatorInterface $paginator): Response
    {
        $videosQuery = $videoRepository->findPaginatedVideos();

        $paginator = $paginator->paginate(
            $videosQuery,
            $request->query->get('page', 1),
            9
        );

        if (!$this->getUser() || !$this->getUser()->isVerified()) {
            $videos = $paginator->getItems();
            $filteredVideos = array_filter($videos, function (Video $video) {
                return !$video->isPremiumVideo();
            });
            $paginator->setItems($filteredVideos);
        }


        if ($this->getUser()){
            if (!$this->getUser()->isVerified()){
                $this->addFlash('info rounded-0', 'Votre adresse e-mail nest pas vérifiée');
            }  
        }
        // ... rest of your code

        return $this->render('video/index.html.twig', [
            'paginator' => $paginator,
            'user' => $this->getUser(),
        ]);
    }









    #[Route('/video/create', name: 'app_video_create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        if ($this->getUser()) {
            if ($this->getUser()->isVerified() == false) {
                $this->addFlash('Danger  rounded-0 ', 'Vous devez confirmer votre email pour créer video!');
                return $this->redirectToRoute('app_home');
            }
        } else {
            $this->addFlash('info  rounded-0 ', 'Vous devez vous connecter pour créer une video!');
            return $this->redirectToRoute('/login');
        }
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video->setUser($this->getUser());
            $entityManager->persist($video);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('video/new.html.twig', [
            'video' => $video,
            'form' => $form,
        ]);
    }


















    #[Route('/video/{id}', name: 'app_video_show', methods: ['GET'])]
    public function show(Video $video): Response
    {

        return $this->render('video/show.html.twig', [
            'video' => $video,
        ]);
    }



    #[Route('/video/{id}/edit', name: 'app_video_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Video $video, EntityManagerInterface $entityManager): Response
    {

        if ($this->getUser()) {
            if ($this->getUser()->isVerified() == false) {
                $this->addFlash('info  rounded-0 ', 'Vous devez confirmer votre email pour modifier
            shop !');
                return $this->redirectToRoute('app_home');
            } else if ($video->getUser()->getEmail() !== $this->getUser()->getEmail()) {
                $this->addFlash('info  rounded-0 ', 'Vous devez être utilisateur ' . $video->getUser()->getFirstname() . ' éditer la video !');
                return $this->redirectToRoute('app_home');
            }
        } else {
            $this->addFlash('info  ', 'Vous devez vous connecter pour modifier la vidéo.');
            return $this->redirectToRoute('app_login'); // Redirect to the login route
        }



        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success  rounded-0 ', 'Vidéo mise à jour avec succès.'); // Adding a flash message

            return $this->redirectToRoute('app_home'); // Redirecting to the home page
        }

        return $this->render('video/edit.html.twig', [
            'video' => $video,
            'form' => $form->createView(), // Passing the form view
        ]);
    }

























    #[Route('/video/{id}/delete', name: 'app_video_delete', methods: ['POST'])]
    public function delete(Request $request, Video $video, EntityManagerInterface $entityManager): Response
    {

        if ($this->getUser()) {

            if ($this->getUser()->isVerified() == false) {
                $this->addFlash('danger rounded-0', 'Vous devez confirmer votre email pour supprimer la video !');
                return $this->redirectToRoute('app_home');
            } else if ($video->getUser()->getEmail() !== $this->getUser()->getEmail()) {
                $this->addFlash('danger rounded-0', 'Vous devez être utilisateur ' . $video->getUser()->getFirstname() . ' pour supprimer la video !');
                return $this->redirectToRoute('app_home');
            }
        } else {

            $this->addFlash('danger rounded-0', 'Vous devez vous connecter pour modifier la video!');
            return $this->redirectToRoute('/login');
        }


        if ($this->isCsrfTokenValid('delete' . $video->getId(), $request->request->get('_token'))) {
            $entityManager->remove($video);
            $entityManager->flush();

            $this->addFlash('success rounded-0 ', 'Vidéo supprimée avec succès.'); // Adding a flash message
        } else {
            $this->addFlash('danger rounded-0', 'Invalid CSRF token.'); // Adding an error flash message
        }

        return $this->redirectToRoute('app_home'); // Redirecting to the home page
    }
}

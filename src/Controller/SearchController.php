<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\SearchType;
use App\Model\SearchData;

class SearchController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, VideoRepository $VideoRepository, PaginatorInterface $paginator): Response
    {
        if ($this->getUser()) {
            if (!$this->getUser()->isVerified()) {
                $this->addFlash('info', 'Your email address is not verified.');
            }
        }

        //pagination
        $pagination = $paginator->paginate(
            $VideoRepository->paginationQuery(),
            $request->query->get('page', 1),
            9
        );
 
        $search = false;

        //barre de rechercher
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($searchData->query);
            $searchData->page = $request->query->getInt('page', 1);
            // $pins = $VideoRepository->findBySearch($searchData);
            $pagination = $paginator->paginate(
                $VideoRepository->findBySearch($searchData),
                $request->query->get('page', 1),
                6
            );
            $search = true;
            return $this->render('pin/index.html.twig', [
                'form' => $form,
                'pagination' => $pagination,
                'search' => $search,
                'searchData' => $searchData->query,
                'pins' => $VideoRepository->findBySearch2($searchData)
            ]);
        }

        return $this->render('pin/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
            'search' => $search
            // 'pins' => $VideoRepository->findAll()
        ]);
    }

 
}

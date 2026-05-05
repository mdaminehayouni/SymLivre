<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CategoryController extends AbstractController
{
    #[Route('/admin/categorie/create', name: 'admin_categorie_create')] 
    public function create(Request $request, EntityManagerInterface $em): Response 
    { 
        $cat = new Category(); 
        $form = $this->createForm(CategoryType::class, $cat); 
        $form->handleRequest($request); 
        if($form->isSubmitted()&&$form->isValid()) 
        { 
            $em->persist($cat); 
            $em->flush(); 
            $this->addFlash('success', 'une catégorie a été ajoutée avec succès');
            return $this->redirectToRoute('admin_categorie_list');
        }
        return $this->render('category/create.html.twig', ['form' => $form]); 
    } 
    #[Route('/category', name: 'admin_categorie_list')] 
    public function ListAll(CategoryRepository $rep,Request $request, EntityManagerInterface $em ,PaginatorInterface $paginator): Response 
    { 
        $category = $paginator->paginate( 
                 $rep->findAll(), 
                 $request->query->getInt('page', 1), 
                 ); 
        return $this->render('category/listAll.html.twig', [ 
            'category' =>$category, 
        ]); 
    } 
}

<?php

namespace App\Controller;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LivreController extends AbstractController
{
    #[Route('/livre', name: 'app_livre')]
    public function listAll(BookRepository $rep, PaginatorInterface $paginator, Request $request): Response{  
        $books = $paginator->paginate( 
                 $rep->findAll(), 
                 $request->query->getInt('page', 1), // Numéro de la                        
                          //page en cours, passé dans l'URL, 1 si aucune page 
                 10 // 3eme param 10,c’est le Nombre de résultats par page  10 
                 ); 
      return $this->render('livre/listAll.html.twig', [ 
            'book' =>$books, 
        ]); 
    }
    #[Route('/index',name:'app_index')]
    public function home(){
        return $this->render('livre/index.html.twig');
    }
    #[Route('/find/{id}',name:'app_find')]
    public function rechercher($id,BookRepository $rep):Response{
        $book=$rep->find($id);
        return $this->render('livre/rechercher.html.twig',['book'=>$book]);
    }
    #[Route('/admin/livre/add', name: 'admin_livres_add')] 
    public function ajouter(EntityManagerInterface $em): Response 
    { 
        $livre = new Book(); 
        $livre->setTitre('Titre du livre 4'); 
        $livre->setImage('https://via.placeholder.com/150'); 
        $livre->setResume('Résume du livre Titre4'); 
        $livre->setPrix(20); 
        $livre->setEditeur('Editeur4'); 
        $dateEdition = new \DateTime('2023-01-01'); 
        $livre->setDate($dateEdition); 
        $em->persist($livre); 
        $em->flush(); 
        return new Response("Le livre est enregistré avec succès"); 
    }
    #[Route('/admin/livres/delete/{id}', name: 'admin_livres_delete')] 
    public function supprimer(Book $livre, EntityManagerInterface $em):Response 
    { 
        $em->remove($livre); 
        $em->flush(); 
        return $this->redirectToRoute('admin_livres'); 
    }
    #[Route('/admin/livres/edit/{id}', name: 'admin_livres_edit')] 
    public function editer(Book $book, EntityManagerInterface $em,Request $request):Response 
    { 
        $form = $this->createForm(BookType::class, $book); 
        $form->handleRequest($request); 
        if($form->isSubmitted()&&$form->isValid()) 
        { 
            $em->persist($book); 
            $em->flush(); 
            $this->addFlash('success', 'un livre a été ajoutée avec succès');
            return $this->redirectToRoute('app_livre');
        }
        return $this->render('livre/create.html.twig', ['form' => $form]);    
    }
    #[Route('/admin/livres/create', name: 'admin_livres_create')] 
    public function create(Request $request, EntityManagerInterface $em): Response 
    { 
        $book = new Book(); 
        $form = $this->createForm(BookType::class, $book); 
        $form->handleRequest($request); 
        if($form->isSubmitted()&&$form->isValid()) 
        { 
            $em->persist($book); 
            $em->flush(); 
            $this->addFlash('success', 'un livre a été ajoutée avec succès');
            return $this->redirectToRoute('app_livre');
        }
        return $this->render('livre/create.html.twig', ['form' => $form]); 
    }

}

<?php

namespace App\Controller;

namespace App\Controller;
use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name: 'app_entreprise')]
    public function index(): Response
    {
        return $this->render('entreprise/index.html.twig', [
            'controller_name' => 'EntrepriseController',
        ]);
    }
    #[Route('/entreprise/add', name: 'app_add_entreprise')]
    public function Add(Request $request,EntityManagerInterface $em)
    {
        $Entreprise=new Entreprise();
        
       $form =$this->CreateForm(EntrepriseType::class,$Entreprise);
       $form->add('Ajouter',SubmitType::class);
       $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid())
    {
        $em->persist($Entreprise);
        $em->flush();
        return $this->redirectToRoute('app_affiche_entreprise');
    }
    return $this->render('Entreprise/add.html.twig',['f'=>$form->createView()]);
    }
    #[Route('/entreprise/affiche', name: 'app_affiche_entreprise')]
    public function Afficher(EntrepriseRepository $repository)
    {
      $Entreprise=$repository->findAll(); //select
      return $this->render('entreprise/affiche.html.twig',['Entreprise'=>$Entreprise]) ;
    }
    #[Route('/entreprise/edit/{id}', name: 'app_edit_entreprise')]
     public function update(EntityManagerInterface $em, Request $request, EntrepriseRepository $rep, $id): Response
    {
    $entreprise = $rep->find($id);

    $form = $this->createForm(EntrepriseType::class, $entreprise);
    $form->add('update', SubmitType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush(); 
        return $this->redirectToRoute('app_affiche_entreprise');
    }

    return $this->render('entreprise/edit.html.twig', [
        'f' => $form->createView(),
    ]);
     }
    #[Route('/entreprise/delete/{name}', name: 'app_delete_entreprise')]
    public function delete($name, EntityManagerInterface $em, EntrepriseRepository $er)
    {
    $entreprise = $er->findOneBy(['name' => $name]);
    if (!$entreprise) {
        throw $this->createNotFoundException('Entreprise not found');
    } else {
        $em->remove($entreprise);
        $em->flush();
        return $this->redirectToRoute('app_affiche_entreprise');
    }
    }
    


}

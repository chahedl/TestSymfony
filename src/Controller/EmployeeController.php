<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeeController extends AbstractController
{
    #[Route('/employee', name: 'app_employee')]
    public function index(): Response
    {
        return $this->render('employee/index.html.twig', [
            'controller_name' => 'EmployeeController',
        ]);
    }
    #[Route('/employee/affiche', name: 'app_affiche_employe')]
    public function Afficher(EmployeRepository $repository)
    {
      $Employe=$repository->findAll(); //select
      return $this->render('Employe/Affiche.html.twig',['Employe'=>$Employe]) ;
    }
    #[Route('/employee/add', name: 'app_add_employe')]
    public function Add(Request $request,EntityManagerInterface $em)
    {
        $Employe=new Employe();
       $form = $this->createForm(EmployeType::class, $Employe);
       $form->add('Ajouter',SubmitType::class);
       $form->handleRequest($request);
       $Entreprise= $Employe->getEntreprise();
    if ($form->isSubmitted() && $form->isValid())
    {
        $em->persist($Employe);
        $em->flush();
        return $this->redirectToRoute('app_affiche_employe');
    }
    return $this->render('Employe/Add.html.twig',['f'=>$form->createView()]);
    }
    #[Route('/employee/update/{id}', name: 'app_update_employe')]
     public function UpdateEmploye(EntityManagerInterface $em, Request $request, EmployeRepository $rep, $id): Response
     {
        $Employe = $rep->find($id);
        $form=$this->createForm(EmployeType::class,$Employe);
        $form->add('update',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em->persist($Employe);
            $em->flush();
            return $this-> redirectToRoute('app_affiche_employe');
        }
        return $this->render('Employe/Edit.html.twig',[
            'f'=>$form->createView(),
        ]);
     }
     #[Route('employee/delete/{id}', name: 'app_delete_employe')]
    public function delete($id, EmployeRepository $repository,EntityManagerInterface $em)
    {
        $employee = $repository->find($id);

    if (!$employee) {
        throw $this->createNotFoundException('Employee not found');
    }
    else {
    $em->remove($employee);
    $em->flush();
    return $this->redirectToRoute('app_affiche_employe');}

    }
   
}

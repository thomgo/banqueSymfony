<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\AccountRepository;
use App\Entity\Account;
use App\Entity\Operation;
use App\Form\AccountType;
use App\Form\OperationType;

/**
*@IsGranted("IS_AUTHENTICATED_FULLY")
*/
class CustomerController extends AbstractController
{
    /**
     * @Route("/accueil", name="home")
     * @Route("/", name="root")
     */
    public function index(): Response
    {
        return $this->render('customer/index.html.twig', [

        ]);
    }

    /**
     * @Route("/account/{id}", name="single", requirements={"id"="\d+"})
     */
    public function single(int $id, AccountRepository $accountRepository): Response
    {
        $account = $accountRepository->getAccount($id, $this->getUser());
        return $this->render('customer/single.html.twig', [
          "account" => $account
        ]);
    }

    /**
     * @Route("/account/delete/{id}", name="account_delete", requirements={"id"="\d+"})
     */
    public function accountDelete(int $id, AccountRepository $accountRepository): Response
    {
        try {
          $account = $accountRepository->findOneBy([
            "id" => $id,
            "user" => $this->getUser()
          ]);
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->remove($account);
          $entityManager->flush();
          $this->addFlash(
            'success',
            'Votre compte a bien été supprimé'
          );
        } catch (\Exception $e) {
          $this->addFlash(
            'danger',
            "Une erreur est survenue, nous n'avons pas pu supprimer votre compte"
          );
        }
        return $this->redirectToRoute('root');
    }

    /**
     * @Route("/account/new", name="account_new")
     */
    public function accountNew(Request $request): Response
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
          $account->setOpeningDate(new \DateTime());
          $account->setUser($this->getUser());
          try {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($account);
            $entityManager->flush();
            $this->addFlash(
              'success',
              'Votre compte a bien été ouvert'
            );
            return $this->redirectToRoute('root');
          } catch (\Exception $e) {
            $this->addFlash(
              'danger',
              "Une erreur est survenue, votre compte n'a pas été enregistré"
            );
          }

        }
        return $this->render('customer/account_new.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/account/operation/{id}", name="account_operation", requirements={"id"="\d+"})
     */
    public function accountOperation(int $id, AccountRepository $accountRepository, Request $request): Response
    {
          $account = $accountRepository->findOneBy([
            "id" => $id,
            "user" => $this->getUser()
          ]);
          $operation = new Operation();
          $form = $this->createForm(OperationType::class, $operation);
          $form->handleRequest($request);
          if($form->isSubmitted() && $form->isValid()) {
            $operation->setRegisteringDate(new \DateTime("now"));
            $operation->setAccount($account);
            try{
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($operation);
              $entityManager->flush();
              $this->addFlash(
                'success',
                'Votre opération a bien été exécutée'
              );
            }catch (\Exception $e) {
              $this->addFlash(
                'danger',
                "Une erreur est survenue, nous n'avons pas pu réaliser l'opération"
              );
            }
          }
        return $this->render("customer/operation.html.twig", [
          "account" => $account,
          "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function blog(): Response
    {
        return $this->render('customer/blog.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }

    /**
     * @Route("/statistiques", name="stats")
     */
    public function stats(): Response
    {
        return $this->render('customer/stats.html.twig', []);
    }
}

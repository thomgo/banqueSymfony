<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\AccountRepository;
use App\Entity\Account;

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
    public function account_delete(int $id, AccountRepository $accountRepository): Response
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

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
use App\Form\TransferType;

// Make sure the user is logged before accessing any route
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
        // No request to the database here, we use the logged user in template
        return $this->render('customer/index.html.twig');
    }

    /**
     * Route that expect as a parameter the id of the entity to show as an int
     * @Route("/account/{id}", name="single", requirements={"id"="\d+"})
     */
    public function single(int $id, AccountRepository $accountRepository): Response
    {
        // Use a custom method inside the repo to get the user account with the operations
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
        // Use try/catch bloc to make sure the delete happens without troubles
        try {
          // Find the accout matching the route id and the user id
          $account = $accountRepository->findOneBy([
            "id" => $id,
            "user" => $this->getUser()
          ]);
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->remove($account);
          $entityManager->flush();
          // Store a message for the user in session
          $this->addFlash(
            'success',
            'Votre compte a bien été supprimé'
          );
        } catch (\Exception $e) {
          // If something went wrong store an error message
          $this->addFlash(
            'danger',
            "Une erreur est survenue, nous n'avons pas pu supprimer votre compte"
          );
        }
        // No matter what happened we redirect to the home page
        return $this->redirectToRoute('root');
    }

    /**
     * @Route("/account/new", name="account_new")
     */
    public function accountNew(Request $request): Response
    {
        // Instanciate a new account to be populated by the form
        $account = new Account();
        // Create a new form object linked to the account
        $form = $this->createForm(AccountType::class, $account);
        // Get the submitted data and try to hdyrate the object
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
          // Store the current date in the object because it is created now
          $account->setOpeningDate(new \DateTime());
          // Store the owner of the account
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
        // If no form is submitted we display the view with the form
        return $this->render('customer/account_new.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * Make the debit and credit operation on a single account
     * @Route("/account/operation/{id}", name="account_operation", requirements={"id"="\d+"})
     */
    public function accountOperation(int $id, AccountRepository $accountRepository, Request $request): Response
    {
          // Get the account object from the route id and the logged user
          $account = $accountRepository->findOneBy([
            "id" => $id,
            "user" => $this->getUser()
          ]);
          $operation = new Operation();
          $form = $this->createForm(OperationType::class, $operation);
          $form->handleRequest($request);
          if($form->isSubmitted() && $form->isValid()) {
            $operation->setRegisteringDate(new \DateTime("now"));
            // Store the account to which the operation is related to
            // This method also update the account amount in the same time
            $operation->setAccount($account);
            try{
              $entityManager = $this->getDoctrine()->getManager();
              // By persisting the operation we also persist the account stored in it
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
     * @Route("/transfer", name="transfer")
     */
    public function transfer(AccountRepository $accountRepository, Request $request): Response
    {
          // Create a form that is not based on any entity (could be however since a transfer is a double operation)
          $form = $this->createForm(TransferType::class);
          $form->handleRequest($request);
          if($form->isSubmitted() && $form->isValid()) {
            // Create a new operation, for now only for debit
            $debitOperation = new Operation();
            $debitOperation->setRegisteringDate(new \DateTime("now"));
            // Since the form is not binded to any entity we need to get the data from the form manually
            // getData return an associative array, the indexex are defiened in the TransferType class
            $debitOperation->setLabel($form->getData()["label"]);
            $debitOperation->setAmount($form->getData()["amount"]);
            // To avoid repeting code now we can clone the debit operation into the credit one with same data
            $creditOperation = clone $debitOperation;

            $debitOperation->setType("débit");
            $creditOperation->setType("crédit");
            $debitAccount = $form->getData()["debitAccount"];
            $creditAccount = $form->getData()["creditAccount"];
            $debitOperation->setAccount($debitAccount);
            $creditOperation->setAccount($creditAccount);

            try{
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($debitOperation);
              $entityManager->persist($creditOperation);
              $entityManager->flush();
              $this->addFlash(
                'success',
                'Votre virement a bien été exécutée',
              );
              return $this->redirectToRoute('root');
            }catch (\Exception $e) {
              $this->addFlash(
                'danger',
                "Une erreur est survenue, nous n'avons pas pu réaliser le virement"
              );
            }
          }
        return $this->render("customer/transfer.html.twig", [
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

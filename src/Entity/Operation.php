<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OperationRepository::class)
 */
class Operation
{
    // Same array as in account to store allowed values
    const TYPES = ["débit", "crédit"];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Choice(choices=Operation::TYPES, message="Type d'opération invalide")
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $registeringDate;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $label;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="operations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRegisteringDate(): ?\DateTimeInterface
    {
        return $this->registeringDate;
    }

    public function setRegisteringDate(\DateTimeInterface $registeringDate): self
    {
        $this->registeringDate = $registeringDate;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        // If the account does not already have the operation we need to update the amount
        if(!$account->getOperations()->contains($this)) {
          // Calculate the new account amount according to the type of the operation
          if($this->type === "crédit") {
            $amount = $account->getAmount() + $this->amount;
          }
          else {
            $amount = $account->getAmount() - $this->amount;
            $this->amount = "-" . $this->amount;
          }
          // Set the new account amount
          $account->setAmount($amount);
        }
        // Store the account object in the operation object
        $this->account = $account;
        return $this;
    }
}

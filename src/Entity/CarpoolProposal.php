<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarpoolProposalRepository")
 */
class CarpoolProposal extends AbstractCarpool
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CarpoolAnswer", mappedBy="proposal", orphanRemoval=true)
     */
    protected $answers;
}

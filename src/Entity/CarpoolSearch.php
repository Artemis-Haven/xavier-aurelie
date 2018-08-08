<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarpoolSearchRepository")
 */
class CarpoolSearch extends AbstractCarpool
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CarpoolAnswer", mappedBy="search", orphanRemoval=true)
     */
    protected $answers;
}

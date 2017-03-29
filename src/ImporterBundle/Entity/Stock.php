<?php

namespace ImporterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class Stock
{
    /** @var integer */
    private $quantity = '0';

    /** @var integer */
    private $idProduct;


    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Stock
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Get idProduct
     *
     * @return integer
     */
    public function getIdProduct()
    {
        return $this->idProduct;
    }
}


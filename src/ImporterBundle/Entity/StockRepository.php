<?php

namespace ImporterBundle\Entity;

use Doctrine\ORM\EntityRepository;

class StockRepository extends EntityRepository
{
    /**
     * Updates or creates the product in the Database
     *
     * @param Stock $stock
     *
     * @return bool
     */
    public function createOrUpdateStock(Stock $stock){
        try{
            $productExists = $this->find($stock->getIdProduct());
            if (!is_null($productExists) && $productExists instanceof Stock) {
                $this->getEntityManager()->merge($stock);
            } else {
                $this->getEntityManager()->persist($stock);
            }
        } catch (\Exception $e){
            return false;
        }

        return true;
    }

}
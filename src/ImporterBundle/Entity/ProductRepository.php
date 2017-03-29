<?php

namespace ImporterBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    /**
     * Updates or creates the product in the Database
     *
     * @param Product $product
     *
     * @return bool
     */
    public function createOrUpdateProduct(Product $product){
        try{
            $productExists = $this->find($product->getIdProduct());
            if (!is_null($productExists) && $productExists instanceof Product) {
                $this->getEntityManager()->merge($product);
            } else {
                $this->getEntityManager()->persist($product);
            }
        } catch (\Exception $e){
            return false;
        }

        return true;
    }

}
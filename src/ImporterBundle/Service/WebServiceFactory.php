<?php
/**
 * Created by PhpStorm.
 * User: kristian
 * Date: 20/11/16
 * Time: 14:32
 */

namespace ImporterBundle\Service;


use ImporterBundle\Controller\CustomPrestashopWS;
use PrestaShopWebservice;

class WebServiceFactory
{
    /** @var null|CustomPrestashopWS */
    private $instance   = null;

    /** @var string */
    private $website;
    /** @var string */
    private $key;
    /** @var string */
    private $debug;

    public function __construct($website, $key, $debug = false){
        $this->website = $website;
        $this->key = $key;
        $this->debug = $debug;
    }

    public function getInstance(){
        if(is_null($this->instance)){
            $this->instance = new CustomPrestashopWS($this->website, $this->key, $this->debug);
        }
        return $this->instance;
    }

}
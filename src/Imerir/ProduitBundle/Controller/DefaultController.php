<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function ajoutAttributAction()
    {
        ini_set("soap.wsdl_cache_enabled", 0);
        //todo mettre l'url dans un twig de ressources
        $client = new \SoapClient('http://localhost/alba/web/app_dev.php/soap');
        $result = $client->__soapCall('login', array('username'=>'alba','passwd'=>'alba'));
        echo $result;
        return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig');
    }
}

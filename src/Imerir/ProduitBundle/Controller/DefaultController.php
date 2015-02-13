<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function ajoutAttributAction()
    {
        return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig');
    }
}

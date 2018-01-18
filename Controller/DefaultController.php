<?php

namespace Donfelice\PolygonFieldTypeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DonfelicePolygonFieldTypeBundle:Default:index.html.twig');
    }
}

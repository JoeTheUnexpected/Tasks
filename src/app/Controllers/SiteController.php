<?php


namespace App\Controllers;


use App\Core\Controller;

class SiteController extends Controller
{
    public function index()
    {
        $this->render('index');
    }
}
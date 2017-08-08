<?php
namespace BrainStrategist\KernelBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;

class ApiController extends FOSRestController
{
    public function postUserScanAction(Request $request)
    {
        $code= $request->request->get('code');
        $user = $request->request->get('user');
        $password = $request->request->get('password');

        if((empty($code) || empty($user) || empty($password)) && ($user!="@adminforum" ||  $password!="W9G2vLge?6&X:" ) ){
            $view = $this->view("Access denied", 403);
            return $this->handleView($view);
        }

        switch($code){
            case '10':
                $response = "valid";
                break;
            case '20':
                $response = "already_scanned";
                break;
            default:
                $response = "unregistered";
                break;
        }
        $view = $this->view($response, 200);
        return $this->handleView($view);

    }

}
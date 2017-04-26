<?php

namespace Controller;

use Model\UserManager;

class SecurityController extends BaseController
{
    public function loginAction()
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $manager = UserManager::getInstance();
            if ($manager->userCheckLogin($_POST))
            {
                $manager->userLogin($_POST['username']);
                $this->redirect('home');
            }
            else {
                $error = "Invalid username or password";
            }
        }
        echo $this->renderView('login.html.twig', ['error' => $error]);
    }
    
    public function logoutAction()
    {
        session_destroy();
        echo $this->redirect('login');
    }
    
    public function registerAction()
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $manager = UserManager::getInstance();
            if ($manager->userCheckRegister($_POST))
            {
                $manager->userRegister($_POST);
                $this->redirect('home');
            }
            else {
                $error = "Invalid data";
            }
        }
        echo $this->renderView('register.html.twig', ['error' => $error]);
    }
    
    public function editProfileAction()
    {
        $error = '';
        if(!empty($_SESSION['user_id'])){
            echo $this->renderView('profile_edit.html.twig', ['name' => $_SESSION['username']]);
        }else{
            $this->redirect('home');
        }
    }
    
    public function changePasswordAction()
    {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $manager = UserManager::getInstance();
            if ($manager->userCheckChangePassword($_POST))
            {
                $manager->userChangePassword($_POST);
                //$this->redirect('editProfile');
            }
            else {
                $error = "Invalid Format";
            }
        }
        
        echo $this->renderView('profile_edit.html.twig', ['error' => $error,'name' => $_SESSION['username']]);
    }
    
    public function showProfileAction()
    {
        
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $manager = UserManager::getInstance();
            if ($manager->userProfileCheck($_POST['author-name']))
            {
                $user=$manager->userGetProfile($_POST['author-name']);
                $profileToShow=[$user['username'],$user['email']];
                if(!empty($_SESSION['username'])){
                    echo $this->renderView('profile.html.twig', ['profileToShow' => $profileToShow,'name' => $_SESSION['username']]);
                }else{
                    echo $this->renderView('profile.html.twig', ['profileToShow' => $profileToShow]);
                }
                //$this->redirect('home');
            }
            else {
                $error = "That profile doesn't exist";
                echo $this->renderView('profile.html.twig', ['error' => $error]);
            }
        }else{
            $error = "Not POST";
        }
    }
}
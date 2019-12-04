<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     *
     */

    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $form = $this->createFormBuilder()
            ->add('email')
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label'=> 'Password'],
                'second_options' => [ 'label' => 'Confirm Password']

            ])
            ->add('register', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success float-right'
                ]
            ])
        ->getForm();


        $form->handleRequest($request);

        if($form->isSubmitted()){
            $data = $form->getData();
            $user = new User();
            $user->setEmail($data['email']);
            $encoded = $encoder->encodePassword($user, $data['password']);
            $user->setPassword($encoded);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        return $this->render('registration/index.html.twig', [
            'form'=> $form->createView()
        ]);
    }


}

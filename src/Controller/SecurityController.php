<?php

namespace App\Controller;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function registers(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());
        if(isset($values->email,$values->password)) {
            $user = new User();
            $user->setEmail($values->email);
            $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
            $user->setRoles($user->getRoles());
            
            $errors = $validator->validate($user);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            
            $em->persist($user);
            $em->flush();

            $data = [
                'status' => 201,
                'message' => 'The user is registered'
            ];

            return new JsonResponse($data, 201);
        }
        $data = [
            'status' => 500,
            'message' => 'You must insert an email and a password.'
        ];
        return new JsonResponse($data, 500);
    }
    
    
    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {
        $user = $this->getUser();
        return $this->json([
            'username' => $user->getUsername(),
            'roles' => $user->getRoles()
        ]);
    }
}
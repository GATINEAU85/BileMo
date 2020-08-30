<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Model;
use App\Entity\Customer;
use App\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception;

use Nelmio\ApiDocBundle\Annotation\Model as Mod;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

/**
 * @Route("/api")
 */
class UserController extends AbstractController
{           
    /**
     * @Route("/users", name="getUsers", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of all users",
     *     @SWG\Schema(ref=@Mod(type=User::class))
     * )
     * @SWG\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function getUsers(Request $request, UserRepository $userRespository, SerializerInterface $serializer)
    {
        $page = $request->query->get('page');
        
        if(is_null($page) || $page < 1){
            $page = 1 ;
        }
        $limit = 10;
        //$users = $userRespository->findAllUsers($page, getEnv('LIMIT_PAGINATION'));
        $users = $userRespository->findAllUsers($page, $limit);
        $data = $serializer->serialize($users, 'json');
        
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }  
    
    /**
     * @Route("/customers/{id}/users", name="addUser", methods={"POST"})
     * @SWG\Response(
     *     response=201,
     *     description="Returns the confirmation of the user add.",
     *     @SWG\Schema(ref=@Mod(type=User::class))
     * )
     * @SWG\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function addUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        
        $customerId = $request->attributes->get('id');
        $customer = $em->getRepository(Customer::class)->find($customerId);
        $user->setCustomer($customer);
        $user->setPassword(password_hash($user->getPassword(), PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_USER']);
        
        //TODO - Renvoyer une bonne erreur 
        $errors = $validator->validate($user);
        
        if (count($errors)){
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        
        $em->persist($user);
        $em->flush();
        
        $data = [
            'status'  => 201,
            'message' => 'This users add is a success.',
        ];
        return new JsonResponse($data, 201);
    }
    
    
    /**
     * @Route("/customers/{customerId}/users/{id}", name="updateUser", methods={"PUT"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the confirmation of user update.",
     *     @SWG\Schema(ref=@Mod(type=User::class))
     * )
     * @SWG\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function updateUser(Request $request, SerializerInterface $serializer, User $user, ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $userUpdate = $em->getRepository(User::class)->find($user->getId());
        $customerId = $request->attributes->get('customerId');

        $data = json_decode($request->getContent());
        foreach ($data as $key => $value){
            if($key && !empty($value)) {
                if($key !== "id"){
                    $name = ucfirst($key);
                    $setter = 'set'.$name;
                    if($key == "customer"){
                        $customer = $em->getRepository(Customer::class)->find($customerId);
                        $userUpdate->$setter($customer);
                    }else{
                        $userUpdate->$setter($value);
                    }
                }
            }
        }
        $userUpdate->setPassword(password_hash($userUpdate->getPassword(), PASSWORD_BCRYPT));
        $userUpdate->setRoles(['ROLE_USER']);
        
        $errors = $validator->validate($userUpdate);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $em->flush();
        $data = [
            'status' => 201,
            'message' => 'The user is updated.'
        ];
        return new JsonResponse($data);
    }
    
    /**
     * @Route("/customers/{customerId}/users/{id}", name="deleteUser", methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the confirmation of delete.",
     *     @SWG\Schema(ref=@Mod(type=User::class))
     * )
     * @SWG\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function deleteUser(User $user, EntityManagerInterface $em)
    {
        $em->remove($user);
        $em->flush();
        
        $data =  [
            'status' => 204,
            'message' => "The delete of this user is success.",
        ];
        return new JsonResponse($data);
    }
    
    
    /**
     * @Route("/customers/{id}/users", name="getUsersByCustomer", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns all users of a customers",
     *     @SWG\Schema(ref=@Mod(type=User::class))
     * )
     * @SWG\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function getUsersByCustomer(Customer $customer, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $users = $em->getRepository(User::class)->findBy(['customer' => $customer]);
        $data = $serializer->serialize($users, 'json');
        
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }          
        
    /**
     * @Route("/customers/{customerId}/users/{userId}", name="getUserById", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns user of a customer.",
     *     @SWG\Schema(ref=@Mod(type=User::class))
     * )
     * @SWG\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function getUserByCustomer(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $userId = $request->attributes->get('userId');
        $customerId = $request->attributes->get('customerId');
        
        $customer = $em->getRepository(Customer::class)->find($customerId);
        $user = $em->getRepository(User::class)->find($userId);

        if($user->getCustomer() != $customer){
            throw new \Exception("The customer hasn't a user identify by this id");
        }
        $data = $serializer->serialize($user, 'json');
                
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    
    
}

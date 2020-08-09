<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Model;
use App\Entity\Brand;
use App\Repository\ProductRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

//use Nelmio\ApiDocBundle\Annotation\Model;
//use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
//use FOS\RestBundle\Controller\Annotations\Get;
//use FOS\RestBundle\Controller\Annotations\View;
//use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api")
 */
class ProductController extends AbstractController
{
//    /**
//     * @Route("/", name="getProductsList", methods={"GET"})
//     */
//    public function index(SerializerInterface $serializer)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $product = $em->getRepository(Product::class)->findAll();
//
//        //If I doesn't use SerializerInterface I must configured the serializer thanks to an encoder and a normalizer
//        // $encoders = [new JsonEncoder()];
//        // $normalizers = [new ObjectNormalizer()];
//        // $serializer = new Serializer($normalizers, $encoders);
//
//        $data = $serializer->serialize($product, 'json');
//        
//        return new Response($data, 200, [
//            'Content-Type' => 'application/json'
//        ]);
//    }
    
        
    /**
     * @Route("/products/{id}", name="getProduct", methods={"GET"})
     */
    public function getProduct(Product $product, ProductRepository $productRepository, SerializerInterface $serializer)
    {
        //$em = $this->getDoctrine()->getManager();
        $entity = $productRepository->find($product->getId());
        $data = $serializer->serialize($entity, 'json');
                
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    
    
    /**
     * @Route("/products/{page<\d+>?1}", name="getProducts", methods={"GET"})
     */
    public function getProducts(Request $request, ProductRepository $productRespository, SerializerInterface $serializer)
    {
        $page = $request->query->get('page');
        
        if(is_null($page) || $page < 1){
            $page = 1 ;
        }
        $limit = 10;
        //$products = $productRespository->findAllProducts($page, getEnv('LIMIT_PAGINATION'));
        $products = $productRespository->findAllProducts($page, $limit);
        $data = $serializer->serialize($products, 'json');
        
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }  
    
    /**
     * @Route("/products", name="addProduct", methods={"POST"})
     */
    public function addProduct(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $product = $serializer->deserialize($request->getContent(), Product::class, 'json');
        
        //TODO - Renvoyer une bonne erreur 
        $errors = $validator->validate($product);
        
        if (count($errors)){
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        
        $em->persist($product);
        $em->flush();
        
        $data = [
            'status' => 201,
            'message' => 'This products add is a success.'
        ];
        return new JsonResponse($data, 201);
    }
    
    
    /**
     * @Route("/products/{id}", name="updateProduct", methods={"PUT"})
     */
    public function updateProduct(Request $request, SerializerInterface $serializer, Product $product, ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $productUpdate = $em->getRepository(Product::class)->find($product->getId());
        $data = json_decode($request->getContent());
        foreach ($data as $key => $value){
            if($key && !empty($value)) {
                if($key !== "id"){
                    $name = ucfirst($key);
                    $setter = 'set'.$name;
                    if($key == "model"){
                        $model = $em->getRepository(Model::class)->find($product->getModel());
                        $productUpdate->$setter($model);
                    }else{
                        $productUpdate->$setter($value);
                    }
                }
            }
        }
        $errors = $validator->validate($productUpdate);
        if(count($errors)) {
            $errors = $serializer->serialize($errors, 'json');
            return new Response($errors, 500, [
                'Content-Type' => 'application/json'
            ]);
        }
        $em->flush();
        $data = [
            'status' => 200,
            'message' => 'The product is updated.'
        ];
        return new JsonResponse($data);
    }
    
    /**
     * @Route("/products/{id}", name="deleteProduct", methods={"DELETE"})
     */
    public function deleteProduct(Product $product, EntityManagerInterface $em)
    {
        $em->remove($product);
        $em->flush();
        
        $data =  [
            'status' => $exception->getCode(),
            'message' => $exception->getMessage(),
        ];
        return new JsonResponse($data);
    }
}

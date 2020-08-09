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

use Nelmio\ApiDocBundle\Annotation\Model as Mod;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/products/{id}", name="getProduct", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the products of an user",
     *     @SWG\Schema(ref=@Mod(type=Product::class))
     * )
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="string",
     *     description="The field used to identify product"
     * )
     * @SWG\Tag(name="products")
     * @Security(name="Bearer")
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
     * @SWG\Response(
     *     response=200,
     *     description="Returns the products list with a pagination by 10 entities.",
     *     @SWG\Schema(ref=@Mod(type=Product::class))
     * )
     * @SWG\Tag(name="products")
     * @Security(name="Bearer")
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
     * @Route("/admin/products", name="addProduct", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the products added.",
     *     @SWG\Schema(ref=@Mod(type=Product::class))
     * )
     * @SWG\Tag(name="products")
     * @Security(name="Bearer")
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
     * @Route("/admin/products/{id}", name="updateProduct", methods={"PUT"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the product updated.",
     *     @SWG\Schema(ref=@Mod(type=Product::class))
     * )
     * @SWG\Tag(name="products")
     * @Security(name="Bearer")
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
     * @Route("/admin/products/{id}", name="deleteProduct", methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the product deleted.",
     *     @SWG\Schema(ref=@Mod(type=Product::class))
     * )
     * @SWG\Tag(name="products")
     * @Security(name="Bearer")
     */
    public function deleteProduct(Product $product, EntityManagerInterface $em)
    {
        $em->remove($product);
        $em->flush();
        
        $data =  [
            'status' => 200,
            'message' => "The product is deleted.",
        ];
        return new JsonResponse($data);
    }
}

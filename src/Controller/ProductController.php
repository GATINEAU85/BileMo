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
     * @Route("/products", name="getProducts", methods={"GET"})
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
}

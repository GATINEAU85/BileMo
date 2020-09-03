<?php

namespace App\Controller;

use App\Links\LinksProductsGenerator;
use App\Paging\ProductPaging;
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
    private $links;
    private $paging;

    public function __construct(LinksProductsGenerator $links, ProductPaging $paging) {
            $this->links = $links;
            $this->paging = $paging;
    }
    
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
        
        $this->links->addLinks($entity);

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
    public function getProducts(Request $request, SerializerInterface $serializer)
    {
        $page = $request->query->get('page');
        
        $products = $this->paging->getDatas($page);
        $this->links->addLinks($products);

        $data = $serializer->serialize($products, 'json');
        
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }  
}

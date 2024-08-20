<?php


namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopContollerController extends AbstractController
{
    #[Route('/shop-list', name: 'app_shop_controller', defaults: ['pk' => null,'min_price'=>null,'max_price'=> null,'brands'=> null],requirements: ['pk' => '\d*'])]
    public function index(CategoryRepository $categoryRepository,ProductRepository $productRepository,$pk=null,Request $request): Response
    {
        
        $pk = $request->query->get('pk');
        $minPrice = $request->query->get('min_price', 0);
        $maxPrice = $request->query->get('max_price', PHP_INT_MAX);
        $brands = $request->query->get('brands');
      // dd($pk,$minPrice,$maxPrice,$brands);
       //$pk,$minPrice,$maxPrice,$brands

        // Convertir les prix en centimes
        $minPrice = (int) $minPrice;
        $maxPrice = (int) $maxPrice;
        //dd($minPrice,$maxPrice);
        if(is_null($pk) && is_null($minPrice) && is_null($maxPrice) && is_null($brands)){
            $products = $productRepository->findProductsFromLastSixMonth();
        }else{
            //$products = $productRepository->findProductsFromLastSixMonthWithBrandId($pk);
           
                //$brandsArray = explode(',', $brands);
                if(strlen($brands)>0){
                    $brandsArray = array_map('intval', explode(',', $brands));
                }else{
                    $brandsArray=[];
                }
                
                //dd($pk,$minPrice,$maxPrice,$brands,$brandsArray);
                $products = $productRepository->findByPriceRangeAndCategoryBrand($pk,$minPrice,$maxPrice,$brandsArray);
               /* if(!is_int((int)$brands)){}else{
                $products = $productRepository->findProductsFromLastSixMonthWithBrandId($brands);
            }*/
            
          // dd($products);
        }
       
        $catagories = $categoryRepository->findAll();
        //$ranges = $productRepository->findPriceRangeFromLastSixMonth();
       // dd($ranges);
        //dd($catagories);
        $minPrice = null;
        $maxPrice = null;

        if (!empty($products)) {
            $prices = array_map(fn($product) => $product->getSoldePrice(), $products);
            $minPrice = min($prices);
            $maxPrice = max($prices);

        }
        if ($request->isXmlHttpRequest()) {  
            $productsArray = [];
                $brandsArray = explode(',', $brands);
               // dd($brands,$minPrice,$maxPrice,$brandsArray,$products);
            foreach ($products as $product) {
                $productsArray[] = [
                    'id' => $product->getId(),
                    'name' => $product->getName(),
                    'slug' => $product->getSlug(),
                    'description' => $product->getDescription(),
                    'regularPrice' => $product->getRegularPrice(),
                    'soldePrice' => $product->getSoldePrice(),
                    'imageUrls' => $product->getImageUrls(),
                    'isNewArrival' => $product->isIsNewArrival(),
                    'isSpecialOffer' => $product->isISSpecialOffer(),
                    'isFeatured' => $product->isIsFeatured(),
                    'createdAt' => $product->getCreatedAt()->format('Y-m-d H:i:s'),
                ];
            }
    
            return new JsonResponse([
                'products' => $productsArray,
                'minPrice' => $minPrice,
                'maxPrice' => $maxPrice,
            ]);
         } else {  
            return $this->render('shop_contoller/index.html.twig', [
                'controller_name' => 'ShopContollerController',
                'categories'=>$catagories,
                'products' => $products,
                'minPrice' => $minPrice,
                'maxPrice' => $maxPrice,
            ]); 
         } 

        
    }
    #[Route('/api/products', name: 'api_products', methods: ['GET'])]
    public function getProductsByCategory(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $categoryId = $request->query->get('category_id');

        if (!$categoryId) {
            return new JsonResponse(['error' => 'category_id is required'], 400);
        }

        // Fetch products by category
        $products = $productRepository->findByCategory($categoryId);

        // Calculate min and max prices
        $minPrice = null;
        $maxPrice = null;

        if (!empty($products)) {
            $prices = array_map(fn($product) => $product->getSoldePrice(), $products);
            $minPrice = min($prices);
            $maxPrice = max($prices);
        }

        $productsArray = [];
        foreach ($products as $product) {
            $productsArray[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'slug' => $product->getSlug(),
                'description' => $product->getDescription(),
                'regularPrice' => $product->getRegularPrice(),
                'soldePrice' => $product->getSoldePrice(),
                'imageUrls' => $product->getImageUrls(),
                'isNewArrival' => $product->isIsNewArrival(),
                'isSpecialOffer' => $product->isISSpecialOffer(),
                'isFeatured' => $product->isIsFeatured(),
                'createdAt' => $product->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return new JsonResponse([
            'products' => $productsArray,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ]);
        /* return new JsonResponse([
            'content' => $this->render('shop_contoller/includes/ajax_get_product_by_categorie_template.html.twig', [
                'products' => $products,])->getContent(),
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ]);*/
    }
    
    #[Route('/api/products/by/price/range/brands', name: 'api_products_by_price_range_brands', methods: ['POST'])]
    public function getProducts(Request $request, ProductRepository $productRepository): JsonResponse
    {
       //// $data = $request->getPayload();
        $brands = $request->get('brands');
        //dd($data,$request->get('min_price', 0),$request->get('max_price', PHP_INT_MAX),$brands);
        // Récupérer les paramètres de la requête
        if ($request->get('category_id') !== null){
            $categoryId = $request->get('category_id');
        }else{
            $categoryId=null;
        }

        $minPrice = $request->get('min_price', 0);
        $maxPrice = $request->get('max_price', PHP_INT_MAX);

        // Convertir les prix en centimes
        $minPrice = (int) $minPrice;
        $maxPrice = (int) $maxPrice;
        $brands = (array) $request->get('brands');
        if(count($brands)>0){
            $inClause = implode(",", $brands);
        }
        
        //dd($minPrice,$maxPrice,$brands,$inClause);
        // Récupérer les produits filtrés par catégorie et intervalle de prix
        $products = $productRepository->findByPriceRangeAndCategoryBrand($categoryId, $minPrice, $maxPrice,$brands);
        $minPrice = null;
        $maxPrice = null;

        if (!empty($products)) {
            $prices = array_map(fn($product) => $product->getSoldePrice(), $products);
            $minPrice = min($prices);
            $maxPrice = max($prices);
        }
       // dd($products);
        // Formatter les produits pour la réponse JSON
        $productsData = array_map(function($product) {
            return [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'slug' => $product->getSlug(),
                'description' => $product->getDescription(),
                'regularPrice' => $product->getRegularPrice(),
                'soldePrice' => $product->getSoldePrice(),
                'imageUrls' => $product->getImageUrls(),
                'isNewArrival' => $product->isIsNewArrival(),
                'isSpecialOffer' => $product->isISSpecialOffer(),
                'isFeatured' => $product->isIsFeatured(),
                'createdAt' => $product->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $products);

        return new JsonResponse([
            'products' => $productsData,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ]);
    }
}

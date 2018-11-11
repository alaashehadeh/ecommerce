<?php
/**
 * Created by PhpStorm.
 * User: alaa.shehadeh
 * Date: 11/4/2018
 * Time: 10:20 AM
 */

namespace App\Http\Services;

use App\Http\Helpers\productHelper;
use App\Http\Repositories\productsRepository;
use App\Http\Repositories\discountRepository;


class productsServices
{
    private $productsRepository;
    private $discountRepository;
    public function __construct(productsRepository $productsRepository,discountRepository $discountRepository)
    {
        $this->productsRepository = $productsRepository;
        $this->discountRepository = $discountRepository;
    }
    public function selectedProduct($id) {
        $output = array();
        $product = $this->productsRepository->findWhere(['id'=>$id])->first();
        if($product)
            $output = productHelper::singleProduct($product);
        return $output;
    }
    public function productWithDiscount($product) {
        $discount = $this->discountRepository->findWhere(['product_id'=>$product['id']])->first();
        if($discount)
            return productHelper::productWithDiscount($product,$discount);
        else
            return $product;
    }
}
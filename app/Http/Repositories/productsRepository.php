<?php
/**
 * Created by PhpStorm.
 * User: alaa.shehadeh
 * Date: 11/4/2018
 * Time: 9:39 AM
 */

namespace App\Http\Repositories;
use Prettus\Repository\Eloquent\BaseRepository;

class productsRepository extends BaseRepository
{
    function model()
    {
        return "App\\products";
    }
    function multiProductsWithDiscount($products) {
        return $this->model->selectRaw('*,products.id as id,products_discount.id as discount_id')
            ->join('products_discount', 'products.id', '=', 'products_discount.product_id','left outer')
            ->whereIn('products.id',$products)->get();
    }
    function searchProductsWithDiscount($search) {
        $query = $this->model->selectRaw('*,products.id as id,products_discount.id as discount_id')
            ->join('products_discount', 'products.id', '=', 'products_discount.product_id','left outer');

        //search by product name
        if(@$search->product_name)
            $query = $query->where('products.name','like','%'.$search->name.'%');

        //search by product price
        if(@$search->product_price & $search->price_currency)
            $query = $query->where('products.price',$search->product_price)->where('products.currency',$search->price_currency);

        //search by product discount
        if(@$search->discount_amount & @$search->discount_type)
            $query = $query->where('products_discount.type',$search->discount_type);

        return $query->get();

    }
}
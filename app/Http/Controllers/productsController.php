<?php

namespace App\Http\Controllers;

use App\Http\Helpers\productHelper;
use App\Http\Repositories\productsRepository;
use Illuminate\Http\Request;
use App\Http\Services\productsServices;
use Validator;

class productsController extends Controller
{
    private $productsRepository;
    private $productServices;

    public function __construct(productsRepository $productsRepository,productsServices $productsServices)
    {
        $this->productsRepository = $productsRepository;
        $this->productServices = $productsServices;
    }
    private function formValidator($data,  $type = 'add', $id = false)
    {
        $validation = array('price' => 'required|numeric','currency' => 'required|in:EUR,USD');

        if($type == 'add')
            $validation['name'] = 'required|unique:products|max:255';

        //form validation
        $validator = Validator::make($data, $validation);
        $validator = $validator->messages();
        return $validator->all();
    }
    public function addProduct(Request $request) {
        $data = productHelper::prepareCreate($request);
        //form validation
        $validatior = $this->formValidator($data);
        if($validatior)
            return response()->json($validatior,404);
        else {
            $product = $this->productsRepository->create($data);
            return response()->json($product);
        }
    }
    public function selectedProduct($id) {
        $product = $this->productServices->selectedProduct($id);
        if(count($product) == 0)
            return response()->json(['error'=>'The product not exist'],404);
        else {
            $product = $this->productServices->productWithDiscount($product);
            return response()->json($product);
        }
    }
    public function deleteSelectedProduct($id) {
        $product = $this->productServices->selectedProduct($id);
        if(count($product) == 0)
            return response()->json(['error'=>'The product not exist'],404);
        else {
            $this->productsRepository->delete($id);
            return response()->json(['success'=>'the selected product deleted correctly']);
        }
    }
    public function editProduct(Request $request,$id)
    {
        $product = $this->productServices->selectedProduct($id);
        if (count($product) == 0)
            return response()->json(['error' => 'The product not exist'], 404);
        else {
            //form validation
            $data = productHelper::prepareCreate($request);
            //form validation
            $validatior = $this->formValidator($data,'edit',$id);
            if ($validatior)
                return response()->json($validatior, 404);
            else {
                $product = $this->productsRepository->update($data, $id);
                return response()->json($product);
            }
        }
    }
    public function productSearch(Request $request) {
            $data = $this->productsRepository->searchProductsWithDiscount($request);
            if($data) {
                $data = productHelper::searchOutput($data);
                return response()->json($data);
            }
            else
                return response()->json(['error'=>'No result found'],404);
    }
}

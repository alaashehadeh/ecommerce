<?php

namespace App\Http\Controllers;

use App\Http\Helpers\discountHelper;
use App\Http\Repositories\discountRepository;
use Illuminate\Http\Request;
use App\Http\Services\productsServices;
use Validator;

class discountController extends Controller
{
    private $discountRepository;
    private $productServices;

    public function __construct(discountRepository $discountRepository,productsServices $productsServices)
    {
        $this->discountRepository = $discountRepository;
        $this->productServices = $productsServices;
    }
    private function formValidator($data, $type = 'add')
    {
        $validation = array(
            'type' => 'required|in:percentage,number');

        if($type == 'add')
            $validation['product_id'] = 'required|unique:products_discount';

        if(@$data['type'] == 'percentage')
            $validation['amount'] = 'required|numeric|min:1|max:99';
        else
            $validation['amount'] = 'required|numeric|min:1';

        //form validation
        $validator = Validator::make($data, $validation);
        $validator = $validator->messages();
        return $validator->all();
    }
    public function addDisocunt(Request $request)
    {
        //check the product first
        $product = $this->productServices->selectedProduct($request->product_id);
        if (count($product) == 0)
            return response()->json(['error' => 'The product not exist'], 404);
        else {
            $data = discountHelper::prepareCreate($request);

            //check if amount of discount less than the product price
            if($data['type'] == 'number' && $data['amount'] > $product['product_price'])
                return response()->json(['error' => 'The discount amount more than the product price'], 404);

            //form validation
            $validatior = $this->formValidator($data);
            if ($validatior)
                return response()->json($validatior, 404);
            else {
                $discount = $this->discountRepository->create($data);
                return response()->json($discount);
            }
        }
    }

    public function deleteDiscount($id) {
        $discount = $this->discountRepository->findWhere(['id'=>$id]);
        if(count($discount) == 0)
            return response()->json(['error'=>'The discount not exist'],404);
        else {
            $this->discountRepository->delete($id);
            return response()->json(['success'=>'the selected discount deleted correctly']);
        }
    }

    public function editDiscount(Request $request,$id)
    {
        $discount = $this->discountRepository->findWhere(['id'=>$id]);
        if(count($discount) == 0)
            return response()->json(['error'=>'The discount not exist'],404);
        else {
            //form validation
            $data = discountHelper::prepareCreate($request);
            //form validation
            $validatior = $this->formValidator($data,'edit');
            if ($validatior)
                return response()->json($validatior, 404);
            else {
                $discount = $this->discountRepository->update($data, $id);
                return response()->json($discount);
            }
        }
    }
}

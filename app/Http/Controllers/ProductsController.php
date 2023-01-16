<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
    */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $product = new Products();
        $product->name = $req->input('name');
        $product->description = $req->input('description');
        $product->price = $req->input('price');
        $product->By = $req->input('By');
        $product->number_of_purchases = 0;
        // product_image
        if ($req->hasFile("product_image")) {
            $uniqueid = uniqid();
            $original_name = $req->file("product_image")->getClientOriginalName();
            $size = $req->file("product_image")->getSize();
            $extension = $req->file("product_image")->getClientOriginalExtension();

            $name = $uniqueid . '.' . $extension;
            $path = $req->file("product_image")->storeAs('storage/uploads', $name);
            if ($path) {
                $product->product_image = $path;
                $product->save();
                return response()->json(array('status' => 'success', 'message' => 'Product saved', 'prod' => $product));
            } else {
                return response()->json(array('status' => 'error', 'message' => 'failed to save product'));
            }
        } else {
            return response()->json(array('status' => 'error', 'message' => 'No file, failed to save prod'));
        }
    }


    /**
     * Display the specified resource.
     */
    public function getProduct($id)
    {
        $result = Products::find($id);
        return $result;
    }

    public function List()
    {
        // return Products::Paginate(1);
        return Products::All();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->input('product_image') === null) {
            return ["result" => "null"];
        } else {
            $uniqueid = uniqid();
            $original_name = $request->file('product_image')->getClientOriginalName();
            $size = $request->file('product_image')->getSize();
            $extension = $request->file('product_image')->getClientOriginalExtension();

            $name = $uniqueid . '.' . $extension;
            $path = $request->file('product_image')->storeAs('/uploads', $name);
            $result = Products::updateOrCreate(
                ['id' => $request->input('id')],
                ['name' => $request->input('name'), 'description' => $request->input('description'), 'price' => $request->input('price'), 'By' => $request->input('By'), 'product_image' => $path]
            );
            return ["result" => "success"];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function Delete($id)
    {
        $result = Products::where('id', $id)->delete();
        if ($result) {
            return ["result" => "Product has been deleted successfully"];
        } else {
            return ["result" => "No record with the given Id"];
        }
    }

    public function searchWithCategory($searchValue= null, $category= null)
    {
        if ($category) {
            $cats=explode(",", $category);
            if ($searchValue!=" ") {
                //search with cat
                // return [clea"dd1"];
                return Products::where('name', 'Like', "%$searchValue%")
                ->whereIn('category', $cats)->get();
            } elseif ($searchValue==" ") {
                return Products::whereIn('category', $cats)->get();
                //category only
                // return ["dd2"];
            }
        }
        if ($category==null) {
            if ($searchValue==null || $searchValue==" ") {
                // return ["dd3"];
                return Products::All();
            } elseif ($searchValue!=" " || $searchValue!=null) {
                // return ["dd4"];
                return Products::where('name', "Like", "%$searchValue%")->get();
            }
        }
    }
    public function search($searchValue)
    {
        return Products::where('name', "Like", "%$searchValue%")->get();
    }
}

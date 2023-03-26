<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		 $products = Product::where('name', '!=', '')->orderBy('id', 'asc')->paginate(10);
         // $products = Product::all();		 
	  return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
		$product = new Product();
		$name = $request->name;
		$validname = str_replace('_;','_:', $name);//Заменим на двоеточие (точка с запятой служебн-разделитель).
		$product->name = $validname;
		$product->price = $request->price;
		$product->save();
        return redirect()->route('products');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
		 return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
		$product = Product::find($id);
		$name = $request->name;
		$validname = str_replace('_;','_:', $name);//Заменим на двоеточие (точка с запятой служебн-разделитель).
		$product->name = $validname;
		$product->price = $request->price;
		$product->save();
        return redirect()->route('products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $product = Product::find($id);
      $product->delete(); //- мягкое удаление т.е. отметит в базе флаг но всё останется
	  //$product->forceDelete(); //реальное удаление	
       return redirect()->route('products');
    }
}

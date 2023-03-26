<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;

use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 
 
    public function index(Builder $builder)
    {
/*         //$orders = Order::where('date', '!=', '')->orderBy('updated_at', 'desc')->paginate(10);
		//$orders = Order::all();
		if (request()->ajax()) {//Сюда запрос приходит автоматически после как отрисовали html-таблицы-шапку
		   $model = Order::query();
		    //$respons = DataTables::of($orders)->toJson();
		    //return $respons;
			 $respons = DataTables::eloquent($model)
			         ->addColumn('link', '<a href="#" onclick="this.href =\'ordersdel/\' + this.parentElement.parentElement.id" >Edit</a>')->setRowId('id')
                     ->addColumn('action', 'path.to.view')
                     ->rawColumns(['link', 'action'])
                     ->toJson();
			return $respons;
			
			//$orders = Order::where('date', '!=', '')->orderBy('updated_at', 'desc');				 			
  			return datatables()->of($orders)
				->addColumn('action', function ($row) {
					$html = '<a href="'.route('orders.edit', ['id' => $row->id ]).'" title="редактировать"> Edit </a>&nbsp; &nbsp; &nbsp;';						 
					//$html .= '<a onclick=\'  if(confirm("Удалим это ?")){delOrder('.$row->id.')} \' style="cursor:pointer; color:red;" > X </a>&nbsp; &nbsp; &nbsp;';						
					$html .= '<button form="delOrder"  type="submit" style="color:red"   title="Удалить" > X </button>
								<form id="delOrder" action="'.route('orders.destroy', ['id' => $row->id ]).'" method="post" onsubmit="return confirm('.$row->id.')">
								  <input type="hidden" name="_token" value="'.csrf_token().'">
								</form>';
					return $html;
				})->toJson();  
		} 
		$html = $builder->columns([//Сперва отрисовывем html-таблицы-шапку готовим к ajax-зполнению         
		Column::make('date'),
		Column::make('phone'),
		Column::make('addres'),
		Column::make('total')->title('сумма'),
		 Column::make('link')->title('+++'),
		//Column::make('product_id'),
		]);//->addAction(['title'=> '<a href="orders/create" style="color:green; font-size:120%; cursor:pointer;"> +++ </a> ','width' => '180px']);
	  */
	 
		if (request()->ajax()) {//Сюда запрос приходит автоматически после как отрисовали html-таблицы-шапку
			   // return DataTables::of(Product::query())->toJson();	   
				 $orders = \App\Models\Order::all();
				//$orders = Order::where('date', '!=', '')->orderBy('updated_at', 'desc')->get();
				// $url = route('orders.edit', ['id' => $row->id ] );				
				return datatables()->of($orders)
					->addColumn('action', function ($row) {
						$html = '<a href="'.route('orders.edit', ['id' => $row->id ]).'" title="редактировать"> Edit </a>&nbsp; &nbsp; &nbsp;';
						//$html .= '<a onclick=\'  if(confirm("Удалим это ?")){delOrder('.$row->id.')} \' style="cursor:pointer; color:red;" > X </a>&nbsp; &nbsp; &nbsp;';						
						 $html .= '<button form="delOrder'.$row->id.'"  type="submit" style="color:red"   title="Удалить" > X </button>
						            <form id="delOrder'.$row->id.'" action="'.route('orders.destroy', ['id' => $row->id ]).'" method="post" onsubmit="return confirm(\'Удалить этот документ ?\')">
				                      <input type="hidden" name="_token" value="'.csrf_token().'">
                                    </form>';
						return $html;
					})->toJson();
			} 
			$html = $builder->columns([//Сперва отрисовывем html-таблицы-шапку готовим к ajax-зполнению         
				Column::make('date')->title('дата'),
				Column::make('phone')->title('телефон'),
				Column::make('addres')->title('адрес'),
				Column::make('total')->title('сумма'),
				//Column::make('email'),
				//Column::make('product_id'),
			])->addAction(['title'=> '<a href="orders/create" style="color:green; font-size:120%; cursor:pointer;"> +++ </a> ','width' => '180px']);
		 	 
	  
		return view('orders.index', compact('html')); 
    }

   
    public function create()
    {
		 $products = Product::where('name', '!=', '')->orderBy('updated_at', 'desc')->paginate(100);
         // $products = Product::all();		 
	    return view('orders.create', compact('products'));      
    }

 
    public function store(OrderRequest $request)
    {		 
        $newOrder = new Order();  
        $newOrder->date = $request->date;
        $newOrder->phone = $request->phone;
        $newOrder->email = $request->email;
        $newOrder->addres = $request->addres;
        $newOrder->geometka = $request->geometka;
		$newOrder->mapzoom = $request->mapzoom;
		$newOrder->mapcenter = $request->mapcenter;
        $newOrder->total = $request->total;
		$prod_ids = ''; $summaAll = 0;
        foreach($request->tovar as $kye=>$tovar){  //Сохраняем инфу из денамических полей таблицы Товары
           $prod_ids = $prod_ids . $tovar['tovar_id'].'_;'.$tovar['nametovar'].'_;'.$tovar['count'].'_;'.$tovar['summa'].'__;';
        }
		$newOrder->prod_ids = $prod_ids;
        $newOrder->save();
        return redirect()->route('orders');
    }

 
    public function edit($id)
    {
      	$order = Order::find($id); 
		$tovarsArr = explode('__;',$order->prod_ids);//Разбили на массив напр. [0]='2_esname_1_300'
		//Нужно выдать масс. напр. ['tovar' => [0]=>['tovar_id'=>1,'nametovar'=>'а_товар11', 'count'=>1, 'summa'=>100]]
        $arrTovars =['tovar'];  $i=0;		
		foreach($tovarsArr as $tovar){
          if($tovar){// Проверим последний элемент может быть пустым
			 $arrT = explode('_;',$tovar);//Напр.$arrT[0]=>1,$arrT[1]=>'а_товар11',$arrT[1]=>1.....
			 $arrTovars['tovar'][$i]['tovar_id'] = $arrT[0];
			 $arrTovars['tovar'][$i]['nametovar'] = $arrT[1];
			 $arrTovars['tovar'][$i]['count'] = $arrT[2];
			 $arrTovars['tovar'][$i]['summa'] = $arrT[3];
			 $i++;
		  }
		}
		$products = Product::where('name', '!=', '')->orderBy('updated_at', 'desc')->paginate(100);  
	    return view('orders.edit', compact('order','products', 'arrTovars'));  
    }

   
 
    public function update(OrderRequest $request, $id)
    {
		$order = Order::find($id);  
        $order->date = $request->date;
        $order->phone = $request->phone;
        $order->email = $request->email;
        $order->addres = $request->addres;
        $order->geometka = $request->geometka;
		$order->mapzoom = $request->mapzoom;
		$order->mapcenter = $request->mapcenter;
        $order->total = $request->total;
		$prod_ids = ''; $summaAll = 0;
        foreach($request->tovar as $kye=>$tovar){  //Сохраняем инфу из денамических полей таблицы Товары
           $prod_ids = $prod_ids . $tovar['tovar_id'].'_;'.$tovar['nametovar'].'_;'.$tovar['count'].'_;'.$tovar['summa'].'__;';
        }
		$order->prod_ids = $prod_ids;
        $order->save();
        return redirect()->route('orders');
        return redirect()->route('orders');
    }

 
    public function destroy($id)
    {
		$order = Order::find($id);
		$order->delete();
        return redirect()->route('orders');
    }
}

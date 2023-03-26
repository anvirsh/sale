<style>
	table {
	font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	text-align: left;
	border-collapse: separate;
	border-spacing: 5px;
	background: #ECE9E0;
	color: #656665;
	border: 16px solid #ECE9E0;
	border-radius: 20px;
	}
	th {font-size: 18px; padding: 10px;} td { background: #e5e7e; padding: 10px; }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ТОВАРЫ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">				
				  <table class="table table-hover table-bordered">
				    <thead class="thead-light">
				      <tr>
					   <th>Наименование </th>
					   <th>Цена</th>											 
					    <th>
						    <a   style="color:green" href="{{route('products.create')}}" title="Создать товар"  > +++ </a>
						</th>
					   </tr>
				    </thead>
				    <tbody id="boditab">
                    @foreach ($products as $product)				  
					 <tr>
					   <td style="cursor: pointer;"><span><a href="#" style="color:#111">{{ $product->name }}</a></span></td>
					   <td style="cursor: pointer;"><span>{{ $product->price }}</td>
					  
					   <td>                 						
							<a href="{{route('products.edit', $product->id )}}" title="Редактировать"> Edit </a>&nbsp; &nbsp;
							<button form="delProd{{ $product->name }}" type="submit" class="m-0 p-0 border-0 bg-transparent" title="Удалить"><span style="color:red"> X </span/></button>
					   </td>
					 </tr>
					 <form id="delProd{{ $product->name }}" action="{{route('products.destroy', $product->id )}}" method="post" onsubmit="return confirm('Удалить {{ $product->name }} ?')">
					   @csrf
					   @method('DELETE')
					 </form> 
                    @endforeach					
				    </tbody>
			     </table>
                </div>
				{{ $products->links() }}
				
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href='{{ route("products")}}'> << </a>  {{ __('Создание  товара') }}
        </h2>
    </x-slot> 
	 @if(Session::has('message'))
		  <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show col-md-12" role="alert">
            <span style="color:red"> {{ Session::get('message') }}</span>
            <button  type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span  onclick="offErr()" aria-hidden="true">&times;</span>
            </button>
          </div>
	  @endif
	  @if (count($errors) > 0)
         <div class="alert alert-danger" style="text-align: center;" role="alert">
	 	   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
             <span  onclick="offErr()" aria-hidden="true">&times;</span>
           </button>
           @foreach ($errors->all() as $error)
            <b style="color:red">  {{ $loop->iteration.") - ". $error }}</b><hr>
           @endforeach
         </div>
       @endif
     <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
               <div class="p-6 text-gray-900"> 
 	           <form id="formNomnklt" enctype="multipart/form-data" method="POST"  action="{{route('products.store')}}">
	           {{csrf_field()}}
                 <input type="text" name="name"  > &nbsp;&nbsp; &nbsp; <span> Наименование </span> </br> </br>
				 <input type="text" name="price" > <span> &nbsp;&nbsp; &nbsp; Цена  </span>  </br> </br>
                 <input type="submit" style="cursor:pointer" value="Создать" >
             	</form>			 
                </div>
            </div>
        </div>
    </div>  
      <script>
	      function offErr(){ $('.alert').fadeOut(1000);	}
	  </script>
</x-app-layout>
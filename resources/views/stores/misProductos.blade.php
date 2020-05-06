@extends('layouts.app')
@section('content')
<?php /* $storeOffers=Auth::user()->store->offers;  */

   
?>
<div class="container">
<div id="menuMisProductos">
    <ul class="nav flex-lg-column justify-content-center nav-pills " id="myTab" role="tablist">
        <li ><a class="nav-item" id="nav-perfil-tab"    href="{{route('stores.miPerfil')}}"  aria-controls="nav-perfil" aria-selected="false">Perfil</a></li>
        <li ><a class="nav-item active" id="nav-ventas-tab" href="{{route('stores.misVentas')}}"  aria-controls="nav-ventas" aria-selected="true">Ventas</a></li>
        <li><a class="nav-item " id="nav-productos-tab"  href="{{route('stores.misProductos')}}"  aria-controls="nav-productos" aria-selected="false">Productos</a></li>
    </ul>
</div>
<div id="formProductos">
    <div id="imgAgregarProd">
        <img src="{{ asset('assets/camera_ 1.svg') }}" alt="producto">
    </div>
    

    <form class="col-md-12 col-lg-12" method="POST" action="/offers">
        @csrf
        <div class="form-group ">
            <label for="inputTitulo">Título</label>
               <input type="text" name="name_offer" placeholder="Nombre" required class="form-control mb-2"/>
        </div>
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Descripción</label>
            
      
            <textarea name="description_offer" placeholder="Descripción" required class="form-control mb-2" id="exampleFormControlTextarea1" rows="3" ></textarea>
        </div>
        <div class="form-group">
            <label for="inputPrecio">Precio</label>
            <input
            type="text"
            name="cost"
            placeholder="Precio"
            class="form-control mb-2" id="inputPrecio" required/>
     
        </div>
        <button type="submit" class="btn btn-principal btn-block" style="font-weight: bold;" id="btnAgregarProducto">Agregar producto</button>
    </form>
</div>
<!-- Fin Formulario Agregar Producto -->

<!-- Inicio Productos del comercio -->
<section id="misProductos">

    <h1>Productos</h1>

    <div id="tarjetasMisProductos">
        @if(session()->get('success'))
 
      
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            
            <strong>{{ session()->get('success') }}</strong> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
    
    @endif
    @foreach($storeOffers as $offers)
        <div id="tarjProducto">
            <img src="{{asset('assets/logo%20regalo.svg')}}" alt="producto">
            <form action="{{ route('offers.destroy', $offers->id)}}" method="post">
                @csrf
                @method('DELETE')
                <input src="{{asset('assets/borrar.svg')}}" type="image" alt="borrar" title="Eliminar" id="eliminar">
            </form>
       
            <p id="nombreProducto">{{$offers->name_offer}}</p>
            <p id="descrProducto">{{$offers->description_offer}}</p>
            <p id="precioProd">$ {{$offers->cost}}</p>
        </div>

    @endforeach
    </div>

</section>
</div>

@endsection

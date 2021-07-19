@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
       <div class="col-md-8">
           <div class="card">
               <div class="card-header">Outfits</div>

               <div class="card-body">
               <ul class="list-group">
                 @foreach ($outfits as $outfit)
                  <li class="list-group-item">
                    <div class="list-container">
                      <div class="list-container__content">
                        <span class="list-container__content__outfit">{{$outfit->type}} size: {{$outfit->size}}</span>
                        <span class="list-container__content__master">{{$outfit->masterOfOutfit->name}} {{$outfit->masterOfOutfit->surname}}</span>
                      </div>
                      <div class="list-container__buttons">
                        <a href="{{route('outfit.edit', [$outfit])}}" class="btn btn-success">Edit</a>
                        <form method="POST" action="{{route('outfit.destroy', [$outfit])}}">
                          @csrf
                          <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                      </div>
                    </div>
                @endforeach
                </ul>
               </div>
           </div>
       </div>
   </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
       <div class="col-md-8">
           <div class="card">
               <div class="card-header">{{$outfit->type}}</div>
               <div class="card-body">

                    <div class="form-group">
                        <small class="form-text text-muted"><b>Outfit type: </b> {{$outfit->type}}</small>
                    </div>
                    <div class="form-group">
                        <small class="form-text text-muted"><b style="color: {{$outfit->color}}"> Outfit color: </b> {{$outfit->color}}</small>
                    </div>
                    <div class="form-group">
                        <small class="form-text text-muted"><b> Outfit size: </b>{{$outfit->size}}</small>
                    </div>
                    <div class="form-group">
                        <small class="form-text text-muted"><b>About outfit:</b> <div> <br> {!!$outfit->about!!} </div> </small>
                    </div>
                    <div class="form-group">                    
                        <small class="form-text text-muted"><b>Master designer: </b> {{$outfit->masterOfOutfit->name}} {{$outfit->masterOfOutfit->surname}}</small>
                    </div>
                    @csrf
                        <a href="{{route('outfit.edit', [$outfit])}}" class="btn btn-success">Edit</a>
                        <a href="{{route('outfit.pdf', [$outfit])}}" class="btn btn-success">View PDF</a>
                    </form>
               </div>
           </div>
       </div>
   </div>
</div>
<script>
$(document).ready(function() {
   $('#summernote').summernote();
 });
</script>
@endsection
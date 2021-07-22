@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
       <div class="col-md-8">
           <div class="card">
               <div class="card-header">Edit Outfit</div>
               <div class="card-body">
                 <form method="POST" action="{{route('outfit.update', [$outfit])}}" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Type: </label>
                        <input type="text" class="form-control" name="outfit_type" value="{{$outfit->type}}">
                        <small class="form-text text-muted">Enter outfit type.</small>
                    </div>
                    <div class="form-group">
                        <label>Color: </label>
                        <input type="text" class="form-control" name="outfit_color" value="{{$outfit->color}}">
                        <small class="form-text text-muted">Which color.</small>
                    </div>
                    <div class="form-group">
                        <label>Size: </label>
                        <input type="text" class="form-control" name="outfit_size" value="{{$outfit->size}}">
                        <small class="form-text text-muted">Outfit size.</small>
                    </div>

                     <div class="form-group">
                            <div class="small-photo">
                                @if($outfit->photo)
                                <img src="{{$outfit->photo}}">
                                <br>
                                <label>Delete photo 
                                <input type="checkbox" name="delete_outfit_photo"></label> 
                                @else
                                <img src="{{asset('no-image.jpg')}}">
                                @endif
                            </div>
                            <label>Photo: </label>
                            <input type="file" class="form-control" name="outfit_photo">
                            <small class="form-text text-muted">Upload outfit photo.</small>
                        </div>

                    <div class="form-group">
                        <label>About: </label>
                        <textarea name="outfit_about" class="form-control" id="summernote">{{$outfit->about}}</textarea>
                        <small class="form-text text-muted">About outfit</small>
                    </div>
                    <div class="form-group">                     
                        <select name="master_id"  class="form-control">
                            @foreach ($masters as $master)
                                <option value="{{$master->id}}" @if($master->id == $outfit->master_id) selected @endif>
                                    {{$master->name}} {{$master->surname}}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Select Master from the list.</small>
                    </div>
                    @csrf
                    <button type="submit" class="btn btn-primary">Edit</button>
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
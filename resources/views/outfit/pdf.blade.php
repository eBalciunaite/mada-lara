{{-- cia viskas senoviskai. neveikia jokie flexai ir fancy css. viskas basic, kaip kokiam emaile --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <style>
        @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 400;
        src: url({{ asset('fonts/Roboto-Regular.ttf') }});
        }
        @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: bold;
        src: url({{ asset('fonts/Roboto-Bold.ttf') }});
        }
        body {
        font-family: 'Roboto';
        }
        </style>
</head>
<body>

    <b>Master designer: </b> {{$outfit->masterOfOutfit->name}} {{$outfit->masterOfOutfit->surname}}
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


</body>
</html>
@if (count($errors))
 
    @foreach($errors->all() as $error)
        <div class='row mb-3'>
            <div class="col-lg-12 bg-danger p-xs b-r-sm"> <strong>Error!</strong> {{ $error }}</div>
        </div>
        <br>
    @endforeach
@endif
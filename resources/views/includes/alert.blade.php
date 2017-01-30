@if(session('message'))
    <div class="col-md-12">
        <div class="alert alert-success alert-no-border alert-close alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{session('message')}}
        </div>
    </div>
@endif
@if ($errors->any())
    <div class="col-md-12">
        <div class="alert alert-danger alert-no-border alert-close alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! implode('', $errors->all(':message')) !!}
        </div>
    </div>
@endif
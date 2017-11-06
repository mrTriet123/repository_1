@extends('default')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="text-center m-t-lg">
            <h1>
                Role List
            </h1>
             {!! csrf_field() !!}
                <div class="form-group">
                    <label>Name :</label>{{$role['name']}}
                </div>
                <div class="form-group">
                    <label>Display name :</label>{{$role['display_name']}}
                </div>
                <div class="form-group">
                   <label>Description :</label>{{$role['description']}}
                </div>
                <div class="form-group">
                    <label>Permission :</label>
                    @foreach($role['roles'] as $roleName)
                        {{$roleName}}<br>
                    @endforeach
                </div>
        </div>
    </div>
</div>
@endsection
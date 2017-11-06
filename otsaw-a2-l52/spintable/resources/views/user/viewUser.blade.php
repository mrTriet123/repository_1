@extends('default')

@section('content')

@include('layouts.default.breadcrumb')

<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">
        <div class="col-lg-12">

            <div class="ibox product-detail">
                <div class="ibox-content">

                    <div class="row">
                        <div class="col-md-5">

                            <div class="product-images">

                                <div>
                                    <div class="image-imitation">
                                        [DRIVER IMAGE]
                                    </div>
                                </div>


                            </div>

                        </div>
                        <div class="col-md-7">

                            <h2 class="font-bold m-b-xs">
                                Driver Profile Detail
                            </h2>
                            <small>Many desktop publishing packages and web page editors now.</small>
                            <div class="m-t-md">
                                <h2 class="product-main-price">{{$user['firstname']}} {{$user['lastname']}} <small class="text-muted">{{$user['email']}}</small> </h2>
                            </div>
                            <hr>

                            <h4>User Role</h4>

                            <div class="small text-muted">
                                {{$user['role']}}
                            </div>
                            <dl class="small m-t-md">
                                <dt>Description lists</dt>
                                <dd>A description list is perfect for defining terms.</dd>
                                <dt>Euismod</dt>
                                <dd>Vestibulum id ligula porta felis euismod semper eget lacinia odio sem nec elit.</dd>
                                <dd>Donec id elit non mi porta gravida at eget metus.</dd>
                                <dt>Malesuada porta</dt>
                                <dd>Etiam porta sem malesuada magna mollis euismod.</dd>
                            </dl>
                            <hr>

                            <div>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-sm"><i class="fa fa-cart-plus"></i> Add to cart</button>
                                    <button class="btn btn-white btn-sm"><i class="fa fa-star"></i> Add to wishlist </button>
                                    <button class="btn btn-white btn-sm"><i class="fa fa-envelope"></i> Contact with author </button>
                                </div>
                            </div>



                        </div>
                    </div>

                </div>
                <div class="ibox-footer">
                    <span class="pull-right">
                        Full stock - <i class="fa fa-clock-o"></i> 14.04.2016 10:04 pm
                    </span>
                    The generated Lorem Ipsum is therefore always free
                </div>
            </div>

        </div>
</div>

</div>
@endsection

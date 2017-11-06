{{-- <div class="alert alert-success alert-dismissable">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    A wonderful serenity has taken possession. <a class="alert-link" href="#">Alert Link</a>.
</div> --}}
{{--<div class="alert alert-info alert-dismissable">--}}
    {{--<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>--}}
    {{--A wonderful serenity has taken possession. <a class="alert-link" href="#">Alert Link</a>.--}}
{{--</div>--}}
{{--<div class="alert alert-warning alert-dismissable">--}}
    {{--<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>--}}
    {{--A wonderful serenity has taken possession. <a class="alert-link" href="#">Alert Link</a>.--}}
{{--</div>--}}
{{--<div class="alert alert-danger alert-dismissable">--}}
    {{--<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>--}}
    {{--A wonderful serenity has taken possession. <a class="alert-link" href="#">Alert Link</a>.--}}
{{--</div>--}}
@if (session('success'))
	<div class="alert alert-success alert-dismissable">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    {{session('success')}}
</div>
@endif


@if (session('error'))
	<div class="alert alert-danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert">×</button>
		{{session('error')}}
	</div>
@endif

@if (count($errors) > 0)
	<div class="alert alert-danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert">×</button>
		<ul>
			@foreach ($errors->all() as $errors)
				<li>{{ $errors }}</li>
			@endforeach
		</ul>
	</div>
@endif
@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-9 col-md-offset-5">
    <h2>Add an Employee</h2>

    @if(count($errors) > 0 )
		<div class="alert alert-danger">
			<ul>
			@foreach ($errors->all() as $error)
				<li> {{ $error }} </li>
			@endforeach
			</ul>
	    </div>
    @endif

    <div class="form-group">
       {!! Form::open(['method'=>'POST', 'action'=>'EmployeeController@store', 'files'=>true])!!}
       
     <div class="form-group">
        {!! Form::label('firstName', 'FIRSTNAME') !!}
        {!! Form::text('firstName', null, ['class'=>'form-control']) !!}
     </div>
     <div class="form-group">
        {!! Form::label('lastName', 'LASTNAME') !!}
        {!! Form::text('lastName', null, ['class'=>'form-control']) !!}
     </div>
     <div class="form-group">
        {!! Form::label('email', 'EMAIL') !!}
        {!! Form::email('email', null, ['class'=>'form-control']) !!}
     </div>
     <div class="form-group">
        {!! Form::label('phone', 'PHONE') !!}
        {!! Form::number('phone', null, ['class'=>'form-control']) !!}
     </div>
     <div class="form-group">
        {!! Form::label('company_id', 'COMPANY') !!}
        {!! Form::select('company_id', [ ''=> 'Choose Options' ] + $comps, null, ['class'=>'form-control']) !!}
     </div>
     
        {{csrf_field()}}
    <div class="form-group">
      {!! Form::submit('submit', ['class'=>'btn btn-primary'])!!}
    </div>
    {!! Form::close() !!}
  </div>
    </div>
  </div>
</div> 

@endsection

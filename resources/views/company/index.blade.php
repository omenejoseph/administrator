@extends('layouts.app')

@section('content')

<div class="container">
<a href="{{url('/companies/create')}}"> <button class="btn btn-primary">Add Company </button></a>
<div class="row">
    <div class="col-md-9 col-md-offset-2">
        <table class="table" id="table">
            <thead>
                <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Website</th>
                <th>Logo</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($comps as $key => $comp)
              <tr>
                <td>{{$key + 1}}</td>
                <td>{{$comp->name}}</td>
                <td>{{$comp->email}}</td>
                <td>{{$comp->website}}</td>
                <td>
                <img src="{{  URL::to('/') }}/images/{{ $comp->logo  }}" alt="">
                </td>
                <td>
                <div class="dropdown">
                 <i class="dropdown-toggle fas fa-cogs" data-toggle="dropdown"></i>
                    <ul class="dropdown-menu dropdown-menu-left">
                    <li><a href="javascript:void(0);" 
                            data-id="{{$comp->id}}"
                            data-name="{{$comp->name}}" 
                            data-email= "{{$comp->email}}"
                            data-website= "{{$comp->website}}"
                            data-url='{{url("companies/update")}}'
                            class="edit-modal"><i class="fa fa-edit"></i> Edit Company</a></li>
                    <li><a href="javascript:void(0);" 
                           class="delete-btn" 
                           data-id="{{$comp->id}}" 
                           data-url='{{url("companies/delete")}}'><i class="fa fa-trash"></i> Delete Company</a></li>
                    </ul>
               </div>
                </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- modal starts -->
<div class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Company</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="edit-user-form" data-parsley-validate="" enctype="multipart/form-data">
             <div class="form-group col-xs-12">
                <label for="input-edit-name">NAME <i class="text-danger">*</i> :</label>
                <input name="name" type="text" class="form-control" id="input-edit-name" placeholder="Full Name" required/>
            </div>
            <div class="form-group col-xs-12">
                <label for="input-edit-email">EMAIL <i class="text-danger">*</i> :</label>
                <input name="email" type="text" class="form-control" id="input-edit-email" placeholder="Email" required/>
            </div>
            <div class="form-group col-xs-12">
                <label for="input-edit-role">WEBSITE <i class="text-danger">*</i> :</label>
                <input name="website" type="text" class="form-control" id="input-edit-website" placeholder="Website" required/>
            </div> 
            <div class="col-xs-12">
                <div class="input-group pad-up pull-right">
                     <button type="button" class="btn btn-default margin-right" data-dismiss="modal">Cancel</button>
                     <button type="submit" data-id='' data-toggle-data='' id="user-edit-send-btn" class="btn btn-primary"><i class="fa fa-edit"></i> Submit </button>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">>
      </div>
    </div>
  </div>
</div>
<!-- modal ends -->
</div>

@endsection

@section('scripts')
<script>
        $(document).ready(function () {
            $.noConflict();
            $('#table').DataTable();

            if (typeof jQuery === "undefined") {
                throw new Error("admin requires jQuery");
            }

            $('.delete-btn').on('click', function () {
                if(confirm("Are you sure you want to delete this Company?")){
                    var getId = $(this).data('id');
                    var getUrl = $(this).data('url');

                    var url = getUrl;
                    var id = getId;

                     $.ajaxSetup({
                         headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                    url     :  url,
                    type    : 'POST',
                    data    : {
                        id:id, 
                        _method: "DELETE"
                    },
                    dataType: 'json',
                    success : function ( json )
                    {
                        alert('Company Deleted');
                        window.location.reload();
                    },
                    error: function()
                    {
                       alert("something went wrong please try again");
                    }
                });

                }
                
            });

            $('.edit-modal').on('click', function(){
                console.log('clicked');
                var getId = $(this).data('id');
                var getName = $(this).data('name');
                var getEmail = $(this).data('email');
                var getWebsite = $(this).data('website');
                var getUrl = $(this).data('url');

                $('#input-edit-name').val(getName);
                $('#input-edit-email').val(getEmail);
                $('#input-edit-website').val(getWebsite);

                $('.modal').modal('show');

                var form = $('#edit-user-form');
                form.submit(function (e){
                    e.preventDefault(); 
                    var url = getUrl;
                    var id = getId;

                    var formData = form.serializeArray();
                    formData.push({name: '_method', value:'PUT'});
                    formData.push({name: 'id', value: id}); 

                $('#edit-user-form #user-edit-send-btn').html("<i class='fa fa-spinner fa-spin' aria-hidden='true'></i> Updating...");
                $('#user-edit-send-btn').attr('disabled', true);
                    $.ajaxSetup({
                         headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                 $.ajax({
                    url     :  url,
                    type    : 'POST',
                    data    : formData,
                    dataType: 'json',
                    success : function ( json )
                    {   
                        $('.modal').modal('hide');
                        alert('Company Updated');
                        window.location.reload();
                    },
                    error: function()
                    {
                        alert('There was an error please try again');
                    }
                });

            });
         });
        });

 </script>
@endsection



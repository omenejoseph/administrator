@extends('layouts.app')

@section('content')

<div class="container">
<a href="{{url('/employees/create')}}"> <button class="btn btn-primary">Add Employee </button></a>
<div class="row">
    <div class="col-md-9 col-md-offset-2">
        <table class="table" id="table">
            <thead>
                <tr>
                <th>#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($emps as $key => $emp)
              <tr>
                <td>{{$key + 1}}</td>
                <td>{{$emp->firstName}}</td>
                <td>{{$emp->lastName}}</td>
                <td>{{$emp->email}}</td>
                <td>{{$emp->phone}}</td>
                <td>
                <div class="dropdown">
                 <i class="dropdown-toggle fas fa-cogs" data-toggle="dropdown"></i>
                    <ul class="dropdown-menu dropdown-menu-left">
                    <li><a href="javascript:void(0);" 
                            data-id="{{$emp->id}}"
                            data-first-name="{{$emp->firstName}}" 
                            data-last-name="{{$emp->lastName}}" 
                            data-email= "{{$emp->email}}"
                            data-phone= "{{$emp->phone}}"
                            data-url='{{url("employees/update")}}'
                            class="edit-modal"><i class="fa fa-edit"></i> Edit Employee</a></li>
                    <li><a href="javascript:void(0);" 
                           class="delete-btn" 
                           data-id="{{$emp->id}}" 
                           data-url='{{url("employees/delete")}}'><i class="fa fa-trash"></i> Delete Employee</a></li>
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
        <h5 class="modal-title">Edit Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="edit-user-form" data-parsley-validate="" enctype="multipart/form-data">
             <div class="form-group col-xs-12">
                <label for="input-edit-name">FIRST NAME <i class="text-danger">*</i> :</label>
                <input name="firstName" type="text" class="form-control" id="input-edit-firstName" placeholder="First Name" required/>
            </div>
            <div class="form-group col-xs-12">
                <label for="input-edit-name">LAST NAME <i class="text-danger">*</i> :</label>
                <input name="lastName" type="text" class="form-control" id="input-edit-lastName" placeholder="Last Name" required/>
            </div>
            <div class="form-group col-xs-12">
                <label for="input-edit-email">EMAIL <i class="text-danger">*</i> :</label>
                <input name="email" type="text" class="form-control" id="input-edit-email" placeholder="Email" required/>
            </div>
            <div class="form-group col-xs-12">
                <label for="input-edit-role">PHONE NUMBER <i class="text-danger">*</i> :</label>
                <input name="phone" type="text" class="form-control" id="input-edit-phone" placeholder="phone" required/>
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
                if(confirm("Are you sure you want to delete this Employee?")){
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
                        alert('Employee Deleted');
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
                var getFirstName = $(this).data('first-name');
                var getLastName = $(this).data('last-name');
                var getEmail = $(this).data('email');
                var getPhone = $(this).data('phone');
                var getUrl = $(this).data('url');

                $('#input-edit-firstName').val(getFirstName);
                $('#input-edit-lastName').val(getLastName);
                $('#input-edit-email').val(getEmail);
                $('#input-edit-phone').val(getPhone);

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
                        alert('Employee Updated');
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



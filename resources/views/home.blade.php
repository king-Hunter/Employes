@extends('layouts.app')
@section('css')
    <style>
        .fa {
            font-size: 2rem;
        }

        .view {
            color: blue;
        }

        .edit {
            color: grey;
        }

        .delete {
            color: red;
        }

        .tuerca {
            position: relative;
            left: 1rem;
        }
        .modalclose{
            position: absolute;
            right: 1rem;
            bottom: 1rem;
        }
        .jumbotron {
            margin-bottom: 0rem !important;
        }
        .permon {
            font-size: 58% !important;
            position: absolute;
}
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-end py-4">
            <div class="col-2">
                <a href="{{ route('getAdd') }}" type="button" class="btn btn-primary btn-lg">Add new</a>
            </div>

        </div>
        <div class="row justify-content-center">
                    @if ($item['info'] > 0)
                        <table class="table table-hover">
                            <thead>
                                <tr class="card-header">
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Salary MXN</th>
                                    <th>Salary USD</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>View</th>
                                    <th>Edit </th>
                                    <th>Delete </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item['data'] as $i)
                                    <tr class="employe{{ $i->id }}">
                                        <th scope="row">{{ $i->code }}</th>
                                        <td>{{ $i->name }}</td>
                                        <td>${{ $i->salary_pesos }}</td>
                                        <td>${{ $i->salary_dollar }}</td>
                                        <td>{{ $i->email }}</td>
                                        <td>
                                            <div class="container-fluid d-none chatatos">
                                                <div class="row justify-content-center">
                                                    <div class="spinner-border text-center text-dark " role="status">
                                                        <span class="sr-only">Loading...</span>
                                                      </div>
                                                </div>
                                            </div>
                                            <div class="d-block chatatus">
                                                <?php $disable = ($i->active == 0) ? ' d-block' : ' d-none'; ?>
                                                <?php $enable = ($i->active == 0) ? ' d-none' : ' d-block'; ?>    
                                                    <span onclick="changeStatus({{$i->id}})" style="" type="button" class="itemTooltip{{ $enable }} badge badge-pill badge-success statusActive" data-toggle="tooltip"
                                                        data-placement="top" title="Change Status on click">
                                                        Active
                                                    </span>
                                                    <span onclick="changeStatus({{$i->id}})" style="" type="button" class="itemTooltip{{ $disable }} badge badge-pill badge-danger statusInactive" data-toggle="tooltip"
                                                        data-placement="top" title="Change Status on click">
                                                        Inactive
                                                    </span>
                                            </div>
                                        </td>
                                        <td>
                                           <a onclick="viewInfo({{ $i->id }})">
                                            <span type="button" class=" view fa fa-eye"></span>
                                               </a> 
                                        </td>
                                        <td>
                                            <span type="button" class="edit fa fa-edit"></span>
                                        </td>
                                        <td>
                                            <a type="button" onclick="confirmarEliminar({{ $i->id }})">
                                                <span class=" delete fa fa-trash"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="card-body text-center">
                            <h1><span class="badge badge-info">Sorry! </span> You don't have data </h1>
                        </div>
                    @endif
        </div>
    </div>
    <!-- Button trigger modal -->
  
  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="jumbotron">
            <div class="container-fluid">
                <div class="row justify-content-between">
                    <h4 class="display-5"><span class="name-info"></span></h4>
                    <h6 class="display-5">Code: <span class="code-info"></span></h6>
                </div>
            </div>
            <hr class="my-4">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-6">
                        <h6>Salary : </h6>
            <p class="lead"> MXN : $<span class="salary-mxn"></span></p>
            <p class="lead"> USD : $<span class="salary-usd"></span> </p>
                    </div>
                    <div class="col-6">
                        <h6>Salary on 6 mon: </h6>
            <p class="lead"> MXN : $<span class="new-salary-mxn"></span><span class="badge badge-info permon">+5% per mon</span></p>
            <p class="lead"> USD : $<span class="new-salary-usd"></span></p>
                    </div>
                </div>
            </div>

            

            
            <hr class="my-3">
            <p class="lead"><strong> Address : </strong><span class="address-info"></span></p>
            <p class="lead"><strong> State : </strong><span class="state-info"></span></p>
            <p class="lead"><strong> City : </strong><span class="city-info"></span> </p>
            <p class="lead"><strong> Phone : </strong><span class="phone-info"></span> </p>
            <p class="lead"><strong> Email : </strong><span class="email-info"></span> </p>
            <button type="button" class="btn btn-secondary modalclose" data-dismiss="modal">Close</button>
          </div>
      </div>
    </div>
  </div>
@endsection
@section('js')
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })

        function confirmarEliminar(id) {
            var route = "{{ route('delete') }}";
            var x = confirm("Delete Employee ?");
            if (x)
                
                $.ajax({
                    type: 'POST', 
                    url: route, 
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    }, 
                    dataType: 'json',
                    success: function(respuestaAjax) {
                        $(".employe" + id).remove();

                        $("#mensajeEliminar").show();

                        setTimeout(() => {
                            $("#mensajeEliminar").hide();
                        }, 2000);
                    },
                    error: function(err) {
                        alert('hubo un error en la petición');
                        alert(err.status);
                    }
                });

            else
                return false;
        }


        function changeStatus(id) {
            $(".employe" + id + " .chatatos").removeClass('d-none');
            $(".employe" + id + " .chatatos").addClass('d-block');
            $(".employe" + id + " .chatatus").removeClass('d-block');
            $(".employe" + id + " .chatatus").addClass('d-none');

            var route = "{{ route('status') }}";
            $.ajax({
                    type: 'POST', 
                    url: route, 
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    }, 
                    dataType: 'json',
                    success: function(respuestaAjax) {
                        if (respuestaAjax.success === true) {
                            
                                if (respuestaAjax.status === true) {
                        
                        $(".employe" + id + " .statusInactive").removeClass('d-block');
                        $(".employe" + id + " .statusInactive").addClass('d-none');
                        
                        $(".employe" + id + " .chatatos").removeClass('d-none');
                        $(".employe" + id + " .chatatos").addClass('d-block');
                        
                        setTimeout(() => {
                        $(".employe" + id + " .chatatos").removeClass('d-block');
                        $(".employe" + id + " .chatatos").addClass('d-none');
                        $(".employe" + id + " .chatatus").removeClass('d-none');
                        $(".employe" + id + " .chatatus").addClass('d-block');
                        $(".employe" + id + " .statusActive").removeClass('d-none');
                        $(".employe" + id + " .statusActive").addClass('d-block');
                    }, 2000);
                    }
                    else {
                        $(".employe" + id + " .statusActive").removeClass('d-block');
                        $(".employe" + id + " .statusActive").addClass('d-none');
                        setTimeout(() => {
                        $(".employe" + id + " .chatatos").removeClass('d-block');
                        $(".employe" + id + " .chatatos").addClass('d-none');
                        $(".employe" + id + " .chatatus").removeClass('d-none');
                        $(".employe" + id + " .chatatus").addClass('d-block');
                        $(".employe" + id + " .statusInactive").removeClass('d-none');
                        $(".employe" + id + " .statusInactive").addClass('d-block');
                    }, 2000);
                    }
                }
                         
                    },
                    error: function(err) {
                        alert('hubo un error en la petición');
                        alert(err.status);
                    }
                });
        }
        function viewInfo(id){
            var route = "{{ route('view') }}";
                
                $.ajax({
                    type: 'POST', 
                    url: route, 
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}',
                    }, 
                    dataType: 'json',
                    success: function(respuestaAjax) {
                        if (respuestaAjax.success === true) {
                            $('.name-info').html(respuestaAjax.data.name);
                            $('.code-info').html(respuestaAjax.data.code);
                            $('.salary-mxn').html(respuestaAjax.data.salary_pesos);
                            $('.salary-usd').html(respuestaAjax.data.salary_dollar);
                            $('.new-salary-mxn').html(respuestaAjax.data.salary_pesos_to_6_mon.toFixed(2));
                            $('.new-salary-usd').html(respuestaAjax.data.salary_dollar_to_6_mon.toFixed(2));
                            $('.address-info').html(respuestaAjax.data.address);
                            $('.state-info').html(respuestaAjax.data.state);
                            $('.city-info').html(respuestaAjax.data.city);
                            $('.phone-info').html(respuestaAjax.data.phone);
                            $('.email-info').html(respuestaAjax.data.email);
                            $('#staticBackdrop').modal('show');
                        }
                    },
                    error: function(err) {
                        alert(err.status);
                    }
                });

        }
    </script>
@endsection

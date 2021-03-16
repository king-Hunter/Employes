@extends('layouts.app')
@section('css')
    <style>
        .fa{
            font-size: 2rem;
        }
        .view{
            color: blue;
        }
        .edit{
            color:grey;
        }
        .delete{
            color: red;
        }
    </style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                @if ($item['info']>0)
                <table class="table table-hover">
                    <thead>
                      <tr class="card-header">
                        <th style="width:10%">#</th>
                        <th style="width:60%">Name</th>
                        <th style="width:20%">Code</th>
                        <th style="width:20%">Status</th>
                        <th style="width:10%"></th>
                        <th style="width:10%"></th>
                        <th style="width:10%"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row">1</th>
                        <td>Juan</td>
                        <td>MX</td>
                        <td><span type="button" class="badge badge-success" data-toggle="tooltip" data-placement="top" title="Change Status on click">Active</span>
                        </td>
                        <td> <span type="button" class="view fa fa-eye"></span></td>
                        <td><span type="button" class="edit fa fa-edit"></span></td>
                        <td><span type="button" class="delete fa fa-trash"></span></td>
                      </tr>
                    </tbody>
                  </table>    
                @endif
                <div class="card-body text-center">
                    <h1><span class="badge badge-info">Sorry! </span> You don't have data </h1>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script>
        $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
    </script>
@endsection
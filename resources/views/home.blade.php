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
            <div class="col-12">
                <div class="card">
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
                                <tr>
                                    <th scope="row">{{ $i->code }}</th>
                                    <td>{{ $i->name }}</td>
                                    <td>${{ $i->salary_pesos }}</td>
                                    <td>${{ $i->salary_dollar }}</td>
                                    <td>{{ $i->email }}</td>
                                    <td><span type="button" class="badge badge-success" data-toggle="tooltip"
                                            data-placement="top" title="Change Status on click">
                                            @if ($i->active)
                                            Active    
                                            @else
                                            Inactive
                                            @endif
                                        </span></td>
                                    <td>
                                        <span type="button" class=" view fa fa-eye"></span>
                                    </td>
                                    <td>
                                        <span type="button" class="edit fa fa-edit"></span>
                                    </td>
                                    <td>
                                        <span type="button" class=" delete fa fa-trash"></span>
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
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })

    </script>
@endsection

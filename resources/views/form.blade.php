@extends('layouts.app')
@section('css')

@endsection
@section('content')
    <div class="container">
        <!--{ { $item['today']->dato } }-->
        <form method="POST" action="{{ route('postAdd') }}" role="form">
            {{ csrf_field() }}
            <div class="row justify-content-start">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label>Name </label>
                        <input type="text" name="name" id="name" class="form-control input-sm" placeholder=" Jhon Connor"
                            required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xs-5 col-sm-5 col-md-5">
                    <div class="row ">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Coin type</label>
                                <select class="form-control " name="typeSalary" id="typeSalary">
                                    <option>MXN</option>
                                    <option>USD</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Salary </label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" name="salary" id="salary" class="form-control input-sm"
                                        placeholder="34.56" required>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-xs-5 col-sm-5 col-md-5">
                    <div class="form-group">
                        <label>Email </label>
                        <input type="email" name="email" id="email" class="form-control input-sm"
                            placeholder="example@one.tom" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-start">
                <div class="col-xs-7 col-sm-7 col-md-7">
                    <div class="form-group">
                        <label>Adress </label>
                        <input type="text" name="address" id="address" class="form-control input-sm"
                            placeholder="152 Kerry Lake" required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-start">
                <div class="col-xs-4 col-sm-4 col-md-4">
                    <div class="form-group">
                        <label>State </label>
                        <input type="text" name="state" id="state" class="form-control input-sm"
                            placeholder="Yorkshire del Oeste" required>
                    </div>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4">
                    <div class="form-group">
                        <label>City </label>
                        <input type="text" name="city" id="city" class="form-control input-sm" placeholder="Inglaterra"
                            required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-start">
                <div class="col-xs-5 col-sm-5 col-md-5">
                    <div class="form-group">
                        <label>Phone </label>
                        <input type="tel" name="phone" id="phone" class="form-control input-sm" placeholder="(241) 445-4652"
                            required>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <input type="submit" value="Save" class="btn btn-success btn-block">
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <a type="button" href="{{ route('home') }}" class="btn btn-info btn-block"> Back </a>
                </div>
            </div>
        </form>
    </div>

@endsection
@section('js')
    <script type='text/javascript'
        src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <script type='text/javascript'>
        $(window).on('load', function() {
            var phones = [{
                "mask": "(###) ###-####"
            }, {
                "mask": "(###) ###-##############"
            }];
            $('#phone').inputmask({
                mask: phones,
                greedy: false,
                definitions: {
                    '#': {
                        validator: "[0-9]",
                        cardinality: 1
                    }
                }
            });
            var name = [{
                "mask": "#############"
            }, {
                "mask": "####################"
            }, {
                "mask": "###########################"
            }, {
                "mask": "###################################"
            }, {
                "mask": "###############################################"
            }];
            $('#name').inputmask({
                mask: name,
                greedy: false,
                definitions: {
                    '#': {
                        validator: "[A-Z,a-z, ]",
                        cardinality: 1
                    }
                }
            });
        });
        const campoNumerico = document.getElementById('salary');

        campoNumerico.addEventListener('keydown', function(evento) {
            const teclaPresionada = evento.key;
            const teclaPresionadaEsUnNumero =
                Number.isInteger(parseInt(teclaPresionada));

            const sePresionoUnaTeclaNoAdmitida =
                teclaPresionada != 'ArrowDown' &&
                teclaPresionada != 'ArrowUp' &&
                teclaPresionada != 'ArrowLeft' &&
                teclaPresionada != 'ArrowRight' &&
                teclaPresionada != 'Backspace' &&
                teclaPresionada != 'Delete' &&
                teclaPresionada != 'Enter' &&
                !teclaPresionadaEsUnNumero;
            const comienzaPorCero =
                campoNumerico.value.length === 0 &&
                teclaPresionada == 0;

            if (sePresionoUnaTeclaNoAdmitida || comienzaPorCero) {
                evento.preventDefault();
            }

        });

    </script>
@endsection

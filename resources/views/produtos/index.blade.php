@extends('layouts.index')
@section('conteudo')
    <div class="container">
        <!--- MENSAGEM DE CONFIRMAÇÂO DE SUCESSO --->
        @if (session('msg'))
            <div class="alert alert-success" role="alert">
                {{ session('msg') }}
            </div>
        @endif
        <!--- FIM MENSAGEM DE CONFIRMAÇÂO DE SUCESSO --->

        <div class="container">
            <div class="row">
                <!-- BEGIN SEARCH RESULT -->
                <div class="col-md-12">
                    <div class="grid search">
                        <div class="grid-body">
                            <div class="row">
                                <!-- BEGIN FILTERS -->
                                <div class="col-md-3">
                                    <h2 class="grid-title"><i class="fa fa-filter"></i> Filtros</h2>
                                    <hr>
                                    <form action="" method="get">
                                        <!-- BEGIN FILTER BY CATEGORY -->
                                        <h4>Status NF:</h4>
                                        <div class="checkbox">
                                            <label><input type="checkbox" class="icheck" value="1" name="filter">
                                                Enviada</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" class="icheck" value="0" name="filter">
                                                Pendênte</label>
                                        </div>
                                        <!-- END FILTER BY CATEGORY -->

                                        <div class="padding"></div>
                                        <!-- END FILTER BY DATE -->

                                        <div class="padding"></div>
                                </div>
                                <!-- END FILTERS -->
                                <!-- BEGIN RESULT -->
                                <div class="col-md-9">
                                    <h2> Produtos <img src="data:image/png;base64,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"
                                            width="150px" class="float-right"></h2>
                                    <hr>
                                    <!-- BEGIN SEARCH INPUT -->


                                    <div class="input-group">

                                        <input type="text" name="orderid" class="form-control" placeholder="Nome Produto">
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="submit"><i
                                                    class="fa fa-search"></i></button>
                                        </span>

                                    </div>
                                    </form>
                                    <!-- END SEARCH INPUT -->

                                    <div class="padding mt-3"></div>
                                    <!-- BEGIN TABLE RESULT -->
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <th>Nome</th>
                                            <th>Preço</th>
                                            <th>Prazo (Dias)</th>
                                            <th>Código</th>
                                            <th>Plano</th>
                                            <tbody>
                                                @foreach ($data as $produto)
                                                    <tr>
                                                        <td>{{$produto->name}}</td>
                                                        <td>R$: {{number_format($produto->price, 2, '.', ',')}}</td>
                                                        <td>{{$produto->prazo}}</td>
                                                        <td>{{$produto->codigo}}</td>
                                                        <td>{{$produto->plan}}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- END TABLE RESULT -->

                                    <!-- BEGIN PAGINATION -->
                                    <div class="d-flex">
                                        {{-- {!! $data->links() !!} --}}
                                    </div>
                                    <!-- END PAGINATION -->
                                </div>
                                <!-- END RESULT -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END SEARCH RESULT -->
            </div>
        </div>
    @endsection

@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12 dahsboard-column">
                    <section class="box-typical box-typical-dashboard lobipanel panel panel-default scrollable">
                        <header class="box-typical-header panel-heading">
                            <h3 class="panel-title">Sale Due Date</h3>
                        </header>
                        <div class="box-typical-body panel-body">
                            <table class="tbl-typical">
                                <thead>
                                <tr>
                                    <th>
                                        <div>Status</div>
                                    </th>
                                    <th>
                                        <div>Clients</div>
                                    </th>
                                    <th align="center">
                                        <div>Orders#</div>
                                    </th>
                                    <th align="center">
                                        <div>Due Date</div>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div><!--.box-typical-body-->
                    </section><!--.box-typical-dashboard-->
                </div><!--.col-->
                <div class="col-xl-12 dahsboard-column">
                    <section class="box-typical box-typical-dashboard lobipanel panel panel-default scrollable">
                        <header class="box-typical-header panel-heading">
                            <h3 class="panel-title">Orders Due Date</h3>
                        </header>
                        <div class="box-typical-body panel-body">
                            <table class="tbl-typical">
                                <thead>
                                <tr>
                                    <th>
                                        <div>Status</div>
                                    </th>
                                    <th>
                                        <div>Clients</div>
                                    </th>
                                    <th align="center">
                                        <div>Sale#</div>
                                    </th>
                                    <th align="center">
                                        <div>Due Date</div>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div><!--.box-typical-body-->
                    </section><!--.box-typical-dashboard-->
                </div><!--.col-->
            </div>
        </div><!--.container-fluid-->
    </div><!--.page-content-->
@endsection
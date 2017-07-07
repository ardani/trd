@extends('layouts.app')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="box-typical box-typical-padding">
                <div class="row">
                    <fieldset class="form-group col-md-3">
                        <label class="form-control-label">No Production <span class="text-danger">*</span></label>
                        <input id="no-production" type="text" name="no" class="form-control" readonly
                               value="{{ $model->no }}">
                    </fieldset>
                    <fieldset class="form-group col-md-3">
                        <label class="form-control-label">No PO</label>
                        <input type="text" readonly class="form-control" value="{{$model->sale_order->no}}">
                    </fieldset>
                    <fieldset class="form-group col-md-2 pull-md-right">
                        <label class="form-control-label">Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control daterange" name="created_at"
                               value="{{$model->created_at->format('d/m/Y')}}" readonly>
                    </fieldset>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <fieldset class="form-group">
                            <label class="form-control-label">Note</label>
                            <p class="text-danger" style="font-weight: bold">{{$model->sale_order->note}}</p>
                        </fieldset>
                    </div>
                    <hr class="hr-form"/>
                    <div class="col-md-12">
                        <table id="listItemOrder" class="display table table-bordered" cellspacing="0" width="100%"
                               style="margin-bottom: 10px">
                            <thead>
                            <tr>
                                <th width="10%">Code</th>
                                <th>Product Name</th>
                                <th width="10%">Qty</th>
                                <th width="10%">Units</th>
                                <th width="10%">Status</th>
                                <th width="15%">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($model->sale_order->transactions as $transaction)
                                <tr>
                                    <td>{{$transaction->product->code}}</td>
                                    <td>{{$transaction->product->name .' - '.$transaction->desc}}</td>
                                    <td>{{abs($transaction->qty)}}</td>
                                    <td>{{$transaction->units}}</td>
                                    <td class="status">{{$transaction->status ? 'finish' : '-'}}</td>
                                    <td>
                                        @if($transaction->product->category_id == 2)
                                            <button type="button" data-name="{{$transaction->product->name}}"
                                                    data-id="{{$transaction->id}}"
                                                    data-url="{{url('productions/actions/detail')}}"
                                                    class="btn btn-sm btn-success setActive">choose
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <h4>Product : <span id="product-selected">-</span></h4>
                        <input type="hidden" name="product_selected"/>
                    </div>
                    <hr class="hr-form"/>
                    <div class="form-wrapper" style="display: none">
                        <div class="col-md-12">
                            <table id="table-productions-details" class="display table table-bordered"
                                   cellspacing="0" width="100%"
                                   style="margin-bottom: 10px">
                                <thead>
                                <tr>
                                    <th width="10%">Code</th>
                                    <th>Product Name</th>
                                    <th width="10%">Units</th>
                                    <th width="10%">Qty</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($model->transactions->isEmpty())
                                    <tr class="empty-row">
                                        <td colspan="4" class="text-center">empty data</td>
                                    </tr>
                                @else
                                    @foreach($model->transactions as $transaction)
                                        <tr>
                                            <td>{{$transaction->product->code}}</td>
                                            <td>{{$transaction->product->name}}</td>
                                            <td>{{$transaction->units}}</td>
                                            <td>{{abs($transaction->qty)}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <a href="{{url('productions')}}" class="btn btn-success">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/html" id="row-production">
        <tr>
            <td data-content="code"></td>
            <td data-content="name"></td>
            <td data-content="units"></td>
            <td data-content="qty"></td>
        </tr>
    </script>
@endsection
@section('scripts')
    <script src="{{asset('js/productions.js')}}"></script>
@endsection
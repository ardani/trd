<ul>
    <li>
        <span class="label label-{{$model->payment_method->name == 'credit' ? 'danger' : 'success'}}">
            {{$model->payment_method->name}}</span>
        @if ($model->paid_status)
            <span class="label label-success">paid</span>
        @endif
    </li>
    @if ($model->payment_method_id == 2 && $model->paid_status != 1)
        <li>expire :{{ is_null($model->paid_until_at) ? '-' : $model->paid_until_at->format('d/m/Y')}}</li>
    @endif
</ul>
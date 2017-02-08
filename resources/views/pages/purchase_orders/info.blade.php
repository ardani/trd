<ul>
    <li>
        <span class="label label-{{$model->payment_method->name == 'credit' ? 'danger' : 'success'}}">
            {{$model->payment_method->name}}</span>
    </li>
    <li>expire :{{ is_null($model->paid_until_at) ? '-' : $model->paid_until_at->format('d/m/Y')}}</li>
</ul>
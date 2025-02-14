<table class="table table-responsive">
    <thead>
    <tr>
        <th>{{__('SN')}}</th>
        <th>{{__('Title')}}</th>
        <th>{{__('Action')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($routes as $route)
        @if($route["permission"] ?? false)
            @can($route["permission"])
                <tr>
                    <td><strong>{{ $loop->iteration }}</strong></td>
                    <td>
                        {{ $route["title"] }}
                        <p><small class="text-info">{{ $route["description"] }}</small></p>
                    </td>
                    <td><a href="{{ $route["route"] }}" class="btn-profile btn-bg-1"> {{ __('Edit Template') }}</a></td>
                </tr>
            @endcan
        @endif
    @endforeach
    </tbody>
</table>

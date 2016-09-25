<div>
    <h1> {{ ucfirst($type) }} stats for {{$link->long_url}}</h1>
    <div id="stats-chart" class="@if($type === 'day') daily @endif"></div>
    <script>
        @if($type === 'day')
            Morris.Bar({
                element: 'stats-chart',
                data: [
                        @foreach ($stats as $s)
                    {'date': '{{ $s->date }}', 'clicks': {{ $s->clicks }}},
                    @endforeach
                ],
                xkey: 'date',
                ykeys: ['clicks'],
                labels: ['Clicks']
            });
        @else
            Morris.Donut({
                element: 'stats-chart',
                data: [
                        @foreach ($stats as $s)
                    {'label': '{{ $s->label }}', 'value': {{ $s->clicks }}},
                    @endforeach
                ]
            });
        @endif
    </script>
</div>
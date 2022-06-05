@extends('layouts.app')

@section('content')
    <div class="container pb-5">
        <div class="echart"></div>
        Super/Admin
    </div>
@endsection
@push('scripts')
    <script>
        const salesByCategory = Echarts.init(document.querySelector('.echart'));

        const echartsData = $.ajax({
            url: '{{route('home.echart')}}', // provide correct url
            type: 'get',
            dataType: 'JSON',

        }).done(
            chart_values => {
                console.log(chart_values); // take a peek on the values (browser console)

                let option = {
                    title: {
                        text: 'Referer of a Website',
                        subtext: 'Fake Data',
                        left: 'center'
                    },
                    tooltip: {
                        trigger: 'item'
                    },
                    legend: {
                        orient: 'vertical',
                        left: 'left'
                    },
                    series: [
                        {
                            name: 'Access From',
                            type: 'pie',
                            radius: '50%',
                            min: 5,
                            data: chart_values,

                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }
                    ]
                };
        salesByCategory.setOption(option);
            }
        );
    </script>
@endpush

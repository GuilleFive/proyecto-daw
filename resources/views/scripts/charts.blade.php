<script>
    const salesByCategory = Echarts.init(document.querySelector('.categories-pie'));
    const salesByDay = Echarts.init(document.querySelector('.days-bar'));

    salesByCategory.showLoading({backgroundColor: 'blue'});
    salesByDay.showLoading({backgroundColor: 'blue'});

    $.ajax({
        url: '{{route('home.salesByCategory')}}',
        type: 'get',
        dataType: 'JSON',

    }).done(
        data => {
            let option = {
                title: {
                    text: 'Categoría mas vendida',
                    left: 'center',
                    textStyle: {
                        color: '#dee2e6',
                        fontSize: '1.2em'
                    }
                },
                series: [
                    {
                        name: 'Access From',
                        type: 'pie',
                        radius: ['40%', '70%'],
                        avoidLabelOverlap: true,
                        label: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            label: {
                                show: true,
                                fontSize: '1.5em',
                                fontWeight: 'bold',
                                formatter: function (d) {
                                    return `${d.name}\n\n${d.value}%`;
                                }
                            },
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        },
                        labelLine: {
                            show: false
                        },
                        data: data,
                    }
                ]
            };

            salesByCategory.hideLoading();
            salesByCategory.setOption(option);
        }
    );

    $(window).on('resize', function () {
        if (salesByCategory != null) {
            salesByCategory.resize();
        }
    });

    $.ajax({
        url: '{{route('home.salesByDay')}}',
        type: 'get',
        dataType: 'JSON',

    }).done(
        data => {
            let option = {
                title: {
                    text: 'Media de ventas por día',
                    left: 'center',
                    textStyle: {
                        color: '#dee2e6',
                        fontSize: '1.2em'
                    }
                },
                xAxis: {
                    type: 'category',
                    data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                },
                tooltip: {
                    trigger: 'item'
                },
                yAxis: {
                    type: 'value'
                },
                series: [
                    {
                        data: data,
                        type: 'bar',
                        showBackground: true,
                        backgroundStyle: {
                            color: 'rgba(180, 180, 180, 0.2)'
                        }
                    }
                ]
            };
            setTimeout(()=>{
                salesByDay.hideLoading();
                salesByDay.setOption(option);
            }, 4000);

        }
    )
    ;

    $(window).on('resize', function () {
        if (salesByDay != null) {
            salesByDay.resize();
        }
    });

    const topSales = document.querySelector('.top-sales');

    $.ajax(
        {
            url: '{{route('home.topSales')}}',
            type: 'get',
        }
    ).done(
        data => {
            topSales.innerHTML = data;
        }
    )
</script>


@extends('layouts.app')

@section('content')
<div class="main-content">
    <section class="section">

        <x-breadcrumb :menus="$menus" :title="$title" />

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="mt-0 mb-3 d-flex justify-content-between align-items-center">
                                <div class="section-title ">{{ $title }}</div>
                            </div>
                            <h2>Welcome <span class="txt-name txt-capitalize">{{ auth()->user()->name }}</span> </h2>
                        </div>
                        @role("USER")
                            <div>
                                <h3>Total Your Money : <span class="txt-your-money"></span></h3>
                            </div>
                        @endrole

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <h4>Chart</h4>
                    <div id="chart"></div>
                </div>
            </div>
        </div>
    </section>


    @role("USER")
    <section>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="mt-0 mb-3 d-flex justify-content-between align-items-center">
                        <div class="section-title ">My Waste</div>
                    </div>
                    <table id="main-table" class="table-hover table"></table>
                </div>
            </div>
        </div>
    </section>
    @endrole

</div>
@endsection

@push('scripts')
    <script>
        $(document).ready( async function () {
            await getData();
            if (GLOBAL_ROLE_NAME == "USER") {
                await drawDatatable()
            }
        });

        async function getData() {
            const url = `${APP_URL}/api/dashboard`;
            const method = "GET";
            const response = await request(url, method);
            console.log('response : ', response);

            if (response.status) {
                $(".txt-your-money").html(rupiahFormat(response.data.totalYourMoney))
                generateChart(response.data);
            }
        }


        function generateChart(data) {

            const d = data.count.map(i => i.count);

            var options = {
                series: [{
                    name: 'Waste',
                    // data: [2.3, 3.1, 4.0, 10.1, 4.0, 3.6, 3.2, 2.3, 1.4, 0.8, 0.5, 0.2]
                    data: d
                }],
                chart: {
                    height: 350,
                    type: 'bar',
                },
                plotOptions: {
                    bar: {
                        borderRadius: 10,
                        dataLabels: {
                            position: 'top', // top, center, bottom
                        },
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val;
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '12px',
                        colors: ["#304758"]
                    }
                },

                xaxis: {
                    // categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    categories: data.categories,
                    position: 'top',
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    crosshairs: {
                        fill: {
                            type: 'gradient',
                            gradient: {
                                colorFrom: '#D8E3F0',
                                colorTo: '#BED1E6',
                                stops: [0, 100],
                                opacityFrom: 0.4,
                                opacityTo: 0.5,
                            }
                        }
                    },
                    tooltip: {
                        enabled: true,
                    }
                },
                yaxis: {
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false,
                    },
                    labels: {
                        show: false,
                        formatter: function (val) {
                            return val;
                        }
                    }
                },
                title: {
                    text: '',
                    floating: true,
                    offsetY: 330,
                    align: 'center',
                    style: {
                        color: '#444'
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        }

        function drawDatatable(){
            $("#main-table").DataTable({
                destroy: true,
                "pageLength": 10,
                "processing": true,
                "serverSide": true,
                "searching": true,
                // "responsive": true,
                "order": [[0, 'desc']],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All'],
                ],
                "ajax": {
                    "url": `${APP_URL}/api/dashboard/datatables`,
                    "headers": { 'X-CSRF-TOKEN': CSRF_TOKEN },
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        // d.filter = {
                        //     name : $('#input-filter-name').val(),
                        // }
                    }
                },
                "columns": [
                    { title : 'No', data: null, width: '5%', searchable:false, orderable: false, render : (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                    // { title : 'Code', data: 'code', name: 'code', render : (data) => data || '' },
                    {
                        title : 'Waste', data: 'waste', name: null, render : (data) => {
                            if (data) {
                                return data.name
                            }
                            return '-'
                        }
                    },
                    { title : 'Qty', data: 'qty', name: 'qty', render : (data) => data || '-' },
                    { title : 'Price', data: 'price', name: 'price', render : (data) => data ? rupiahFormat(data) : '-' },
                    { title : 'Total Price', data: 'total_price', name: 'total_price', render : (data) => data ? rupiahFormat(data) : '-' },
                ],
            })
        }
    </script>
@endpush

@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/c3/c3.min.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/morris/morris-0.4.3.min.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content ">
    <div class="row">
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">as of Today</span>
                    <h5>Total Documents</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{(count($documents))}}</h1>
                    {{-- <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> --}}
                    <small>&nbsp;</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">as of Today</span>
                    <h5>New Requests</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($change_requests->where('created_at','>=',date('Y-m-d')))+count($copy_requests->where('created_at','>=',date('Y-m-d')))}}</h1>
                    {{-- <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> --}}
                    <small>&nbsp;</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">as of Today</span>
                    <h5>Pending</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($change_requests->where('status','Pending'))+count($copy_requests->where('status','Pending'))}}</h1>
                    {{-- <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> --}}
                    <small>&nbsp;</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">as of this Month ({{date('M. Y')}})</span>
                    <h5>Approved</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($change_requests->where('status','Approved')) + count($copy_requests->where('status','Approved'))}}</h1>
                    {{-- <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> --}}
                    <small>&nbsp;</small>
                </div>
            </div>
        </div>
    </div>
    <div class="row ">
        {{-- <div class="col-lg-8 stretch-card">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Search Documents</h5>
                </div>
                <div class="ibox-content stretch-card">

                    <div class="search-form">
                        <form action="{{url('/home')}}" method="get">
                            <div class="input-group">
                                <input type="text" placeholder="Document Name/Code" name="search"  class="form-control input-lg" required>
                                <div class="input-group-btn">
                                    <button class="btn btn-lg btn-primary" type="submit">
                                        Search
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="search-result">
                        <h3><a href="#">Document 1 v2</a> <span class="label label-primary">Public</span></h3>
                        Last Updated by <a href="#" class="search-link">Employee 1</a>
                        <p>
                            Date Effective : January 1, 2023 <br>
                            Company : W Group Inc.
                            
                        </p>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="search-result">
                        <h3><a href="#">Document 2 v1</a> <span class="label label-danger">Private</span></h3>
                        Last Updated by <a href="#" class="search-link">Employee 3</a>
                        <p>
                            Date Effective : January 1, 2023 <br>
                            Company : W Group Inc.
                            
                        </p>
                    </div>
                    <div class="hr-line-dashed"></div>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Documents Library </h5>

                </div>
                <div class="ibox-content">
                    <div>
                        <div id="stocked"></div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
  
    <div class='row'>
        @if((auth()->user()->role == "Administrator") || (auth()->user()->role == "Management Representative") || (auth()->user()->role == "Business Process Manager"))
    
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Requests</h5>
                    </div>
                    <div class="ibox-content">
                        <div id="morris-bar-chart"></div>
                    </div>
                </div>
            </div>
            
        @endif
        @if(count($permits) != 0)
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Permits and licenses ({{count($permits)}}) </h5>
                   
                </div>
                <div class="ibox-content">
                    <div id="morris-donut-chart" ></div>
                </div>
            </div>
        </div>
        @endif
        @if((auth()->user()->role == "Administrator") || (auth()->user()->role == "Management Representative") || (auth()->user()->role == "Business Process Manager"))
    
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Document Requests Status this {{date('Y')}}</h5>
                </div>
                <div class="ibox-content">
                    <div>
                        <div id="pie"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/chartJs/Chart.min.js') }}"></script>

<script src="{{ asset('login_css/js/plugins/morris/raphael-2.1.0.min.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/morris/morris.js') }}"></script>


<script src="{{ asset('login_css/js/plugins/d3/d3.min.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/c3/c3.min.js') }}"></script>
<script>
    var departments = {!! json_encode(($departments)->toArray()) !!};
    var for_renewal = {!! json_encode((count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))))) !!};
    var over_due = {!! json_encode((count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d'))))) !!};
    var active = {!! json_encode((count($permits->where('expiration_date','!=',null)->where('expiration_date','>=',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))))) !!};
    var no_expiration = {!! json_encode((count($permits->where('expiration_date','==',null)))) !!};
    var types = {!! json_encode(($categories->pluck('name'))->toArray()) !!};
    var obsoletes = {!! json_encode(($departments->pluck('obsoletes_count'))->toArray()) !!};
    var months = {!! json_encode(($months)) !!};

    var pending = {!!json_encode(($yearChangeRequests->where('status','Pending')->count()))!!}
    var approved = {!!json_encode(($yearChangeRequests->where('status','Approved')->count()))!!}
    var declined = {!!json_encode(($yearChangeRequests->where('status','Declined')->count()))!!}
    $(function() {
            Morris.Donut({
            element: 'morris-donut-chart',
            data: [
                
                { label: "For Renewal", value: for_renewal-over_due },
                { label: "Overdue", value: over_due },
                { label: "Active", value: active },
                { label: "No Expiration", value: no_expiration } ],
            resize: true,
            colors: ['#FFA500','#f44336', '#54cdb4','#1ab394'],
        });
        var aaa= months;
        Morris.Bar({
        element: 'morris-bar-chart',
        data: aaa,
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Change Requests', 'Copy Requests'],
        hideHover: 'auto',
        resize: true,
        barColors: ['#1ab394', '#cacaca'],
    });
        
    });

    $(document).ready(function(){
        var types_names = {!! json_encode(($categories)->toArray()) !!};
        var colors ={};
        var column = ['x'];
 
    for(y=0;y<departments.length;y++)
    {
        column.push(departments[y].code+"("+departments[y].documents_count+")");
    }
    var types = [];
    var  columns= [column];
    for(i =0;i< types_names.length;i++)
    {
        type_column=[types_names[i].code];
        for(z=0;z<departments.length;z++)
        {
            var doc = departments[z].documents;
            var count = doc.filter(o => o.category === types_names[i].name);
            type_column.push(count.length)
           
        }
        
        columns.push(type_column);
        colors[types_names[i].code] = types_names[i].color;
        types.push(types_names[i].code);
    }
    final_types = [types];
    console.log(columns);
        c3.generate({
                bindto: '#stocked',
                data:{
                    x : 'x',
                    columns: columns,
                    colors:colors,
                    type: 'bar',
                    groups: final_types,
                   
                },
                axis: {
                    x: {
                        show: true,
                        type: 'categorized', // this is needed to load string x value
                    },
                    y2: {
                        show: true,
                        label: 'Counts'
                    },
                    y: {
                        show: true,
                        label: 'Counts'
                    },
                }
            });

            c3.generate({
                bindto: '#pie',
                data:{
                    columns: [
                        ['Approved', approved],
                        ['Declined', declined],
                        ['Pending', pending]
                    ],
                    colors:{
                        Approved: '#54cdb4',
                        Declined: '#f44336',
                        Pending: '#BABABA',
                    },
                    type : 'pie'
                }
            });
        

        $('.locations').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                
            ]

        });

    });
  

</script>
@endsection


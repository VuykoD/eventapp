@extends ('app')

@section('page-header')
    <h2 class="content-header-title">{{ trans('menus.backend.access.settings_event_type.management') }}</h2>
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="/assets/css/plugins/tables/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/assets/css/sweetalert.css">

@endsection

@section('javascript')

<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.material.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/v/dt/dt-1.10.16/datatables.min.js"></script>
<script src="/assets/js/plugins/tables/datatable/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="/assets/js/sweetalert.min.js" type="text/javascript"></script>



 

<script>
$myVocabulary = {!!$Event_type!!}
function list()   { 
$.each($myVocabulary,function(index,value) {
  var $index1=index+1
  $(".rows").append(
    '<tr role="row" class="odd">'+
    "<td>" +value['name']+"</td>"+
    "<td>" +value['code']+"</td>"+
    "<td>" +value['group_size']+"</td>"+
    "<td>" +value['duration']+"</td>"+
    "<td>" +value['additional']+"</td>"+
    "<td>"+
       "<btn class='btn btn-xs btn-primary edit' id='"+value['id']+"id"+"'>"+
          "<i class='icon-pencil2' data-toggle='tooltip' data-placement='top' title='Edit'></i>"+
        "</btn> "+
       "<a class='btn btn-xs btn-danger delete' id='"+value['id']+"id_d"+"'>"+
          "<i class='icon-trash-o' data-toggle='tooltip' title='Delete'></i>"+
        "</a>"+
    "</td>"+
    "</tr>"
    );
})
}
list()
</script>

<script>

$(".delete").click(function () {
    $('.sweet-overlay').css('display','block')
    $('.showSweetAlert').css('display','block')
    var $string= $(this).attr("id");
    $id=$string.replace('id_d','')
    $.each($myVocabulary,function(index,value) {
      if (value['id']==$id) {$data=value}
    }) 
})

$(".cancel").click(function () {
    $('.sweet-overlay').css('display','none')
    $('.showSweetAlert').css('display','none')
}) 

$(".confirm").click(function () {
    $(".cancel").click()
    location.href = 'settings_event_type?id='+$id;   
})

$(".edit").click(function () {
    var $string= $(this).attr("id");
    $id=$string.replace('id','')
    console.log($id) 
    $.each($myVocabulary,function(index,value) {
      if (value['id']==$id) {$data=value}
    })

    location.href = 'settings_event_type/create?id='+$id;   
})
 
</script>

 <script type="text/javascript" class="init">
    

$(document).ready(function() {
    $('#example').DataTable( {
        //  "columnDefs": [
        //     {
        //         "targets": [ 0 ],
        //         "visible": false
        //     }
        // ]
    } );
} );

</script>

@endsection

@section('content')

    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ trans('menus.backend.access.settings_event_type.all') }}</h4>
                    <div class="heading-elements">
                        @include('partials.admin.event-type-header-buttons')
                    </div>
                </div>

                <div class="card-body collapse in">
                    <div class="card-block card-dashboard table-responsive">                      
                        <table id="example" class="table table-condensed compact table-hover">
                            <thead>
                                <tr>
                                    <th>{{ trans('labels.backend.access.settings_event_type.table.name') }}</th>
                                    <th>{{ trans('labels.backend.access.settings_event_type.table.code') }}</th>
                                    <th>{{ trans('labels.backend.access.settings_event_type.table.group_size') }}</th>
                                    <th>{{ trans('labels.backend.access.settings_event_type.table.duration') }}</th>
                                    <th>{{ trans('labels.backend.access.settings_event_type.table.additional') }}</th>
                                    <th>{{ trans('labels.general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody Class="rows">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

@include ('partials.event.alert') 
@endsection

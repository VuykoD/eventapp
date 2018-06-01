@extends ('app')

@section('page-header')
    <h2 class="content-header-title">{{ trans('menus.backend.access.vendor_invite.management') }}</h2>
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
$myVocabulary = {!!$Vendor_invite!!}
function list()   { 
$.each($myVocabulary,function(index,value) {
  var $index1=index+1
  $(".rows").append(
    '<tr role="row" class="'+$index1+'row odd">'+
    "<td>" +value['uid']+"</td>"+
    "<td>" +value['action']+"</td>"+
    "<td>" +value['vendor_id']+"</td>"+
    "<td>" +value['event_id']+"</td>"+
    "<td>" +value['invited_at']+"</td>"+
    "<td>" +value['responsed_at']+"</td>"+
    "<td>"+
       "<btn class='btn btn-xs btn-primary edit' id='"+value['id']+"id"+"'>"+
          "<i class='icon-search4' data-toggle='tooltip' data-placement='top' title='View'></i>"+
        "</btn> "+
    "</td>"+
    "</tr>"
    );
})
}
list() 





// $(".edit").click(function () {
//     var $string= $(this).attr("id");
//     $id=$string.replace('id','')
//     $.each($myVocabulary,function(index,value) {
//       if (value['id']==$id) {$data=value}
//     })

//     location.href = 'settings_vendor_category/create?id='+$id;   
// })

</script>


 <script type="text/javascript" class="init">
    
$(document).ready(function() {
    $('#example').DataTable( {
    } );
} );

    </script>

@endsection

@section('content')
    <section class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ trans('menus.backend.access.vendor_invite.all') }}</h4>
                </div>

                <div class="card-body collapse in">
                    <div class="card-block card-dashboard table-responsive">                      
                        <table id="example" class="table table-condensed compact table-hover">
                            <thead>
                                <tr>
                                    <th>UID</th>
                                    <th>Status</th>
                                    <th>Vendor_id</th>
                                    <th>Event_id</th>
                                    <th>Invited at</th>
                                    <th>Confirmed at</th>
                                    <th>actions</th>
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



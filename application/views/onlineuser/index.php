<div class="content-wrapper">
  <section class="content-header">
    <h1> Pembeli Online </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Pembeli Online</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <div class="row">
              <div class="col-md-6">
                <select onchange="loadtable(this.value)" id="select-status" style="width: 150px" class="form-control">
                  <option value="ENABLE">ENABLE</option>
                  <option value="DISABLE">DISABLE</option>
                </select>
              </div>
            </div>
          </div>
          <div class="box-body">
            <div class="show_error"></div>
            <div class="table-responsive">
              <div id="load-table"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<script type="text/javascript">
  function loadtable(status) {
    var table = '<table class="table table-bordered" id="mytable">'+
    '     <thead>'+
    '     <tr class="bg-success">'+
    '       <th style="width:20px">No</th>'+
    '       <th width="70px" align="center">Photo Pembeli</th>'+
    '       <th>Name</th>'+
    '       <th>Email</th>'+
    '       <th>Phone</th>'+
    '       <th style="width:150px">Status</th>'+
    '     </tr>'+
    '     </thead>'+
    '     <tbody>'+
    '     </tbody>'+
    ' </table>';
    $("#load-table").html(table)
    var t = $("#mytable").dataTable({
      initComplete: function() {
        var api = this.api();
        $('#mytable_filter input')
        .off('.DT')
        .on('keyup.DT', function(e) {
          if (e.keyCode == 13) {
            api.search(this.value).draw();
          }
        });
      },
      oLanguage: {
        sProcessing: "loading..."
      },
      processing: true,
      serverSide: true,
      ajax: {"url": "<?= base_url('onlineuser/json?status=') ?>"+status, "type": "POST"},
      columns: [{"data": "id","orderable": false},
      {"data": "url"},
      {"data": "name"},
      {"data": "email"},
      {"data": "phone"},
      {"data": "status"}],
      order: [[2, 'asc']],
      columnDefs : [
      { 
        targets : [1],
        render : function (data, type, row, meta) {
          var htmls = '<object data="'+row['url']+'" style="width: 70px;"><img src="https://www.library.caltech.edu/sites/default/files/styles/headshot/public/default_images/user.png?itok=1HlTtL2d" type="image/png" alt="example" style="width: 70px;"></object>';
          return htmls;
        }
      },
      { 
        targets : [5],
        render : function (data, type, row, meta) {
          if(row['status']=='ENABLE'){
            var htmls = '<a href="<?= base_url('master/Tbl_user/status/') ?>'+row['id']+'/DISABLE">'+
            '    <button type="button" class="btn btn-sm btn-sm btn-success"><i class="fa fa-home"></i> ENABLE</button>'+
            '</a>';
          }else{
            var htmls = '<a href="<?= base_url('master/Tbl_user/status/') ?>'+row['id']+'/ENABLE">'+
            '    <button type="button" class="btn btn-sm btn-sm btn-danger"><i class="fa fa-home"></i> DISABLE</button>'+
            '</a>';
          }
          return htmls;
        }
      }],

      rowCallback: function(row, data, iDisplayIndex) {
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        $('td:eq(0)', row).html(index);
      }
    });
  }
  loadtable($("#select-status").val());
</script>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Transaksi</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Transaksi</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-right">
                                    <a href="<?= base_url('casir') ?>">
                                        <button type="button" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah Data Transaksi</button>
                                    </a>
                                </div>
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
<div class="modal fade bd-example-modal-sm" tabindex="-1" transaksi="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="modal-delete">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="upload-delete" action="<?= base_url('transaksi/delete') ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm delete</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idTransaksi" id="delete-input">
                    <p>Are you sure to delete this data?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger btn-send">Yes, Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function loadtable() {
        var table = '<table class="table table-bordered" id="mytable">' +
            '     <thead>' +
            '     <tr class="bg-success">' +
            '       <th style="width:20px">No</th>' +
            '<th>Pembeli Online</th>' +
            '<th>Pihak Admin</th>' +
            '<th>Kode</th>' +
            '<th>Total Transaksi</th>' +
            '<th>Alamat </th>' +
            '<th>Nama Pengirim</th>' +
            '<th>Nomor Terhubung</th>' +
            '<th>Status Transaksi</th>' +
            '<th>Catatan</th>' +
            '       <th></th>' +
            '     </tr>' +
            '     </thead>' +
            '     <tbody>' +
            '     </tbody>' +
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
            ajax: {
                "url": "<?= base_url('transaksi/json') ?>",
                "type": "POST"
            },
            columns: [{
                    "data": "idTransaksi",
                    "orderable": false
                },
                {
                    "data": "idUser"
                },
                {
                    "data": "idAdmin"
                },
                {
                    "data": "kodeTransaksi"
                },
                {
                    "data": "totalTransaksi"
                },
                {
                    "data": "alamatKirim"
                },
                {
                    "data": "nama_pengirim"
                },
                {
                    "data": "noHubungi"
                },
                {
                    "data": "statusTransaksi"
                },
                {
                    "data": "catatan"
                },
                {
                    "data": "view",
                    "orderable": false
                }
            ],
            order: [
                [1, 'asc']
            ],
            columnDefs: [{
                    targets: [1],
                    render: function(data, type, row, meta) {
                        if (row['idUser']) {
                            var htmls = row['idUser'];
                        } else {
                            var htmls = 'Tidak Tersedia!';
                        }
                        return htmls;
                    }
                },
                {
                    targets: [2],
                    render: function(data, type, row, meta) {
                        if (row['idAdmin']) {
                            var htmls = row['idAdmin'];
                        } else {
                            var htmls = '<span class="text-muted font-weight-normal font-italic d-block">Tidak Tersedia</span>';
                        }
                        return htmls;
                    }
                },
                {
                    targets: [3],
                    render: function(data, type, row, meta) {
                        var htmls = '<b>' + row['kodeTransaksi'] + '</b>';
                        return htmls;
                    }
                },
                {
                    targets: [4],
                    render: function(data, type, row, meta) {
                        var htmls = '<b>' + rupiah(row['totalTransaksi']) + '</b>';
                        return htmls;
                    }
                },
                {
                    targets: [8],
                    render: function(data, type, row, meta) {
                        if (row['statusTransaksi'] == 'WAITING_PAYMENT') {
                            var htmls = '<small class="label bg-yellow"><i class="fa fa-warning"> </i> Menunggu Pembayaran </small> <hr style="margin-top:5px; margin-bottom:5px">' +
                                '<div class="row" align="center">' +
                                '<button type="button" class="btn btn-send btn-approve btn-sm btn-sm btn-primary" onclick="approve(' + row['idTransaksi'] + ')"><i class="fa fa-check-circle"></i></button> ' +
                                '<button type="button" class="btn btn-send btn-reject btn-sm btn-sm btn-danger" onclick="reject(' + row['idTransaksi'] + ')"><i class="fa fa-ban"></i></button>' +
                                '</div>';
                        } else if (row['statusTransaksi'] == 'APPROVE') {
                            var htmls = '<small class="label bg-blue"><i class="fa fa-check"> </i> Pembayaran Selesai </small>';
                        } else if (row['statusTransaksi'] == 'EXPIRED') {
                            var htmls = '<small class="label bg-red"><i class="fa fa-ban"> </i> Kadarluarsa </small>';
                        } else {
                            var htmls = '<small class="label bg-red"><i class="fa fa-ban"> </i> Ditolak </small>';
                        }
                        return htmls;
                    }
                }
            ],
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

    function view(id) {
        location.href = "<?= base_url('transaksi/view/') ?>" + id;
    }

    function hapus(id) {
        $("#modal-delete").modal('show');
        $("#delete-input").val(id);
    }

    function rupiah(value) {
        var number_string = value.toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{1,3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

        return "Rp " + rupiah;
    }

    $("#upload-delete").submit(function() {
        event.preventDefault();
        var form = $(this);
        var mydata = new FormData(this);
        $.ajax({
            type: "POST",
            url: form.attr("action"),
            data: mydata,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $(".btn-send").addClass("disabled").html("<i class='la la-spinner la-spin'></i>  Processing...").attr('disabled', true);
                $(".show_error").slideUp().html("");
            },
            success: function(response, textStatus, xhr) {
                var str = response;
                if (str.indexOf("success") != -1) {
                    $(".show_error").hide().html(response).slideDown("fast");
                    $(".btn-send").removeClass("disabled").html('Yes, Delete it').attr('disabled', false);
                } else {
                    setTimeout(function() {
                        $("#modal-delete").modal('hide');
                    }, 1000);
                    $(".show_error").hide().html(response).slideDown("fast");
                    $(".btn-send").removeClass("disabled").html('Yes , Delete it').attr('disabled', false);
                    loadtable($("#select-status").val());
                }
            },
            error: function(xhr, textStatus, errorThrown) {}
        });
        return false;
    });

    function approve(id) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('transaksi/approve/') ?>" + id,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $(".btn-send").addClass("disabled").html("<i class='fa fa-spinner'></i>").attr('disabled', true);
                $(".show_error").slideUp().html("");
            },
            success: function(response, textStatus, xhr) {
                var str = response;
                if (str.indexOf("success") != -1) {
                    $(".show_error").hide().html(response).slideDown("fast");
                    $(".btn-approve").removeClass("disabled").html('<i class="fa fa-check-circle"></i> ').attr('disabled', false);
                    $(".btn-reject").removeClass("disabled").html('<i class="fa fa-ban"></i> ').attr('disabled', false);
                    location.reload();
                } else {
                    setTimeout(function() {
                        $("#modal-delete").modal('hide');
                    }, 1000);
                    $(".show_error").hide().html(response).slideDown("fast");
                    $(".btn-approve").removeClass("disabled").html('<i class="fa fa-check-circle"></i> ').attr('disabled', false);
                    $(".btn-reject").removeClass("disabled").html('<i class="fa fa-ban"></i> ').attr('disabled', false);
                }
            },
            error: function(xhr, textStatus, errorThrown) {}
        });
    }

    function reject(id) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('transaksi/reject/') ?>" + id,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $(".btn-send").addClass("disabled").html("<i class='fa fa-spinner'></i>").attr('disabled', true);
                $(".show_error").slideUp().html("");
            },
            success: function(response, textStatus, xhr) {
                var str = response;
                if (str.indexOf("success") != -1) {
                    $(".show_error").hide().html(response).slideDown("fast");
                    $(".btn-approve").removeClass("disabled").html('<i class="fa fa-check-circle"></i> ').attr('disabled', false);
                    $(".btn-reject").removeClass("disabled").html('<i class="fa fa-ban"></i> ').attr('disabled', false);
                    location.reload();
                } else {
                    setTimeout(function() {
                        $("#modal-delete").modal('hide');
                    }, 1000);
                    $(".show_error").hide().html(response).slideDown("fast");
                    $(".btn-approve").removeClass("disabled").html('<i class="fa fa-check-circle"></i> ').attr('disabled', false);
                    $(".btn-reject").removeClass("disabled").html('<i class="fa fa-ban"></i> ').attr('disabled', false);
                }
            },
            error: function(xhr, textStatus, errorThrown) {}
        });
    }
</script>
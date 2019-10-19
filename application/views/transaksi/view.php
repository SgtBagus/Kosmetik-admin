<div class="content-wrapper">
    <section class="content-header">
        <h1>Detail Transaksi <small><?= $transaksi['kodeTransaksi'] ?></small></h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Transaksi</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="show_error"></div>
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-12" style="margin-top: 15px">
                        <div class="box box-solid round">
                            <div class="box-header with-border">
                                <h3 class="box-title pull-left">Sisa Waktu</h3>
                                <h3 class="box-title pull-right">
                                    <?php if ($transaksi['statusTransaksi'] == "APPROVE") { ?>
                                        <span type="button" class="btn-primary btn-sm round">
                                            <i class="fa fa-check"></i> Di Terima
                                        </span>
                                    <?php } else if ($transaksi['statusTransaksi'] == "REJECT") { ?>
                                        <span type="button" class="btn-danger btn-sm round">
                                            <i class="fa fa-check"></i> Sudah Tidak ada
                                        </span>
                                    <?php } else if ($transaksi['statusTransaksi'] == "EXPIRED") { ?>
                                        <span type="button" class="btn-danger btn-sm round">
                                            <i class="fa fa-check"></i> Sudah Tidak ada
                                        </span>
                                    <?php } else { ?>
                                        <span type="button" class="btn-success btn-sm round">
                                            <i class="fa fa-check"></i> Masih Ada
                                        </span>
                                    <?php } ?>
                                </h3>
                            </div>
                            <div class="box-body" align="center">
                                <?php if ($transaksi['statusTransaksi'] == "APPROVE") { ?>
                                    <h1><b>DITERIMA</b></h1>
                                <?php } else if ($transaksi['statusTransaksi'] == "REJECT") { ?>
                                    <h1><b>DITOLAK</b></h1>
                                <?php } else { ?>
                                    <h1><b id="countDownKadarluasa">00 : 00 : 00</b></h1>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="box box-solid round">
                            <div class="box-header with-border">
                                <h3 class="box-title pull-left">Total Harga</h3>
                            </div>
                            <div class="box-body" align="center">
                                <h1><b>Rp <?= number_format($transaksi['totalTransaksi'], 0, ',', '.'); ?>,-</b></h1>
                            </div>
                        </div>
                        <div class="row" align="center">
                            <a href="<?= base_url('transaksi') ?>">
                                <button type="button" class="btn btn-reject btn-sm btn-sm btn-info">
                                    <i class="fa fa-archive"></i> Data Transaksi
                                </button>
                            </a>
                            <?php if ($transaksi['statusTransaksi'] == 'WAITING_PAYMENT') { ?>
                                <div class="row" style="margin-top:15px">
                                    <button type="button" class="btn btn-send btn-approve btn-sm btn-sm btn-primary" onclick="approve(<?= $transaksi['idTransaksi'] ?>)">
                                        <i class="fa fa-check-circle"></i> Terima Pembayaran
                                    </button>
                                    <button type="button" class="btn btn-send btn-reject btn-sm btn-sm btn-danger" onclick="reject(<?= $transaksi['idTransaksi'] ?>)">
                                        <i class="fa fa-ban"></i> Tolak Pembayaran
                                    </button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12" style="margin-top: 15px">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-solid round">
                                    <div class="box-header with-border">
                                        <h3 class="box-title pull-left">Rincian Invoice</h3>
                                        <h3 class="box-title pull-right">
                                            <span id="cetak" type="button" class="btn-primary btn-sm round">
                                                <i class="fa fa-print"></i> Cetak Invoice
                                            </span>
                                        </h3>
                                    </div>
                                    <div class="box-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Kode Invoice</th>
                                                <td><b><?= $transaksi['kodeTransaksi'] ?></b></td>
                                            </tr>
                                            <tr>
                                                <th>Nama Pembeli</th>
                                                <td><?= $user['name'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>Email Pembeli</th>
                                                <td><?= $user['email'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>No Telpon Pembeli</th>
                                                <td><?= $user['phone'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Mengajukan Pembayaran</th>
                                                <td><?= date('Y-m-d H:i:s', strtotime($transaksi['transaksiDibuat'])) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Kadarluasa</th>
                                                <td><?= date('Y-m-d H:i:s', strtotime($transaksi['transaksiEXP'])) ?></td>
                                            </tr>
                                            <tr>
                                                <th>Total Produk Dibeli</th>
                                                <td><b><?= $transaksi['totalBeliProduk']; ?></b></td>
                                            </tr>
                                            <tr>
                                                <th>Total Jumlah Barang</th>
                                                <td><b><?= $transaksi['jumlahBarang']; ?></b></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12" style="margin-top: 15px">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-solid round">
                                    <div class="box-header with-border">
                                        <h3 class="box-title pull-left">Detail Pengirim</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Nama Pengirim</label>
                                            <input type="text" class="form-control" value="<?= $transaksi['nama_pengirim'] ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Nomor Pengirim</label>
                                            <input type="text" class="form-control" value="<?= $transaksi['noHubungi'] ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Alamat Pengirim</label>
                                            <textarea class="form-control" rows="5px" readonly><?= $transaksi['alamatKirim'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12" style="margin-top: 15px">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-solid round">
                            <div class="box-header with-border">
                                <h3 class="box-title pull-left">Detail Produk</h3>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th></th>
                                                <th>Qty</th>
                                                <th>Harga Produk</th>
                                                <th>Total harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            foreach ($invoice_produk as $row) {
                                                $photo = $this->mymodel->selectDataone('file', array('table_id' => $row['idProduk'], 'table' => 'm_produk'));
                                                $produk = $this->mymodel->selectDataone('m_produk', array('idProduk' => $row['idProduk']));
                                                $kategori = $this->mymodel->selectDataone('m_kategori', array('idKategori' => $produk['idKategori']));
                                                $desk = strlen($produk["deskProduk"]) > 50 ? substr($produk["deskProduk"], 0, 50) . "..." : $produk["deskProduk"];
                                                $rowStock = $this->mymodel->selectWithQuery("SELECT count(idStok) as rowstock from produk_stok WHERE idProduk = " . $row['idProduk'] . " AND statusStok = 'TERSEDIA' AND status = 'ENABLE'");
                                                ?>
                                                <tr>
                                                    <td align="center">
                                                        <img src="<?= $photo['url'] ?>" alt="" width="100px" height="100px" class="img-fluid rounded shadow-sm">
                                                        <input type="hidden" name="dtd[<?= $i ?>][id]" value="<?= $row['id'] ?>">
                                                    </td>
                                                    <td>
                                                        <h4><?= $produk['namaProduk'] ?></h4>
                                                        <span class="text-muted font-weight-normal font-italic d-block">Kategori: <?= $kategori['namaKategori'] ?>
                                                            <br><small><?= $desk ?></small>
                                                        </span>
                                                    </td>
                                                    <td align="center">
                                                        <b><?= $row['jumlahProduk'] ?></b>
                                                    </td>
                                                    <td>
                                                        <b>
                                                            Rp <?= number_format($produk['hargajProduk'], 0, ',', '.') ?>
                                                        </b>
                                                    </td>
                                                    <td>
                                                        <b id="totalharga-<?= $row['id'] ?>">
                                                            Rp <?= number_format($row['totalHarga'], 0, ',', '.') ?>
                                                        </b>
                                                    </td>
                                                </tr>
                                            <?php $i++;
                                            } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td align="right" colspan="2">Total Qty Produk</td>
                                                <td align="center"><b><?= $transaksi['jumlahBarang'] ?></b></th>
                                                <td align="right">Subtotal</td>
                                                <td align="center"><b>Rp <?= number_format($transaksi['totalTransaksi'], 0, ',', '.') ?></b></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12" style="margin-top: 15px">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-solid round">
                            <div class="box-header with-border">
                                <h3 class="box-title pull-left">Bukti Pembayaran</h3>
                            </div>
                            <div class="box-body">
                                <div class="show_error_image"></div>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="50%" align="center">
                                            <?php if ($file_tranksaksi) { ?>
                                                <img src="<?= base_url() . $file_tranksaksi['dir'] ?>" width="100%" height="100px" id="preview_image">
                                                <br><br>
                                                <div align="center">
                                                    <a href="<?= base_url() . $file_tranksaksi['dir'] ?>" target="_blank">
                                                        <button type="button" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> Lihat Gambar</button>
                                                    </a>
                                                </div>
                                            <?php } else { ?>
                                                <img src="https://getuikit.com/v2/docs/images/placeholder_600x400.svg" width="100%" height="250px" id="preview_image">
                                            <?php } ?>
                                        </th>
                                        <td>
                                            <form action="<?= base_url('transaksi/sumbitImage/') . $transaksi['idTransaksi'] ?>" method="POST" id="upload-create">
                                                <input type="file" class="file" id="imageFile" style="display: none;" name="file" accept="image/x-png,image/jpeg,image/jpg" />
                                                <button type="button" class="btn btn-sm btn-info" id="btnFile"><i class="fa fa-file"></i> Browser File</button>
                                                <p class="help-block">Foto yang diupload disarankan berukuran 70px x 70px dan memiliki format PNG, JPG, atau JPEG</p>
                                                <button type="submit" class="btn btn-sm btn-primary btn-send"><i class="fa fa-save"></i> Simpan Gambar</button>
                                            </form>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12" style="margin-top: 15px">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-solid round">
                            <div class="box-header with-border">
                                <h3 class="box-title pull-left">Catatan Tambahan</h3>
                            </div>
                            <div class="box-body">
                                <div class="show_error_note"></div>
                                <form action="<?= base_url('transaksi/sumbitNote/') . $transaksi['idTransaksi'] ?>" method="POST" id="uploadnote">
                                    <div class="form-group">
                                        <textarea class="form-control" name="note"><?= $transaksi['catatan'] ?></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary btn-note"><i class="fa fa-save"></i> Simpan Catatan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    var countDownDate = new Date("<?= $transaksi['transaksiEXP'] ?>").getTime();
    var x = setInterval(function() {
        var now = new Date().getTime();
        var distance = countDownDate - now;
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        document.getElementById("countDownKadarluasa").innerHTML = hours + " : " + minutes + " : " + seconds;
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("countDownKadarluasa").innerHTML = "Kadaluarsa";
        }
    }, 1000);

    $('#cetak').click(function() {
        window.print();
    });

    $("#upload-create").submit(function() {
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
                $(".show_error_image").slideUp().html("");
            },

            success: function(response, textStatus, xhr) {
                var str = response;
                if (str.indexOf("success") != -1) {
                    $(".show_error_image").hide().html(response).slideDown("fast");
                    setTimeout(function() {
                        location.reload();
                    }, 1000);

                    $(".btn-send").removeClass("disabled").html('<i class="fa fa-save"></i> Simpan Gambar').attr('disabled', false);
                } else {
                    $(".show_error_image").hide().html(response).slideDown("fast");
                    $(".btn-send").removeClass("disabled").html('<i class="fa fa-save"></i> Simpan Gambar').attr('disabled', false);
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log(xhr);
                $(".btn-send").removeClass("disabled").html('<i class="fa fa-save"></i> Simpan Gambar').attr('disabled', false);
                $(".show_error_image").hide().html(xhr).slideDown("fast");
            }
        });
        return false;
    });

    $("#uploadnote").submit(function() {
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
                $(".btn-note").addClass("disabled").html("<i class='la la-spinner la-spin'></i>  Processing...").attr('disabled', true);
                $(".show_error_note").slideUp().html("");
            },

            success: function(response, textStatus, xhr) {
                var str = response;
                if (str.indexOf("success") != -1) {
                    $(".show_error_note").hide().html(response).slideDown("fast");
                    setTimeout(function() {
                        location.reload();
                    }, 1000);

                    $(".btn-note").removeClass("disabled").html('<i class="fa fa-save"></i> Simpan Catatan').attr('disabled', false);
                } else {
                    $(".show_error_note").hide().html(response).slideDown("fast");
                    $(".btn-note").removeClass("disabled").html('<i class="fa fa-save"></i> Simpan Catatan').attr('disabled', false);
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log(xhr);
                $(".btn-note").removeClass("disabled").html('<i class="fa fa-save"></i> Simpan Catatan').attr('disabled', false);
                $(".show_error_note").hide().html(xhr).slideDown("fast");
            }
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
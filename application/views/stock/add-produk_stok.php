<form method="POST" action="<?= base_url('stock/store') ?>" id="upload-create" enctype="multipart/form-data">
    <div class="show_error"></div>
    <div class="form-group">
        <label for="form-idProduk">Produk</label>
        <select name="dt[idProduk]" class="form-control select2">
            <?php
            $m_produk = $this->mymodel->selectWhere('m_produk', null);
            foreach ($m_produk as $m_produk_record) {
                echo "<option value=" . $m_produk_record['idProduk'] . ">" . $m_produk_record['namaProduk'] . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="form-kodeBarang">Kode Barang</label>
        <input type="text" class="form-control" id="form-kodeBarang" placeholder="Masukan Kode Barang" name="dt[kodeBarang]">
    </div>
    <hr>
    <button type="submit" class="btn btn-primary btn-send"><i class="fa fa-save"></i> Save</button>
    <button type="reset" class="btn btn-danger"><i class="fa fa-refresh"></i> Reset</button>
</form>
<script type="text/javascript">
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
                form.find(".show_error").slideUp().html("");
            },
            success: function(response, textStatus, xhr) {
                var str = response;
                if (str.indexOf("success") != -1) {
                    form.find(".show_error").hide().html(response).slideDown("fast");
                    setTimeout(function() {
                        $("#load-table").html('');
                        loadtable($("#select-status").val());
                        $("#modal-form").modal('hide');
                    }, 1000);
                    $(".btn-send").removeClass("disabled").html('<i class="fa fa-save"></i> Save').attr('disabled', false);
                } else {
                    form.find(".show_error").hide().html(response).slideDown("fast");
                    $(".btn-send").removeClass("disabled").html('<i class="fa fa-save"></i> Save').attr('disabled', false);
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                $(".btn-send").removeClass("disabled").html('<i class="fa fa-save"></i> Save').attr('disabled', false);
                form.find(".show_error").hide().html(xhr).slideDown("fast");
            }
        });
        return false;
    });
</script>
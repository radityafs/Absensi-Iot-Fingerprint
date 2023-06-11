
<!-- Begin Page Content -->

<div class="container-fluid">
<link href="https://livedemo.mbahcoding.com/assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet">

            <!-- Grow In Utility -->

<h1 class="h3 mb-2 text-gray-800">Data Absensi</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">

  <div class="card-header py-3">

  <?php
if (isset($_POST['awal']) && isset($_POST['akhir'])) {
    echo '<a class="text-right-mb3 font-weight-bold btn btn-primary float-right" href="' . $pdf . '">Download Rekapan</a>';
} else {
    echo '<button type="button" id="add_button" onclick="add_ijin()" class="text-right-mb3 font-weight-bold btn btn-primary float-right">Tambah Ijin</button>';
}
?>

  <form action="<?=base_url('Report/absensi/?kelas=' . urlencode($_GET['kelas']))?>" method="POST" class="form-inline md-form mr-auto mb-4">
  <input class="form-control mr-sm-2" type="text" name="daterange" placeholder="" aria-label="Search">
  <input type="hidden" value="" name="awal"/>
  <input type="hidden" value="" name="akhir"/>
  <input type="submit" name="searchbtn" id="searchbtn" value="Search" class="btn btn-primary" />

</form>

  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
          <th style="width:50px;">#</th>
          <th>NAMA</th>
          <th style="width:200px;">TANGGAL</th>
          <th>WAKTU</th>
          <th>KETERANGAN</th>
          <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
        <?php $i = 1;?>
        <?php foreach ($menu as $dt): ?>
        <tr>
        <td><?=$i?></td>
        <td><?=$dt['nama']?></td>
        <td><?=$dt['tanggal']?></td>
        <td><?=$dt['waktu']?></td>
        <?php
if ($dt['status'] == 1 && $dt['status_pulang'] == 0) {
    $KET = 'MASUK';
} else if ($dt['status'] == 2 && $dt['status_pulang'] == 0) {
    $KET = 'TERLAMBAT MASUK';
} else if ($dt['status'] == 3 && $dt['status_pulang'] == 0) {
    $KET = 'SAKIT';
} else if ($dt['status'] == 4 && $dt['status_pulang'] == 0) {
    $KET = 'IJIN';
} else if ($dt['status_pulang'] == 1) {
    $KET = 'SUDAH PULANG';
}
?>
        <td><?=$KET?></td>

        <td>
        <a class="btn btn-sm float-left btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person(<?=$dt['id']?>)"><i class="glyphicon glyphicon-pencil"></i> Edit</a>        </td>
        <?php $i++;?>
        </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
        <th style="width:50px;">#</th>
          <th>NAMA</th>
          <th style="width:200px;">TANGGAL</th>
          <th>WAKTU</th>
          <th>KETERANGAN</th>
          <th>ACTION</th>
          </tr>
        </tfoot>

      </table>
    </div>
  </div>
</div>


<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript">

var save_method; //for save method string
var table;
var waktu = new Date()
var datepicker;
var date_start;
datepicker = $('input[name="daterange"]').daterangepicker({
    opens: 'middle',
    locale: {
      format: 'DD/MM/YYYY'
    }

}, function(start, end, label) {

    $('[name="awal"]').val(start.format('DD/MM/YYYY'));
    $('[name="akhir"]').val(end.format('DD/MM/YYYY'));

});



function add_ijin()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Tambah Absensi'); // Set Title to Bootstrap modal title
    $('[name="waktu"]').val(waktu.getHours() + ":" + waktu.getMinutes());
    $('[name="waktu"]').prop("disabled",true);
    $('[name="status"]').html('<option value="3">Sakit</option><option value="4">Ijin</option>')
    $('[name="nama"]').change(function(){
    var nama = $('[name="nama"]').val();
    if(nama != ''){
    $.ajax({
    url:"<?=base_url('Report/fetch_nama');?>",
    method:"POST",
    data:{nama:nama},
    success:function(data)
    {
     $('[name="nama"]').val(data);
    }
    });
}
    });

}


function edit_person(id)
{

    save_method = 'update';
    usr_id = id;
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('Report/ajax_edit/') ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="nama"]').val(data.nama);
            $('[name="tanggal"]').val(data.tanggal);
            $('[name="waktu"]').val(data.waktu);
            $('[name="status"]').val(data.status);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Absensi'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}



function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('Report/ajax_add') ?>";

    } else {
        url = "<?php echo site_url('Report/ajax_update') ?>/" + usr_id;
    }
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                location.reload(true);
            }

            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Check Data Anda Kembali');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h3 class="modal-title">Person Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md">NAMA</label>
                            <div class="col-md">
                                <input name="nama" placeholder="" value="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md">TANGGAL</label>
                            <div class="col-md">
                                <input name="tanggal" data-provide="datepicker" data-date-format="dd/mm/yyyy" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md">WAKTU</label>
                            <div class="col-md">
                                <input name="waktu" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md">Keterangan</label>
                            <div class="col-md">
                            <select name="status" class="custom-select">
                            <option value="1">Sudah Pulang</option>
                            <option value="2">Dispen / Ijin</option>
                            </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>
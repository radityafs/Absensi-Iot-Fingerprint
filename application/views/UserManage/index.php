
<!-- Begin Page Content -->

<div class="container-fluid">


<h1 class="h3 mb-2 text-gray-800">Data Siswa</h1>


<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">

    <button type="button" id="add_button" onclick="add_person()" class="text-right-mb3 font-weight-bold btn btn-primary float-right">Add User</button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
          <th>NISN</th>
          <th>Name</th>
          <th>Kelas</th>
          <th>ID Finger</th>
          <th>Nomor Wali</th>
          <th style="width:125px;">Action</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
          <tr>
          <th>NISN</th>
          <th>Name</th>
          <th>Kelas</th>
          <th>ID Finger</th>
          <th>Nomor Wali</th>
          <th style="width:125px;">Action</th>
          </tr>
        </tfoot>

      </table>
    </div>
  </div>
</div>


<script src="https://livedemo.mbahcoding.com/assets/jquery/jquery-2.1.4.min.js"></script>
<script src="https://livedemo.mbahcoding.com/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#dataTable').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('UserManage/ajax_list') ?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],

    });

    //datepicker


});



function add_person()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
    $('[name="kelas"]').change(function(){
    var kelas = $('[name="kelas"]').val();
    if(kelas != ''){
    $.ajax({
    url:"<?=base_url('UserManage/fetch_database');?>",
    method:"POST",
    data:{kelas:kelas},
    success:function(data)
    {
     $('[name="id_finger"]').html(data);
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
        url : "<?php echo site_url('UserManage/ajax_edit/') ?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            console.log(data);
            $('[name="NISN"]').val(data.NISN);
            $('[name="Nama"]').val(data.nama);
            $('[name="kelas"]').change(function(){
    var kelas = $('[name="kelas"]').val();
    if(kelas != ''){
    $.ajax({
    url:"<?=base_url('UserManage/fetch_database');?>",
    method:"POST",
    data:{kelas:kelas},
    success:function(data)
    {
     $('[name="id_finger"]').html(data);
    }
    });
}
    });
            $('[name="id_finger"]').val(data.id_finger);
            $('[name="nomor_orang_tua"]').val(data.nomor_orang_tua);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Person'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('UserManage/ajax_add') ?>";

    } else {
        url = "<?php echo site_url('UserManage/ajax_update') ?>/" + usr_id;
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {
          //  console.log(data);
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
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

function delete_person(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('UserManage/ajax_delete') ?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
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
                            <label class="control-label col-md">NISN</label>
                            <div class="col-md">
                                <input name="NISN" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md">Nama</label>
                            <div class="col-md">
                                <input name="Nama" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md">Kelas / Device</label>
                            <div class="col-md">
                            <select name="kelas" class="custom-select">
                            <option value="" selected>Select Device</option>
                            <?php
foreach ($devices as $row) {
    echo '<option value="' . $row['nama_devices'] . '">' . $row['nama_devices'] . '</option>';
}
?>
                            </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md">ID FINGER</label>
                            <div class="col-md">
                                <select name="id_finger" class="custom-select">
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md">Nomor Wali</label>
                            <div class="col-md">
                                <input name="nomor_orang_tua" placeholder="" class="form-control" type="text">
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
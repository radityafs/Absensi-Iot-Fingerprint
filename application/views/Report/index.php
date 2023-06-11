
<!-- Begin Page Content -->

<div class="container-fluid">

            <!-- Grow In Utility -->

<h1 class="h3 mb-2 text-gray-800">Data Absensi</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">

  <div class="card-header py-3">

</form>

  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
          <th style="width:50px;">#</th>
          <th>KELAS</th>
          <th style="width:200px;">SISWA</th>
          <th style="width:50px;">Action</th>
          </tr>
        </thead>
        <tbody>
        <?php $i = 1;?>
        <?php foreach ($menu as $dt): ?>
        <tr>
        <td><?=$i?></td>
        <td><?=$dt['kelas']?></td>
        <td><?=$dt['siswa']?></td>
        <td>
        <a class="btn btn-sm float-left btn-primary" href="<?=base_url('Report/absensi/?kelas=' . urlencode($dt['kelas']));?>">View</a>
        </td>
        <?php $i++;?>
        </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
          <th style="width:25px;" >#</th>
          <th>KELAS</th>
          <th style="width:200px;">SISWA</th>
          <th style="width:50px;">Action</th>
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
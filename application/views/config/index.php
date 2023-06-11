    <!-- Begin Page Content -->
    <?=$this->session->flashdata('message')?>

    <div class="container-fluid">


<!-- Page Heading -->
<h1 class="h3 mb-1 text-gray-800">Pengaturan</h1>

<!-- Content Row -->
<div class="row">

  <!-- Grow In Utility -->
  <div class="col-lg-6">

    <div class="card position-relative">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Waktu</h6>
      </div>
      <div class="card-body">
      <form class="waktu" method="POST" action=<?=base_url('config/waktu')?>>

        <div class="small mb-1">Waktu Datang :</div>
              <input class="form-control" type="text" name="waktudatang" id="waktudatang" placeholder="" value="<?=$waktu['waktu_masuk_awal'] . '-' . $waktu['waktu_masuk_akhir']?>" required="">
              <br>
              <div class="small mb-1">Waktu Pulang :</div>
              <input class="form-control" type="text" name="waktupulang" id="waktupulang" placeholder="" value="<?=$waktu['waktu_pulang_awal'] . '-' . $waktu['waktu_pulang_akhir']?>" required="">      </div>
              <button class="btn btn-style-1 btn-primary" type="submit" data-toast="" data-toast-position="topRight" data-toast-type="success" data-toast-icon="fe-icon-check-circle" data-toast-title="Success!" data-toast-message="Your profile updated successfuly.">Submit</button>
    </div>

  </div>

  <!-- Fade In Utility -->
  <div class="col-lg-6">

    <div class="card position-relative">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Notifikasi</h6>
      </div>
      <div class="card-body">

        <div class="small mb-1">Notifikasi akan dikirimkan pada saat :</div>
        <nav class="navbar navbar-expand navbar-light bg-light mb-4">
          <a class="navbar-brand">Masuk</a>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
            <input type="checkbox" <?php if ($waktu['notifikasi_masuk'] == 1) {echo 'checked';}?> id="toggle-masuk" data-toggle="toggle" data-style="ios">
            </li>
          </ul>
        </nav>
        <nav class="navbar navbar-expand navbar-light bg-light mb-4">
          <a class="navbar-brand">Pulang</a>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
            <input type="checkbox" <?php if ($waktu['notifikasi_pulang'] == 1) {echo 'checked';}?> data-toggle="toggle" id="toggle-pulang" data-style="ios">
            </li>
          </ul>
        </nav>
        <nav class="navbar navbar-expand navbar-light bg-light mb-4">
          <a class="navbar-brand">Terlambat</a>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
            <input type="checkbox" <?php if ($waktu['notifikasi_terlambat'] == 1) {echo 'checked';}?> data-toggle="toggle" id="toggle-terlambat" data-style="ios">

            </li>
          </ul>
        </nav>
        <nav class="navbar navbar-expand navbar-light bg-light mb-4">
          <a class="navbar-brand">Tidak Hadir</a>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
            <input type="checkbox" <?php if ($waktu['notifikasi_alfa'] == 1) {echo 'checked';}?> data-toggle="toggle" id="toggle-alfa" data-style="ios">

            </li>
          </ul>
        </nav>
        <nav class="navbar navbar-expand navbar-light bg-light mb-4">
          <a class="navbar-brand">Ijin / Dispen</a>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
            <input type="checkbox" <?php if ($waktu['notifikasi_dispen'] == 1) {echo 'checked';}?> data-toggle="toggle" id="toggle-dispen" data-style="ios">

            </li>
          </ul>
        </nav>


      </div>
    </div>

  </div>

</div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<style>
  .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; }
</style>
<script src="https://livedemo.mbahcoding.com/assets/jquery/jquery-2.1.4.min.js"></script>
<script src="https://livedemo.mbahcoding.com/assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<script>
$(document).ready(function() {
  $('#toggle-masuk').change(function(){
    var checkStatus = this.checked ? 1 : 0;
    $.post( "<?=base_url('config/notifikasi');?>", { detail: 'notifikasi_masuk', status: checkStatus })
  });
  $('#toggle-pulang').change(function(){
    var checkStatus = this.checked ? 1 : 0;
    $.post( "<?=base_url('config/notifikasi');?>", { detail: 'notifikasi_pulang', status: checkStatus })
  });
  $('#toggle-terlambat').change(function(){
    var checkStatus = this.checked ? 1 : 0;
    $.post( "<?=base_url('config/notifikasi');?>", { detail: 'notifikasi_terlambat', status: checkStatus })
  });

  $('#toggle-alfa').change(function(){
    var checkStatus = this.checked ? 1 : 0;
    $.post( "<?=base_url('config/notifikasi');?>", { detail: 'notifikasi_alfa', status: checkStatus })
  });
  $('#toggle-dispen').change(function(){
    var checkStatus = this.checked ? 1 : 0;
    $.post( "<?=base_url('config/notifikasi');?>", { detail: 'notifikasi_dispen', status: checkStatus })
  });
});


</script>
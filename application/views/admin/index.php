<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800"><?=$title?></h1>
<p class="mb-4">Data ini menunjukan data Presensi siswa hari ini Tanggal <?=date('d-m-Y');?> </a>.</p>
<div class="row">

<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card border-left-success shadow h-100 py-2">
<div class="card-body">
<div class="row no-gutters align-items-center">
<div class="col mr-2">
<div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kehadiran Siswa</div>
<div class="h5 mb-0 font-weight-bold text-gray-800"><?=$kehadiran;?></div>
</div>
<div class="col-auto">
<i class="fas fa-calendar fa-2x text-gray-300"></i>
</div>
</div>
</div>
</div>
</div>

<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card border-left-primary shadow h-100 py-2">
<div class="card-body">
<div class="row no-gutters align-items-center">
<div class="col mr-2">
<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Siswa Terlambat</div>
<div class="h5 mb-0 font-weight-bold text-gray-800"><?=$terlambat;?></div>
</div>
<div class="col-auto">
<i class="fas fa-user-clock fa-2x text-gray-300"></i>
</div>
</div>
</div>
</div>
</div>
<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card border-left-info shadow h-100 py-2">
<div class="card-body">
<div class="row no-gutters align-items-center">
<div class="col mr-2">
<div class="text-xs font-weight-bold text-info text-uppercase mb-1">Siswa Ijin</div>
<div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
</div>
<div class="col-auto">
<i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
</div>
</div>
</div>
</div>
</div>

<!-- Pending Requests Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
<div class="card border-left-danger shadow h-100 py-2">
<div class="card-body">
<div class="row no-gutters align-items-center">
<div class="col mr-2">
<div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Tidak Hadir</div>
<div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
</div>
<div class="col-auto">
<i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
</div>
</div>
</div>
</div>
</div>
</div>


<h1 class="h3 mb-2 text-gray-800">Tabel Absensi</h1>
<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Data Absensi</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Waktu</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>

<?php foreach ($query as $list) {
    echo '<tr>';
    echo '<td>' . $list['id'] . '</td>';
    echo '<td>' . $list['nama'] . '</td>';
    echo '<td>' . $list['devices'] . '</td>';
    echo '<td>' . $list['waktu'] . '</td>';
    if ($list['status'] == 1) {
        echo '<td>Datang Tepat Waktu</td>';
    } else if ($list['status'] == 2) {
        echo '<td>Datang Terlambat</td>';
    } else if ($list['status'] == 3) {
        echo '<td>Pulang</td>';
    } else {
        echo '<td>Error</td>';
    }
    echo '</tr>';
}?>
        </tbody>
        <tfoot>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Waktu</th>
            <th>Keterangan</th>
          </tr>
        </tfoot>

      </table>
    </div>
  </div>
</div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<!-- Begin Page Content -->
<div class="container-fluid">


<h1 class="h3 mb-2 text-gray-800">Data Devices</h1>
<?=form_error('nama_devices', '<div class="alert alert-danger" role="alert">
', '</div>');?>
<?=form_error('key_devices', '<div class="alert alert-danger" role="alert">
', '</div>');?>
<?=form_error('mode_devices', '<div class="alert alert-danger" role="alert">
', '</div>');?>
<?=form_error('is_active', '<div class="alert alert-danger" role="alert">
', '</div>');?>
<?=$this->session->flashdata('message')?>

<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="text-right-mb3 font-weight-bold btn btn-primary float-right" data-toggle="modal" data-target="#ModalAddUser">Add Devices</h6>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>#</th>
            <th>Devices Name</th>
            <th>Key Devices</th>
            <th>Mode Devices</th>
            <th>Active</th>
            <th style="width:125px;">Action</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
          <th>#</th>
            <th>Devices Name</th>
            <th>Key Devices</th>
            <th>Mode Devices</th>
            <th>Active</th>
            <th style="width:125px;">Action</th>
          </tr>
        </tfoot>
        <tbody>
        <?php $i = 1;?>

        <?php foreach ($menu as $dt): ?>
        <?php if ($dt['mode_devices'] == 1) {
    $mode = "Read";
} else {
    $mode = "Add";
}?>
        <?php if ($dt['is_active'] == 1) {
    $isactive = "Active";
} else {
    $isactive = "Disabled";
}?>

          <tr>
            <td><?=$i;?></td>
            <td><?=$dt['nama_devices']?></td>
            <td><?=$dt['key_devices']?></td>
            <td><?=$mode;?></td>
            <td><?=$isactive;?></td>
            <td>

<a href="" class="btn btn-sm float-left btn-primary" data-toggle="modal" data-target="#ModalEditUser<?=$dt['id'];?>">Edit</a>
<a href="<?=base_url('device/DeleteDevice/' . $dt['id'])?>" class="btn btn-sm float-right btn-danger" onclick="return confirm('Are You Sure ?')">Delete</a>
            </td>
          </tr>
          <?php $i++;?>
        <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Button trigger modal -->

<!-- Modal -->
<?php foreach ($menu as $dts):
    $id = $dts['id'];
    $namedevice = $dts['nama_devices'];
    $keydevices = $dts['key_devices'];
    $modedevices = $dts['mode_devices'];
    $isactivedvc = $dts['is_active'];
    ?>

							<div class="modal fade" id="ModalEditUser<?=$id;?>" tabindex="-1" role="dialog" aria-labelledby="EditUserModalLabel" aria-hidden="true">
							  <div class="modal-dialog" role="document">
							    <div class="modal-content">
							      <div class="modal-header">
							        <h5 class="modal-title" id="EditUserModalLabel">Edit Devices</h5>
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							          <span aria-hidden="true">&times;</span>
							        </button>
							      </div>
							      <div class="modal-body">
							      <form action="<?=base_url('device/EditDevices/' . $dts['id']);?>" method="post">
							      <div class="form-group">
							      <label >Nama Devices</label>
							    <input type="text" class="form-control" value="<?=$namedevice;?>" name="nama_devices" id="nama_devices" placeholder="Nama Devices">
							    </div>
							    <div class="form-group">
							    <label >Key Devices</label>
							    <input type="text" class="form-control" value="<?=$keydevices?>" name="key_devices" id="key_devices" placeholder="Key Devices">
							    </div>
							    <div class="form-group">
							    <label for="">Mode Devices</label>
							    <select class="form-control" id="mode_devices" name="mode_devices">
							      <option value="1">Read</option>
							      <option value="2">Add</option>
							    </select>
							  </div>
							    <div class="form-group">
							    <label for="">Active</label>
							    <select class="form-control" id="is_active" name="is_active">
							      <option value="1">Activated</option>
							      <option value="0">Disabled</option>
							    </select>
							  </div>
							    <div class="form-group">
							    </div>
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							        <button type="submit" class="btn btn-primary">Save changes</button>
							      </div>
							      </form>
							    </div>
							  </div>
							</div>
							<?php endforeach;?>

<!-- Modal -->

<div class="modal fade" id="ModalAddUser" tabindex="-1" role="dialog" aria-labelledby="ModalAddUserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalAddUserLabel">Add Devices</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form action="<?=base_url('device');?>" method="post">
      <div class="form-group">
      <label >Nama Devices</label>
    <input type="text" class="form-control" name="nama_devices" id="nama_devices" placeholder="Nama Devices">
    </div>
    <div class="form-group">
    <label >Key Devices</label>
    <input type="text" class="form-control" name="key_devices" id="key_devices" placeholder="Key Devices">
    </div>
    <div class="form-group">
    <label for="">Mode Devices</label>
    <select class="form-control" id="mode_devices" name="mode_devices">
      <option value="1">Read</option>
      <option value="2">Add</option>
    </select>
  </div>
    <div class="form-group">
    <label for="">Active</label>
    <select class="form-control" id="is_active" name="is_active">
      <option value="1">Activated</option>
      <option value="0">Disabled</option>
    </select>
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>
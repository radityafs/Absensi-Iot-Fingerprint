<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laporan</title>
    <link rel="stylesheet" href="">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<style>
    .line-title{
        border: 0;
        border-style: inset;
        border-top: 1px solid #000;
    }
</style>
</head>
<body>
    <img src="<?base_url('assets/img/logo.png')?>" style="position:absolute; width:60px; height: auto;">
    <table style="widht: 100%;">
        <tr>
            <td>
                <span style="Line-height: 1.6;  font-weight: bold;">
                    REKAP ABSENSI SMA N 2 SRAGEN
                    <br>Kelas <?=$kelas?> Tanggal <?=$awal?> - <?=$akhir?>
    </span>
            </td>
</tr>
</table>

<br class="line-title">

 <table class ="table table-bordered">
<tr>
            <th>NOMOR</th>
            <th>NAMA</th>
            <th>KELAS</th>
            <th>Hari Efektif</th>
            <th>Masuk</th>
            <th>Terlambat</th>
            <th>Ijin</th>
            <th>Sakit</th>
            <th>Bolos</th>
            <th>Alfa</th>
</tr>

<?php $no = 1;?>
<?php foreach ($menu as $dt): ?>

<tr>
        <td><?=$no?></td>
        <td><?=$dt['NAMA']?></td>
        <td><?=$dt['KELAS']?></td>
        <td><?=$dt['Hari Efektif']?></td>
        <td><?=$dt['Masuk']?></td>
        <td><?=$dt['Terlambat']?></td>
        <td><?=$dt['Ijin']?></td>
        <td><?=$dt['Sakit']?></td>
        <td><?=$dt['Bolos']?></td>
        <td><?=$dt['Alfa']?></td>

</tr>
<?php $no++;?>
<?php endforeach;?>

 </table>

</body>
</html>
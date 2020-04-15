<table>
    <tr>
        <td colspan=3>Nama</td>
        <td>:</td>
        <td colspan=5>{{$user->fullname}}</td>
    </tr>
    <tr>
        <td colspan=3>Golongan</td>
        <td>:</td>
        <td colspan=5>{{$user->level}}</td>
    </tr>
    <tr>
        <td colspan=3>Jabatan</td>
        <td>:</td>
        <td colspan=5>{{$user->position}}</td>
    </tr>
    <tr>
        <td colspan=3>Satuan Kerja</td>
        <td>:</td>
        <td colspan=5>{{$user->location}}</td>
    </tr>
    <tr>
        <td colspan=3>Unit Kerja</td>
        <td>:</td>
        <td colspan=5>{{$user->units}}</td>
    </tr>
    <tr>
        <td colspan=3>Tanggal</td>
        <td>:</td>
        <td colspan=5>{{$tanggal}}</td>
    </tr>
    <tr>
        <td colspan=9></td>
    </tr>
    <tr>
       <th style="text-align:left">No</th>
       <th colspan=5>Kegiatan</th>
       <th>Satuan</th>
       <th>Volume</th>
       <th>Output</th> 
    </tr>
    @forelse($kinerja as $key => $kin)
        <tr valign="top">
            <td style="text-align:left">{{$key+1}}</td>
            <td colspan=5 style="height:auto;word-wrap: break-word;">{{$kin->description}}</td>
            <td style="text-align:left">{{$kin->metrix}}</td>
            <td style="text-align:left">{{$kin->volume}}</td>
            <td style="text-align:left">{{$kin->output}}</td> 
        </tr>
    @empty
        <tr>
        <td colspan='9'>Tidak ada data </td>
        </tr>
    @endforelse

</table>
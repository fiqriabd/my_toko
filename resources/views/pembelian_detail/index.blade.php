@extends('layouts.master')

@section('title')
    Transaksi Pembelian
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Transaksi Pembelian</li>
@endsection

@section('content')
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-12">
            <div class="box">
              <div class="box-header with-border">
                <table>
                  <tr>
                    <td>Distributor</td>
                    <td>: {{ $distributor -> nama_distributor }}</td>
                  </tr>
                  <tr>
                    <td>Telepon</td>
                    <td>: {{ $distributor -> telepon_distributor }}</td>
                  </tr>
                  <tr>
                    <td>Alamat</td>
                    <td>: {{ $distributor -> alamat_distributor }}</td>
                  </tr>
                </table>
              </div>
                <div class="box-body table-responsive">
                  <form class="form-produk">
                    @csrf
                        <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Kode Produk</label>
                        <div class="col-lg-5">
                          <div class="input-group">
                            <input type="hidden" name="id_pembelian" id="id_pembelian" value="{{ $id_pembelian }}">
                            <input type="hidden" name="id_produk" id="id_produk">
                            <input type="text" class="form-control" name="kode_produk" id="kode_produk">
                            <span class="input-group-btn">
                            <button onclick="tampilProduk()"class="btn btn-info btn-flat"type="button"><i class="fa fa-arrow-right"></i></button>
                              </span>
                          </div>
                        </div>                    
                      </div>
                  </form>
                    <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th width="10%">Jumlah</th>
                        <th>Subtotal</th>
                        <th width="15%">Aksi <i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody></tbody>
                    </table>
                </div>
            </div>
            <!-- /.box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
@includeIf('pembelian_detail.produk')
@endsection

@push('scripts')
<script>
  let table, table2;

  $(function(){
    table =   $('.table-pembelian').DataTable({
        processing: true,
        autoWidth: false,
        ajax: {
          url: '{{ route('pembelian_detail.data', $id_pembelian) }}',
        },
        columns:[
          {data: 'DT_RowIndex', searchable: false, sortable: false},
          {data: 'kode_produk'},
          {data: 'nama_produk'},
          {data: 'harga_beli_pembelian_detail'},
          {data: 'jumlah_pembelian_detail'},
          {data: 'subtotal_pembelian_detail'},
          {data: 'aksi', searchable: false, sortable: false},
        ]
    });
    table2 = $('.table-produk').DataTable();

    $(document).on('input','quantity', function(){

    });
  });

  function tampilProduk(){
    $('#modal-produk').modal('show');

  }

  // function editForm(url){
  //   $('#modal-form').modal('show');
  //   $('#modal-form .modal-title').text('Edit Pembelian');

  //   $('#modal-form form')[0].reset();
  //   $('#modal-form form').attr('action', url);
  //   $('#modal-form [name=_method]').val('put');
  //   $('#modal-form [name=deskripsi_pembelian]').focus();

  //   $.get(url)
  //       .done((response) => {
  //           $('#modal-form [name=deskripsi_pembelian]').val(response.deskripsi_pembelian);
  //           $('#modal-form [name=nominal_pembelian]').val(response.nominal_pembelian);
  //           $('#modal-form [name=alamat_pembelian]').val(response.alamat_pembelian);
  //       })
  //       .fail((errors) => {
  //           alert('Tidak dapat menampilkan data');
  //           return;
  //     });  
  // }

  function hideProduk(){
    $('#modal-produk').modal('hide');
  }

  function pilihProduk(id, kode){
      $('#id_produk').val(id);
      $('#kode_produk').val(kode);
      hideProduk();
      tambahProduk();
  }

  function tambahProduk(){
      $.post('{{ route('pembelian_detail.store') }}', $('.form-produk').serialize())
        .done(response => {
            $('#kode_produk').focus();
            table.ajax.reload();
        })
        .fail(errors => {
            alert("Tidak dapat menyimpan data");
            return;
        })
  }

  function deleteData(url){
      if(confirm('Yakin ingin menghapus data terpilih?')){
            $.post(url,{
                    '_token': $('[name=csrf-token').attr('content'),
                    '_method': 'delete'
                })
             .done((response) => {
                    table.ajax.reload();
                })
              .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
              })
      }
  }
   
</script>
@endpush
@extends('layouts.master')

@section('title')
    Transaksi Pembelian
@endsection

@push('css')
<style>
  .tampil-bayar {
      font-size: 5em;
      text-align: center;
      height: 100px;
  }
  .tampil-terbilang {
      padding: 10px;
      background: #f0f0f0;
  }

  @media(max-width: 768px){
      .tampil-bayar {
        font-size: 3em;
        height: 70px;
        padding-top: 5px;
      }
  }
</style>
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
                <div class="box-body">
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

                    <div class="row">
                      <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                      </div>
                      <div class="col-lg-4">
                        <form action="{{ 'pembelian.store' }}" class="form-pembelian" method="post">
                          @csrf
                          <input type="hidden" class="id_pembelian" value="{{ $id_pembelian }}">
                          <input type="hidden" class="total" id="total">
                          <input type="hidden" class="total_item" id="total_item">
                          <input type="hidden" class="bayar" id="bayar">

                          <div class="form-group row">
                            <label for="totalrp" class="col-lg-2 control-label">Total</label>
                            <div class="col-lg-8">
                              <input type="text" name="totalrp" id="totalrp" class="form-control" readonly>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                            <div class="col-lg-8">
                              <input type="text" name="diskon" id="diskon" class="form-control">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                            <div class="col-lg-8">
                              <input type="text" name="bayar" id="bayar" class="form-control">
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                </div>
                <div class="box-footer">
                  <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floopy-o"></i>Simpan Transaksi</button>
                </div>
            </div>
          </div>
        </div>
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
        ], 
        dom: 'Brt',
        bSort: false,
    });
    table2 = $('.table-produk').DataTable();

    $(document).on('input','.quantity', function(){
        let id = $(this).data('id');
        let jumlah = parseInt($(this).val());

        if (jumlah < 1){
          $(this).val(1);
          alert("Jumlah tidak boleh kurang dari 1");
          return;
        }

        if (jumlah > 1000) {
          $(this).val(1000);
          alert("Jumlah tidak boleh lebih dari 10000");
          return;
        }

        $.post(`{{ url('/pembelian_detail') }}/${id}`,{
              '_token': $('[name=csrf-token]').attr('content'),
              '_method': 'put',
              'jumlah_pembelian_detail': jumlah
            })
            .done(response => {
              $(this).on('mouseout', function(){
                  table.ajax.reload();
              });
            })
            .fail(errors =>{
                alert('Tidak dapat menyimpan data');
                return;
            });
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
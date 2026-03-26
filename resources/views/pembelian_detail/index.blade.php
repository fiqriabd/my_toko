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

  .table-pembelian tbody tr:last-child{
      display: none;
  }

  @media(max-width: 768px){
      .tampil-bayar {
        font-size: 3em;
        height: 70px;
        padding-top: 5px;
      }
  }
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Transaksi Pembelian</li>
@endsection

@section('content')
        <!-- /.row -->
        <div class="row">
          <div class="col-md-12">
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

                    <table class="table table-striped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th width="10%">Jumlah</th>
                        <th>Subtotal</th>
                        <th width="15%">Aksi <i class="fa fa-cog"></i></th>
                    </thead>
                    </table>

                    <div class="row">
                      <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                      </div>
                      <div class="col-lg-4">
                        <form action="{{ route('pembelian.store') }}" class="form-pembelian" method="post">
                          @csrf
                          <input type="hidden" name="id_pembelian" value="{{ $id_pembelian }}">
                          <input type="hidden" name="total" id="total">
                          <input type="hidden" name="total_item" id="total_item">
                          <input type="hidden" name="bayar_pembelian" id="bayar_pembelian">

                          <div class="form-group row">
                            <label for="totalrp" class="col-lg-2 control-label">Total</label>
                            <div class="col-lg-8">
                              <input type="text" id="totalrp" class="form-control" readonly>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="diskon_pembelian" class="col-lg-2 control-label">Diskon</label>
                            <div class="col-lg-8">
                              <input type="number" name="diskon_pembelian" id="diskon_pembelian" class="form-control" value="{{ $diskon }}">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                            <div class="col-lg-8">
                              <input type="text" id="bayarrp" class="form-control">
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
    $('body').addClass('sidebar-collapse');

    table = $('.table-pembelian').DataTable({
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
    })
    .on('draw.dt', function(){
        loadForm($('#diskon_pembelian').val());
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
          alert("Jumlah tidak boleh lebih dari 1000");
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
                // alert('Tidak dapat menyimpan data');
                return;
            });
    });

      $(document).on('input', '#diskon_pembelian', function(){
        if($(this).val() == ""){
          $(this).val(0).select();
        }

        loadForm($(this).val());
    });

    $('.btn-simpan').on('click', function(){
        $('.form-pembelian').submit();
    });
  });


  function tampilProduk(){
    $('#modal-produk').modal('show');

  }

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
        });
  }

  function deleteData(url){
      if(confirm('Yakin ingin menghapus data terpilih?')){
            $.post(url,{
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
             .done((response) => {
                    table.ajax.reload();
                })
              .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
              });
      }
  }
   
  function loadForm(diskon = 0) {
      $('#total').val($('.total').text());
      $('#total_item').val($('.total_item').text());

      $.get(`{{ url('/pembelian_detail/loadform') }}/${diskon}/${$('.total').text()}`)
          .done(response => {
              $('#totalrp').val('Rp. '+ response.totalrp);
              $('#bayarrp').val('Rp. '+ response.bayarrp);
              $('#bayar_pembelian').val(response.bayar);
              $('.tampil-bayar').text('Rp. '+response.bayarrp);
              $('.tampil-terbilang').text(response.terbilang);
          })
          .fail(errors => {
              alert("Tidak dapat menampilkan data");
              return;
          })
  }
</script>
@endpush
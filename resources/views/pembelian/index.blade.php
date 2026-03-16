@extends('layouts.master')

@section('title')
    Daftar Pembelian
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pembelian</li>
@endsection

@section('content')
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-12">
            <div class="box">
              <div class="box-header with-border">
                <button onclick="addForm()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i>Transaksi Baru</button>
                @empty(! session('id_pembelian'))
                <a href="{{ route('pembelian_detail.index') }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-pencil"></i>Transaksi Aktif</a>
                @endempty
              </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Distributor</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Diskon</th>
                        <th>Total Bayar</th>
                        <th width="15%">Aksi <i class="fa fa-cog"></i></th>
                    </thead>
                    </table>
                </div>
            </div>
            <!-- /.box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
@includeIf('pembelian.distributor')
@includeIf('pembelian.detail')
@endsection

@push('scripts')
<script>
  let table, table1;

  $(function(){
    table =   $('.table-pembelian').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('pembelian.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'distributor'},
                {data: 'total_item_pembelian'},
                {data: 'total_harga_pembelian'},
                {data: 'diskon_pembelian'},
                {data: 'bayar_pembelian'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
    });

     $('.table-distributor').DataTable();
        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_beli_pembelian_detail'},
                {data: 'jumlah_pembelian_detail'},
                {data: 'subtotal_pembelian_detail'},
            ]
        })

  });

  function addForm(){
    $('#modal-distributor').modal('show');

  }

  function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

//   function editForm(url){
//     $('#modal-form').modal('show');
//     $('#modal-form .modal-title').text('Edit Pembelian');

//     $('#modal-form form')[0].reset();
//     $('#modal-form form').attr('action', url);
//     $('#modal-form [name=_method]').val('put');
//     $('#modal-form [name=deskripsi_pembelian]').focus();

//     $.get(url)
//         .done((response) => {
//             $('#modal-form [name=deskripsi_pembelian]').val(response.deskripsi_pembelian);
//             $('#modal-form [name=nominal_pembelian]').val(response.nominal_pembelian);
//             $('#modal-form [name=alamat_pembelian]').val(response.alamat_pembelian);
//         })
//         .fail((errors) => {
//             alert('Tidak dapat menampilkan data');
//             return;
//       });  
//   }

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
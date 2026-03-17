@extends('layouts.master')

@section('title')
    Daftar Pembelian
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pembelian</li>
@endsection

@section('content')
        <div class="row">
          <div class="col-md-12">
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
   
</script>
@endpush
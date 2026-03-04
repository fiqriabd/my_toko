@extends('layouts.master')

@section('title')
    Produk
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Produk</li>
@endsection

@section('content')
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-12">
            <div class="box">
              <div class="box-header with-border">
                <div class="btn-group">
                    <button onclick="addForm('{{ route('produk.store') }}')" class="btn btn-success btn-sm btn-flat"><i class="fa fa-plus-circle"> Tambah</i></button>
                    <button onclick="deleteSelected('{{ route('produk.delete_selected') }}')" class="btn btn-danger btn-sm btn-flat"><i class="fa fa-trash"> Hapus</i></button>
                </div>
              </div>
                <div class="box-body table-responsive">
                  <form action="" class="form-produk">
                    @csrf
                    <table class="table table-stiped table-bordered">
                    <thead>
                        <th>
                          <input type="checkbox" name="select_all" id="select_all">
                        </th>                        
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Merk</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th width="15%">Aksi <i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody></tbody>
                    </table>
                  </form>
                </div>
            </div>
            <!-- /.box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
@includeIf('produk.form')
@endsection

@push('scripts')
<script>
  let table;

  $(function(){
    table =   $('.table').DataTable({
      processing: true,
      autoWidth: false,
      ajax: {
        url:'{{ route('produk.data') }}',
      },
      columns:[
        {data: 'select_all'},
        {data: 'DT_RowIndex', searchable: false, sortable: false},
        {data: 'kode_produk'},
        {data: 'nama_produk'},
        {data: 'nama_kategori'},
        {data: 'merk_produk'},
        {data: 'harga_beli_produk'},
        {data: 'harga_jual_produk'},
        {data: 'stok_produk'},
        {data: 'aksi', searchable: false, sortable: false}
      ]
    });

    $('#modal-form').validator().on('submit', function (e){
      if (! e.preventDefault()){
          $.post($('#modal-form form').attr('action'),$('#modal-form form').serialize())
          .done((response) => {
            $('#modal-form').modal('hide'); 
            table.ajax.reload();
          })
          .fail((errors) => {
              alert('Tidak dapat menyimpan data');
              return;
          });
      }
    });

    $('[name=select_all]').on('click', function(){
        $(':checkbox').prop('checked', this.checked);
    })

  });

  function addForm(url){
    $('#modal-form').modal('show');
    $('#modal-form .modal-title').text('Tambah Produk');

    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('post');
    $('#modal-form [name=nama_produk]').focus();
  }

  function editForm(url){
    $('#modal-form').modal('show');
    $('#modal-form .modal-title').text('Edit Produk');

    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('put');
    $('#modal-form [name=nama_produk]').focus();

    $.get(url)
        .done((response) => {
            $('#modal-form [name=nama_produk]').val(response.nama_produk);
            $('#modal-form [name=id_kategori]').val(response.id_kategori);
            $('#modal-form [name=merk_produk]').val(response.merk_produk);
            $('#modal-form [name=harga_beli_produk]').val(response.harga_beli_produk);
            $('#modal-form [name=harga_jual_produk]').val(response.harga_jual_produk);
            $('#modal-form [name=diskon_produk]').val(response.diskon_produk);
            $('#modal-form [name=stok_produk]').val(response.stok_produk);
        })
        .fail((errors) => {
            alert('Tidak dapat menampilkan data');
            return;
      });  
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
   
   function deleteSelected(url){
        if ($('input:checked').length > 1){
            if(confirm('Yakin ingin menghapus data terpilih?')){
                $.post(url, $('.form-produk').serialize())
                .done((response) => {
                  table.ajax.reload();
                })
                .fail((errors) => {
                  alert('Tidak dapat menghapus data');
                  return;
                });
            }
        } else{
            alert('Pilih data yang akan dihapus');
            return;
        }
   }
</script>
@endpush
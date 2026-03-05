@extends('layouts.master')

@section('title')
    Distributor
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Distributor</li>
@endsection

@section('content')
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-12">
            <div class="box">
              <div class="box-header with-border">
                <button onclick="addForm('{{ route('distributor.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"> Tambah</i></button>
              </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Distributor</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
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
@includeIf('distributor.form')
@endsection

@push('scripts')
<script>
  let table;

  $(function(){
    table =   $('.table').DataTable({
      processing: true,
      autoWidth: false,
      ajax: {
        url:'{{ route('distributor.data') }}',
      },
      columns:[
        {data: 'DT_RowIndex', searchable: false, sortable: false},
        {data: 'nama_distributor'},
        {data: 'alamat_distributor'},
        {data: 'telepon_distributor'},
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
    })

  });

  function addForm(url){
    $('#modal-form').modal('show');
    $('#modal-form .modal-title').text('Tambah Distributor');

    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('post');
    $('#modal-form [name=nama_distributor]').focus();
  }

  function editForm(url){
    $('#modal-form').modal('show');
    $('#modal-form .modal-title').text('Edit Distributor');

    $('#modal-form form')[0].reset();
    $('#modal-form form').attr('action', url);
    $('#modal-form [name=_method]').val('put');
    $('#modal-form [name=nama_distributor]').focus();

    $.get(url)
        .done((response) => {
            $('#modal-form [name=nama_distributor]').val(response.nama_distributor);
            $('#modal-form [name=alamat_distributor]').val(response.alamat_distributor);
            $('#modal-form [name=telepon_distributor]').val(response.telepon_distributor);
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
   
</script>
@endpush
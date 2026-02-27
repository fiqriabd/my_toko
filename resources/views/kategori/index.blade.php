@extends('layouts.master')

@section('title')
    Kategori
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Kategori</li>
@endsection

@section('content')
        <!-- /.row -->
        <div class="row">
          <div class="col-md-12">
            <div class="box">
              <div class="box-header with-border">
                <button class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle">Tambah</i></button>
              </div>
                <div class="box-body table-responsive">
                    <table class="table table-stiped table-bordered"></table>
                    <thead>
                        <th width="5%">No</th>
                        <th>Kategori</th>
                        <th width="15%"><i class="fa cog"></i></th>
                    </thead>
                </div>
            </div>
            <!-- /.box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

@endsection
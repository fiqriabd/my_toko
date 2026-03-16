<!-- Modal -->
<div class="modal fade" id="modal-distributor" tabindex="-1" role="dialog" aria-labelledby="modal-distributor">
  <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Pilih Distributor</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Aksi <i class="fa fa-cog"></i></th>
                        </thead>
                        <tbody>
                            @foreach ($distributor as $key => $item)
                                <tr>
                                    <td width="5%">{{ $key+1 }}</td>
                                    <td>{{ $item->nama_distributor }}</td>
                                    <td>{{ $item->telepon_distributor }}</td>
                                    <td>{{ $item->alamat_distributor }}</td>
                                    <td>
                                        <a href="{{ route('pembelian.create', $item->id_distributor) }}" class="btn btn-primary btn-xs btn-flat">
                                            <i class="fa fa-check-circle"></i>
                                            Pilih
                                        </a>
                                    </td>
                                </tr>
                            
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
  </div>
</div>
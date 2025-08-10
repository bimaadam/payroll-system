<?php
require_once '../auth.php';
requireLogin();
?>
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
          <h6>Data Jabatan</h6>
          <button class="btn btn-primary btn-sm" onclick="showAddModal()">
            <i class="fas fa-plus"></i> Tambah Jabatan
          </button>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0" id="jabatanTable">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Jabatan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama Jabatan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal Dibuat</th>
                  <th class="text-secondary opacity-7">Aksi</th>
                </tr>
              </thead>
              <tbody id="jabatanTableBody">
                <!-- Data will be loaded here -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="jabatanModal" tabindex="-1" role="dialog" aria-labelledby="jabatanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="jabatanModalLabel">Tambah Jabatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="jabatanForm">
          <div class="form-group">
            <label for="kode_jabatan" class="form-control-label">Kode Jabatan</label>
            <input class="form-control" type="text" id="kode_jabatan" name="kode_jabatan" required>
          </div>
          <div class="form-group">
            <label for="nama_jabatan" class="form-control-label">Nama Jabatan</label>
            <input class="form-control" type="text" id="nama_jabatan" name="nama_jabatan" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="saveJabatan()">Simpan</button>
      </div>
    </div>
  </div>
</div>

<script>
let isEditMode = false;
let currentKodeJabatan = '';

// Load jabatan data
function loadJabatan() {
  $.ajax({
    url: 'api/jabatan.php',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        let tbody = $('#jabatanTableBody');
        tbody.empty();
        
        response.data.forEach(function(jabatan) {
          let row = `
            <tr>
              <td>
                <div class="d-flex px-2 py-1">
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="mb-0 text-sm">${jabatan.kode_jabatan}</h6>
                  </div>
                </div>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">${jabatan.nama_jabatan}</p>
              </td>
              <td>
                <span class="text-secondary text-xs font-weight-bold">${formatDate(jabatan.created_at)}</span>
              </td>
              <td class="align-middle">
                <button class="btn btn-link text-secondary mb-0" onclick="editJabatan('${jabatan.kode_jabatan}')">
                  <i class="fa fa-edit text-xs"></i>
                </button>
                <button class="btn btn-link text-secondary mb-0" onclick="deleteJabatan('${jabatan.kode_jabatan}')">
                  <i class="fa fa-trash text-xs"></i>
                </button>
              </td>
            </tr>
          `;
          tbody.append(row);
        });
      }
    },
    error: function() {
      Swal.fire('Error', 'Gagal memuat data jabatan', 'error');
    }
  });
}

// Show add modal
function showAddModal() {
  isEditMode = false;
  currentKodeJabatan = '';
  $('#jabatanModalLabel').text('Tambah Jabatan');
  $('#jabatanForm')[0].reset();
  $('#kode_jabatan').prop('readonly', false);
  $('#jabatanModal').modal('show');
}

// Edit jabatan
function editJabatan(kode) {
  isEditMode = true;
  currentKodeJabatan = kode;
  $('#jabatanModalLabel').text('Edit Jabatan');
  
  $.ajax({
    url: 'api/jabatan.php?kode_jabatan=' + kode,
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        $('#kode_jabatan').val(response.data.kode_jabatan).prop('readonly', true);
        $('#nama_jabatan').val(response.data.nama_jabatan);
        $('#jabatanModal').modal('show');
      }
    }
  });
}

// Save jabatan
function saveJabatan() {
  let formData = {
    kode_jabatan: $('#kode_jabatan').val(),
    nama_jabatan: $('#nama_jabatan').val()
  };
  
  let url = 'api/jabatan.php';
  let method = isEditMode ? 'PUT' : 'POST';
  
  $.ajax({
    url: url,
    method: method,
    data: JSON.stringify(formData),
    contentType: 'application/json',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        Swal.fire('Berhasil', response.message, 'success');
        $('#jabatanModal').modal('hide');
        loadJabatan();
      } else {
        Swal.fire('Error', response.message, 'error');
      }
    },
    error: function() {
      Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data', 'error');
    }
  });
}

// Delete jabatan
function deleteJabatan(kode) {
  Swal.fire({
    title: 'Konfirmasi',
    text: 'Apakah Anda yakin ingin menghapus jabatan ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: 'api/jabatan.php',
        method: 'DELETE',
        data: JSON.stringify({kode_jabatan: kode}),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            Swal.fire('Berhasil', response.message, 'success');
            loadJabatan();
          } else {
            Swal.fire('Error', response.message, 'error');
          }
        },
        error: function() {
          Swal.fire('Error', 'Terjadi kesalahan saat menghapus data', 'error');
        }
      });
    }
  });
}

// Format date
function formatDate(dateString) {
  let date = new Date(dateString);
  return date.toLocaleDateString('id-ID');
}

// Load data when page loads
$(document).ready(function() {
  loadJabatan();
});
</script>

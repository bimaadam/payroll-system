<?php
require_once '../auth.php';
requireLogin();
?>
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
          <h6>Data Karyawan</h6>
          <button class="btn btn-primary btn-sm" onclick="showAddModal()">
            <i class="fas fa-plus"></i> Tambah Karyawan
          </button>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0" id="karyawanTable">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">TTL</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jenis Kelamin</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jabatan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">No HP</th>
                  <th class="text-secondary opacity-7">Aksi</th>
                </tr>
              </thead>
              <tbody id="karyawanTableBody">
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
<div class="modal fade" id="karyawanModal" tabindex="-1" role="dialog" aria-labelledby="karyawanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="karyawanModalLabel">Tambah Karyawan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="karyawanForm">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="nama_karyawan" class="form-control-label">Nama Karyawan</label>
                <input class="form-control" type="text" id="nama_karyawan" name="nama_karyawan" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="ttl" class="form-control-label">Tempat, Tanggal Lahir</label>
                <input class="form-control" type="text" id="ttl" name="ttl" placeholder="Jakarta, 01 Januari 1990" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="jenis_kelamin" class="form-control-label">Jenis Kelamin</label>
                <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                  <option value="">Pilih Jenis Kelamin</option>
                  <option value="Laki-laki">Laki-laki</option>
                  <option value="Perempuan">Perempuan</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="no_hp" class="form-control-label">No HP</label>
                <input class="form-control" type="text" id="no_hp" name="no_hp" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="kode_jabatan" class="form-control-label">Jabatan</label>
                <select class="form-control" id="kode_jabatan" name="kode_jabatan" required>
                  <option value="">Pilih Jabatan</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="alamat" class="form-control-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="saveKaryawan()">Simpan</button>
      </div>
    </div>
  </div>
</div>

<script>
let isEditMode = false;
let currentIdKaryawan = '';

// Load karyawan data
function loadKaryawan() {
  $.ajax({
    url: 'api/karyawan.php',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        let tbody = $('#karyawanTableBody');
        tbody.empty();
        
        response.data.forEach(function(karyawan) {
          let row = `
            <tr>
              <td>
                <div class="d-flex px-2 py-1">
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="mb-0 text-sm">${karyawan.id_karyawan}</h6>
                  </div>
                </div>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">${karyawan.nama_karyawan}</p>
              </td>
              <td>
                <span class="text-secondary text-xs font-weight-bold">${karyawan.ttl}</span>
              </td>
              <td>
                <span class="text-secondary text-xs font-weight-bold">${karyawan.jenis_kelamin}</span>
              </td>
              <td>
                <span class="text-secondary text-xs font-weight-bold">${karyawan.nama_jabatan || '-'}</span>
              </td>
              <td>
                <span class="text-secondary text-xs font-weight-bold">${karyawan.no_hp}</span>
              </td>
              <td class="align-middle">
                <button class="btn btn-link text-secondary mb-0" onclick="viewKaryawan(${karyawan.id_karyawan})" title="Lihat Detail">
                  <i class="fa fa-eye text-xs"></i>
                </button>
                <button class="btn btn-link text-secondary mb-0" onclick="editKaryawan(${karyawan.id_karyawan})" title="Edit">
                  <i class="fa fa-edit text-xs"></i>
                </button>
                <button class="btn btn-link text-secondary mb-0" onclick="deleteKaryawan(${karyawan.id_karyawan})" title="Hapus">
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
      Swal.fire('Error', 'Gagal memuat data karyawan', 'error');
    }
  });
}

// Load jabatan options
function loadJabatanOptions() {
  $.ajax({
    url: 'api/jabatan.php',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        let select = $('#kode_jabatan');
        select.find('option:not(:first)').remove();
        
        response.data.forEach(function(jabatan) {
          select.append(`<option value="${jabatan.kode_jabatan}">${jabatan.nama_jabatan}</option>`);
        });
      }
    }
  });
}

// Show add modal
function showAddModal() {
  isEditMode = false;
  currentIdKaryawan = '';
  $('#karyawanModalLabel').text('Tambah Karyawan');
  $('#karyawanForm')[0].reset();
  loadJabatanOptions();
  $('#karyawanModal').modal('show');
}

// View karyawan details
function viewKaryawan(id) {
  $.ajax({
    url: 'api/karyawan.php?id_karyawan=' + id,
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        let karyawan = response.data;
        Swal.fire({
          title: 'Detail Karyawan',
          html: `
            <div class="text-left">
              <p><strong>ID:</strong> ${karyawan.id_karyawan}</p>
              <p><strong>Nama:</strong> ${karyawan.nama_karyawan}</p>
              <p><strong>TTL:</strong> ${karyawan.ttl}</p>
              <p><strong>Jenis Kelamin:</strong> ${karyawan.jenis_kelamin}</p>
              <p><strong>Alamat:</strong> ${karyawan.alamat}</p>
              <p><strong>No HP:</strong> ${karyawan.no_hp}</p>
              <p><strong>Jabatan:</strong> ${karyawan.nama_jabatan || '-'}</p>
            </div>
          `,
          confirmButtonText: 'Tutup'
        });
      }
    }
  });
}

// Edit karyawan
function editKaryawan(id) {
  isEditMode = true;
  currentIdKaryawan = id;
  $('#karyawanModalLabel').text('Edit Karyawan');
  loadJabatanOptions();
  
  $.ajax({
    url: 'api/karyawan.php?id_karyawan=' + id,
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        let karyawan = response.data;
        $('#nama_karyawan').val(karyawan.nama_karyawan);
        $('#ttl').val(karyawan.ttl);
        $('#jenis_kelamin').val(karyawan.jenis_kelamin);
        $('#alamat').val(karyawan.alamat);
        $('#no_hp').val(karyawan.no_hp);
        
        // Set jabatan after options are loaded
        setTimeout(() => {
          $('#kode_jabatan').val(karyawan.kode_jabatan);
        }, 500);
        
        $('#karyawanModal').modal('show');
      }
    }
  });
}

// Save karyawan
function saveKaryawan() {
  let formData = {
    nama_karyawan: $('#nama_karyawan').val(),
    ttl: $('#ttl').val(),
    jenis_kelamin: $('#jenis_kelamin').val(),
    alamat: $('#alamat').val(),
    no_hp: $('#no_hp').val(),
    kode_jabatan: $('#kode_jabatan').val()
  };
  
  if (isEditMode) {
    formData.id_karyawan = currentIdKaryawan;
  }
  
  let url = 'api/karyawan.php';
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
        $('#karyawanModal').modal('hide');
        loadKaryawan();
      } else {
        Swal.fire('Error', response.message, 'error');
      }
    },
    error: function() {
      Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data', 'error');
    }
  });
}

// Delete karyawan
function deleteKaryawan(id) {
  Swal.fire({
    title: 'Konfirmasi',
    text: 'Apakah Anda yakin ingin menghapus karyawan ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: 'api/karyawan.php',
        method: 'DELETE',
        data: JSON.stringify({id_karyawan: id}),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            Swal.fire('Berhasil', response.message, 'success');
            loadKaryawan();
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

// Load data when page loads
$(document).ready(function() {
  loadKaryawan();
});
</script>

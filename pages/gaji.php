<?php
require_once '../auth.php';
requireLogin();
?>
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
          <h6>Data Gaji</h6>
          <button class="btn btn-primary btn-sm" onclick="showAddModal()">
            <i class="fas fa-plus"></i> Tambah Data Gaji
          </button>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0" id="gajiTable">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Gaji</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Karyawan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jabatan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Gaji Pokok</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tunjangan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Bonus</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Gaji</th>
                  <th class="text-secondary opacity-7">Aksi</th>
                </tr>
              </thead>
              <tbody id="gajiTableBody">
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
<div class="modal fade" id="gajiModal" tabindex="-1" role="dialog" aria-labelledby="gajiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="gajiModalLabel">Tambah Data Gaji</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="gajiForm">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="kode_gaji" class="form-control-label">Kode Gaji</label>
                <input class="form-control" type="text" id="kode_gaji" name="kode_gaji" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="id_karyawan" class="form-control-label">Karyawan</label>
                <select class="form-control" id="id_karyawan" name="id_karyawan" required>
                  <option value="">Pilih Karyawan</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="gaji_pokok" class="form-control-label">Gaji Pokok</label>
                <input class="form-control" type="number" id="gaji_pokok" name="gaji_pokok" step="0.01" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="tunjangan" class="form-control-label">Tunjangan</label>
                <input class="form-control" type="number" id="tunjangan" name="tunjangan" step="0.01" value="0">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="bonus" class="form-control-label">Bonus</label>
                <input class="form-control" type="number" id="bonus" name="bonus" step="0.01" value="0">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="total_gaji_preview" class="form-control-label">Total Gaji (Preview)</label>
                <input class="form-control" type="text" id="total_gaji_preview" readonly>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" onclick="saveGaji()">Simpan</button>
      </div>
    </div>
  </div>
</div>

<script>
let isEditMode = false;
let currentKodeGaji = '';

// Load gaji data
function loadGaji() {
  $.ajax({
    url: 'api/gaji.php',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        let tbody = $('#gajiTableBody');
        tbody.empty();
        
        response.data.forEach(function(gaji) {
          let row = `
            <tr>
              <td>
                <div class="d-flex px-2 py-1">
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="mb-0 text-sm">${gaji.kode_gaji}</h6>
                  </div>
                </div>
              </td>
              <td>
                <p class="text-xs font-weight-bold mb-0">${gaji.nama_karyawan || '-'}</p>
              </td>
              <td>
                <span class="text-secondary text-xs font-weight-bold">${gaji.nama_jabatan || '-'}</span>
              </td>
              <td>
                <span class="text-secondary text-xs font-weight-bold">Rp ${formatCurrency(gaji.gaji_pokok)}</span>
              </td>
              <td>
                <span class="text-secondary text-xs font-weight-bold">Rp ${formatCurrency(gaji.tunjangan)}</span>
              </td>
              <td>
                <span class="text-secondary text-xs font-weight-bold">Rp ${formatCurrency(gaji.bonus)}</span>
              </td>
              <td>
                <span class="badge badge-sm bg-gradient-success">Rp ${formatCurrency(gaji.total_gaji)}</span>
              </td>
              <td class="align-middle">
                <button class="btn btn-link text-secondary mb-0" onclick="editGaji('${gaji.kode_gaji}')" title="Edit">
                  <i class="fa fa-edit text-xs"></i>
                </button>
                <button class="btn btn-link text-secondary mb-0" onclick="deleteGaji('${gaji.kode_gaji}')" title="Hapus">
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
      Swal.fire('Error', 'Gagal memuat data gaji', 'error');
    }
  });
}

// Load karyawan options (only those without gaji record)
function loadKaryawanOptions() {
  $.ajax({
    url: 'api/karyawan.php',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        let select = $('#id_karyawan');
        select.find('option:not(:first)').remove();
        
        response.data.forEach(function(karyawan) {
          select.append(`<option value="${karyawan.id_karyawan}">${karyawan.nama_karyawan} - ${karyawan.nama_jabatan || 'Tanpa Jabatan'}</option>`);
        });
      }
    }
  });
}

// Show add modal
function showAddModal() {
  isEditMode = false;
  currentKodeGaji = '';
  $('#gajiModalLabel').text('Tambah Data Gaji');
  $('#gajiForm')[0].reset();
  $('#kode_gaji').prop('readonly', false);
  loadKaryawanOptions();
  updateTotalPreview();
  $('#gajiModal').modal('show');
}

// Edit gaji
function editGaji(kode) {
  isEditMode = true;
  currentKodeGaji = kode;
  $('#gajiModalLabel').text('Edit Data Gaji');
  loadKaryawanOptions();
  
  $.ajax({
    url: 'api/gaji.php?kode_gaji=' + kode,
    method: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        let gaji = response.data;
        $('#kode_gaji').val(gaji.kode_gaji).prop('readonly', true);
        $('#gaji_pokok').val(gaji.gaji_pokok);
        $('#tunjangan').val(gaji.tunjangan);
        $('#bonus').val(gaji.bonus);
        
        // Set karyawan after options are loaded
        setTimeout(() => {
          $('#id_karyawan').val(gaji.id_karyawan);
        }, 500);
        
        updateTotalPreview();
        $('#gajiModal').modal('show');
      }
    }
  });
}

// Save gaji
function saveGaji() {
  let formData = {
    kode_gaji: $('#kode_gaji').val(),
    id_karyawan: $('#id_karyawan').val(),
    gaji_pokok: parseFloat($('#gaji_pokok').val()) || 0,
    tunjangan: parseFloat($('#tunjangan').val()) || 0,
    bonus: parseFloat($('#bonus').val()) || 0
  };
  
  let url = 'api/gaji.php';
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
        $('#gajiModal').modal('hide');
        loadGaji();
      } else {
        Swal.fire('Error', response.message, 'error');
      }
    },
    error: function() {
      Swal.fire('Error', 'Terjadi kesalahan saat menyimpan data', 'error');
    }
  });
}

// Delete gaji
function deleteGaji(kode) {
  Swal.fire({
    title: 'Konfirmasi',
    text: 'Apakah Anda yakin ingin menghapus data gaji ini?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, Hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: 'api/gaji.php',
        method: 'DELETE',
        data: JSON.stringify({kode_gaji: kode}),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            Swal.fire('Berhasil', response.message, 'success');
            loadGaji();
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

// Update total gaji preview
function updateTotalPreview() {
  let gajiPokok = parseFloat($('#gaji_pokok').val()) || 0;
  let tunjangan = parseFloat($('#tunjangan').val()) || 0;
  let bonus = parseFloat($('#bonus').val()) || 0;
  let total = gajiPokok + tunjangan + bonus;
  
  $('#total_gaji_preview').val('Rp ' + formatCurrency(total));
}

// Format currency
function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID').format(amount);
}

// Load data when page loads
$(document).ready(function() {
  loadGaji();
  
  // Add event listeners for real-time total calculation
  $('#gaji_pokok, #tunjangan, #bonus').on('input', updateTotalPreview);
});
</script>

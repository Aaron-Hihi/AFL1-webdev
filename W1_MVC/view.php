<?php
include("controller.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UC Lite Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        :root {
            --bs-primary: #fd910d;
            --bs-primary-rgb: 253, 145, 13;
        }

        body { 
            background-color: #f8f9fa; 
        }
        .card { 
            margin-top: 2rem; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); 
        }
        .card-header { 
            font-weight: bold; background-color: #fd910d; color: white
        }
        .bg-primary .card-header, .navbar-dark {
            color: #212529 !important;
        }
        .navbar-brand, .nav-link {
            color: white !important;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">UC Lite</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link nav-toggle active" href="#" data-target="#mahasiswa">Mahasiswa</a></li>
                    <li class="nav-item"><a class="nav-link nav-toggle" href="#" data-target="#mata_kuliah">Mata Kuliah</a></li>
                    <li class="nav-item"><a class="nav-link nav-toggle" href="#" data-target="#enrollments">Enrollments</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        <div id="mahasiswa" class="content-section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Daftar Mahasiswa</h2>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#dataModal" data-type="mahasiswa" data-action="add">+ Tambah Baru</button>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="mahasiswaTableBody">
                            <?php if (empty($allMahasiswa)): ?>
                                <tr><td colspan="4" class="text-center">Belum ada data mahasiswa.</td></tr>
                            <?php else: ?>
                                <?php foreach ($allMahasiswa as $key => $mahasiswa): ?>
                                <tr>
                                    <th><?= $key + 1 ?></th>
                                    <td><?= htmlspecialchars($mahasiswa->nama) ?></td>
                                    <td><?= htmlspecialchars($mahasiswa->address) ?></td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#dataModal" data-type="mahasiswa" data-action="edit" data-id="<?= $mahasiswa->id ?>" data-nama="<?= htmlspecialchars($mahasiswa->nama) ?>" data-address="<?= htmlspecialchars($mahasiswa->address) ?>">Edit</button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-type="mahasiswa" data-id="<?= $mahasiswa->id ?>" data-nama="<?= htmlspecialchars($mahasiswa->nama) ?>">Delete</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="mata_kuliah" class="content-section d-none">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Daftar Mata Kuliah</h2>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#dataModal" data-type="matakuliah" data-action="add">+ Tambah Baru</button>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Mata Kuliah</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="mataKuliahTableBody">
                            <?php if (empty($allMataKuliah)): ?>
                                <tr><td colspan="3" class="text-center">Belum ada data mata kuliah.</td></tr>
                            <?php else: ?>
                                <?php foreach ($allMataKuliah as $key => $matakuliah): ?>
                                <tr>
                                    <th><?= $key + 1 ?></th>
                                    <td><?= htmlspecialchars($matakuliah->nama) ?></td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#dataModal" data-type="matakuliah" data-action="edit" data-id="<?= $matakuliah->id ?>" data-mata_kuliah="<?= htmlspecialchars($matakuliah->nama) ?>">Edit</button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-type="matakuliah" data-id="<?= $matakuliah->id ?>" data-nama="<?= htmlspecialchars($matakuliah->nama) ?>">Delete</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div id="enrollments" class="content-section d-none">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Mahasiswa - Mata Kuliah</h2>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#dataModal" data-type="enrollments" data-action="add">+ Tambah Baru</button>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Mahasiswa</th>
                                <th>Mata Kuliah</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="enrollmentsTableBody">
                            <?php if (empty($allEnrollments)): ?>
                                <tr><td colspan="4" class="text-center">Belum ada data pendaftaran.</td></tr>
                            <?php else: ?>
                                <?php foreach ($allEnrollments as $key => $enrollment): ?>
                                <tr>
                                    <th><?= $key + 1 ?></th>
                                    <td><?= htmlspecialchars($mahasiswaMap[$enrollment->id_mahasiswa] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($mataKuliahMap[$enrollment->id_mata_kuliah] ?? 'N/A') ?></td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-danger btn-sm delete-btn" data-type="enrollment"data-id="<?= $enrollment->id ?>" data-nama="<?= htmlspecialchars($mahasiswaMap[$enrollment->id_mahasiswa] ?? 'N/A') ?> - <?= htmlspecialchars($mataKuliahMap[$enrollment->id_mata_kuliah] ?? 'N/A') ?>">Delete</button>
                                </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="dataForm" action="controller.php" method="POST">
                        <input type="hidden" id="formAction" name="action" value="">
                        <input type="hidden" id="formEntryId" name="id" value="">

                        <div id="mahasiswaFields">
                            <div class="mb-3">
                                <label for="modalNama" class="form-label">Nama Mahasiswa:</label>
                                <input type="text" class="form-control" id="modalNama" name="nama">
                            </div>
                            <div class="mb-3">
                                <label for="modalAddress" class="form-label">Alamat:</label>
                                <input type="text" class="form-control" id="modalAddress" name="address">
                            </div>
                        </div>

                        <div id="mataKuliahFields">
                            <div class="mb-3">
                                <label for="modalMataKuliah" class="form-label">Nama Mata Kuliah:</label>
                                <input type="text" class="form-control" id="modalMataKuliah" name="mata_kuliah">
                            </div>
                        </div>

                        <div id="enrollmentsFields">
                            <div class="mb-3">
                                <label for="modalSelectMahasiswa" class="form-label">Mahasiswa:</label>
                                <select class="form-select" id="modalSelectMahasiswa" name="id_mahasiswa"></select>
                            </div>
                            <div class="mb-3">
                                <label for="modalSelectMataKuliah" class="form-label">Mata Kuliah:</label>
                                <select class="form-select" id="modalSelectMataKuliah" name="id_mata_kuliah"></select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" form="dataForm" id="addBtn" name="add_button" class="btn btn-success d-none">Tambah</button>
                    <button type="submit" form="dataForm" id="editBtn" name="edit_button" class="btn btn-primary d-none">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteForm" action="controller.php" method="POST" style="display:none;">
        <input type="hidden" name="action" id="deleteAction">
        <input type="hidden" name="id" id="deleteId">
    </form>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            // PAGE NAV
            $('.nav-toggle').on('click', function(e) {
                e.preventDefault();
                const targetId = $(this).data('target');
                $('.nav-toggle').removeClass('active');
                $(this).addClass('active');
                $('.content-section').addClass('d-none');
                $(targetId).removeClass('d-none');
            });

            const dataModal = document.getElementById('dataModal');
            const modalTitle = $('#dataModalLabel');
            const form = $('#dataForm');
            const addBtn = $('#addBtn');
            const editBtn = $('#editBtn');

            // MODAL HANDLING
            dataModal.addEventListener('show.bs.modal', function(event) {
                const button = $(event.relatedTarget); 
                const action = button.data('action');
                const type = button.data('type');
                
                form[0].reset();
                
                $('#mahasiswaFields, #mataKuliahFields, #enrollmentsFields').addClass('d-none');
                addBtn.addClass('d-none');
                editBtn.addClass('d-none');
                
                form.find('input, select').prop('required', false);

                if (type === 'mahasiswa') {
                    $('#mahasiswaFields').removeClass('d-none');
                    $('#modalNama, #modalAddress').prop('required', true);
                    $('#formEntryId').attr('name', 'id_mahasiswa');

                    if (action === 'add') {
                        modalTitle.text('Tambah Mahasiswa Baru');
                        $('#formAction').val('add_mahasiswa');
                        addBtn.removeClass('d-none');
                    } else if (action === 'edit') {
                        modalTitle.text('Edit Mahasiswa');
                        $('#formAction').val('edit_mahasiswa');
                        $('#formEntryId').val(button.data('id'));
                        $('#modalNama').val(button.data('nama'));
                        $('#modalAddress').val(button.data('address'));
                        editBtn.removeClass('d-none');
                    }
                } else if (type === 'matakuliah') {
                    $('#mataKuliahFields').removeClass('d-none');
                    $('#modalMataKuliah').prop('required', true);
                    $('#formEntryId').attr('name', 'id_mata_kuliah');

                    if (action === 'add') {
                        modalTitle.text('Tambah Mata Kuliah Baru');
                        $('#formAction').val('add_matakuliah');
                        addBtn.removeClass('d-none');
                    } else if (action === 'edit') {
                        modalTitle.text('Edit Mata Kuliah');
                        $('#formAction').val('edit_matakuliah');
                        $('#formEntryId').val(button.data('id'));
                        $('#modalMataKuliah').val(button.data('mata_kuliah'));
                        editBtn.removeClass('d-none');
                    }
                } else if (type === 'enrollments') {
                    $('#enrollmentsFields').removeClass('d-none');
                    $('#modalSelectMahasiswa, #modalSelectMataKuliah').prop('required', true);

                    modalTitle.text('Daftarkan Mata Kuliah ke Mahasiswa');
                    $('#formAction').val('add_enrollment');
                    addBtn.removeClass('d-none');

                    // DROPDOWN HANDLING
                    const mahasiswaSelect = $('#modalSelectMahasiswa');
                    mahasiswaSelect.empty().append('<option selected disabled value="">Pilih...</option>');
                    $('#mahasiswaTableBody tr:has(.edit-btn)').each(function() {
                        const id = $(this).find('.edit-btn').data('id');
                        const name = $(this).find('td').eq(0).text();
                        mahasiswaSelect.append(new Option(name, id));
                    });

                    const mataKuliahSelect = $('#modalSelectMataKuliah');
                    mataKuliahSelect.empty().append('<option selected disabled value="">Pilih...</option>');
                    $('#mataKuliahTableBody tr:has(.edit-btn)').each(function() {
                        const id = $(this).find('.edit-btn').data('id');
                        const name = $(this).find('td').eq(0).text();
                        mataKuliahSelect.append(new Option(name, id));
                    });
                }
            });
        });

        // DELETE HANDLING
        $(document).on("click", ".delete-btn", function () {
            const id = $(this).data("id");
            const nama = $(this).data("nama");
            const type = $(this).data("type");

            let action = "";
            if (type === "mahasiswa") action = "delete_mahasiswa";
            if (type === "matakuliah") action = "delete_matakuliah";
            if (type === "enrollment") action = "delete_enrollment";

            if (confirm(`Apakah Anda yakin ingin menghapus ${nama}?`)) {
                $("#deleteAction").val(action);
                $("#deleteId").val(id);
                $("#deleteForm").submit();
            }
        });
    </script>
</body>
</html>


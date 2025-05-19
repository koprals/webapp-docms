<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\JenisPermohonan;
use App\Models\JenisDokumen;
use App\Models\Klien;
use App\Models\Dokumen;
use Illuminate\Support\Facades\Storage;

class PermohonanCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Permohonan::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/permohonan');
        CRUD::setEntityNameStrings('permohonan', 'permohonan');

        if (backpack_user()->hasRole('klien')) {
            $this->crud->addClause('where', 'id_klien', backpack_user()->klien->id_klien);
            $this->crud->denyAccess('delete');
        }

        if (!backpack_user()->hasRole('klien')) {
            $this->crud->denyAccess('create');
        }

        $this->crud->with(['klien', 'jenisPermohonan', 'dokumen.jenisDokumen']);
    }

    protected function setupListOperation()
    {
        if (backpack_user()->hasRole('admin')) {
            CRUD::column('id_permohonan')->label('ID');
            CRUD::column('klien.nama_klien')->label('Nama Klien');
            CRUD::column('jenisPermohonan.nama_jenis')->label('Jenis Permohonan');
            CRUD::column('tgl_input')->label('Tanggal')->type('date');
            CRUD::column('status')->label('Status')
                ->type('select_from_array')
                ->options([
                    0 => 'Draft',
                    1 => 'Terkirim',
                    2 => 'Diproses',
                    3 => 'Selesai'
                ]);
        } else {
            CRUD::column('id_permohonan')->label('ID');
            CRUD::column('jenisPermohonan.nama_jenis')->label('Jenis');
            CRUD::column('tgl_input')->label('Tanggal')->type('date');
            CRUD::column('status')->label('Status')
                ->type('badge')
                ->options([
                    0 => 'secondary',
                    1 => 'primary',
                    2 => 'warning',
                    3 => 'success'
                ]);
        }

        // Dropdown Sederhana untuk Filter Status
        $statusOptions = [
            '' => 'Semua Status',
            0 => 'Draft',
            1 => 'Terkirim',
            2 => 'Diproses',
            3 => 'Selesai'
        ];

        $currentStatus = request()->get('status_filter', '');

        // Jika ada filter status, tambahkan klausa where
        if ($currentStatus !== '') {
            $this->crud->addClause('where', 'status', $currentStatus);
        }
    }


    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'id_jenis_permohonan' => 'required',
            'tgl_input' => 'required|date',
            'alamat_pihak_satu' => 'required',
        ]);

        // Field utama permohonan
        CRUD::field('id_jenis_permohonan')
            ->label('Jenis Permohonan')
            ->type('select')
            ->entity('jenisPermohonan')
            ->model(JenisPermohonan::class)
            ->attribute('nama_jenis')
            ->wrapper(['class' => 'form-group col-md-6']);

        CRUD::field('tgl_input')
            ->label('Tanggal Input')
            ->type('date')
            ->wrapper(['class' => 'form-group col-md-6']);

        CRUD::field('alamat_pihak_satu')
            ->label('Alamat Pihak Satu')
            ->type('textarea');

        CRUD::field('alamat_pihak_dua')
            ->label('Alamat Pihak Dua')
            ->type('textarea');

        CRUD::field('no_pbb')
            ->label('No PBB')
            ->type('text')
            ->wrapper(['class' => 'form-group col-md-6']);

        CRUD::field('no_sertifikat')
            ->label('No Sertifikat')
            ->type('text')
            ->wrapper(['class' => 'form-group col-md-6']);

        CRUD::field('luas_tanah')
            ->label('Luas Tanah (m²)')
            ->type('number')
            ->wrapper(['class' => 'form-group col-md-6']);

        // Field upload dokumen
        $this->crud->addField([
            'name' => 'dokumen_section',
            'type' => 'custom_html',
            'value' => '<h4 class="mb-4">Upload Dokumen</h4>'
        ]);

        // Buat field upload untuk setiap jenis dokumen
        foreach (JenisDokumen::all() as $jenis) {
            $this->crud->addField([
                'name' => 'dokumen_'.$jenis->id_jenis,
                'label' => $jenis->nama_jenis,
                'type' => 'upload',
                'upload' => true, // Menandakan bahwa field ini hanya untuk upload, bukan untuk disimpan di tabel permohonan
                'disk' => 'public',
                'path' => 'dokumen',
                'hint' => 'Format: jpg, png, pdf, doc, docx (Max 5MB)',
                'wrapper' => ['class' => 'form-group col-md-6']
            ]);
        }


        // Auto-set klien jika user adalah klien
        if (backpack_user()->hasRole('klien')) {
            CRUD::field('id_klien')
                ->type('hidden')
                ->value(backpack_user()->klien->id_klien);
        } else {
            CRUD::field('id_klien')
                ->label('Klien')
                ->type('select')
                ->entity('klien')
                ->model(Klien::class)
                ->attribute('nama_klien');
        }

        if (backpack_user()->hasRole('admin')) {
            CRUD::field('status')
                ->label('Status')
                ->type('select_from_array')
                ->options([
                    0 => 'Draft',
                    1 => 'Terkirim',
                    2 => 'Diproses',
                    3 => 'Selesai'
                ])
                ->wrapper(['class' => 'form-group col-md-6']);
        }
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

        if (backpack_user()->hasRole('klien')) {
            $this->crud->addField([
                'name' => 'status_notice',
                'type' => 'custom_html',
                'value' => '<div class="alert alert-warning">Anda hanya bisa mengubah permohonan dengan status Draft</div>'
            ]);
            $this->crud->addClause('where', 'status', 0);
        }

        // Set nilai awal untuk field dokumen
        $entry = $this->crud->getCurrentEntry();
        $dokumenList = $entry->dokumen()->with('jenisDokumen')->get();

        foreach ($dokumenList as $dokumen) {
            $fieldName = 'dokumen_'.$dokumen->id_jenis;
            $this->crud->modifyField($fieldName, [
                'value' => $dokumen->source
            ]);
        }
    }

    protected function setupShowOperation()
    {
        CRUD::column('klien.nama_klien')->label('Nama Klien');
        CRUD::column('jenisPermohonan.nama_jenis')->label('Jenis Permohonan');
        CRUD::column('tgl_input')->label('Tanggal Input')->type('date');
        CRUD::column('status')->label('Status')
            ->type('select_from_array')
            ->options([
                0 => 'Draft',
                1 => 'Terkirim',
                2 => 'Diproses',
                3 => 'Selesai'
            ]);

        CRUD::column('alamat_pihak_satu')->label('Alamat Pihak Satu')->type('textarea');
        CRUD::column('alamat_pihak_dua')->label('Alamat Pihak Dua')->type('textarea');
        CRUD::column('no_pbb')->label('No PBB');
        CRUD::column('no_sertifikat')->label('No Sertifikat');
        CRUD::column('luas_tanah')->label('Luas Tanah (m²)');

        CRUD::column('dokumen')
            ->label('Dokumen Terlampir')
            ->type('custom_html')
            ->value(function($entry) {
                if ($entry->dokumen->isEmpty()) {
                    return '<div class="alert alert-info">Tidak ada dokumen terlampir</div>';
                }

                $html = '<div class="row">';
                foreach ($entry->dokumen as $dokumen) {
                    $html .= $this->generateDocumentThumbnail($dokumen);
                }
                $html .= '</div>';

                return $html;
            });
    }

    public function store()
    {
        // Hapus field dokumen dari request sebelum disimpan
        $request = $this->crud->getRequest();
        foreach (JenisDokumen::all() as $jenis) {
            $request->request->remove('dokumen_'.$jenis->id_jenis);
        }

        // Simpan data permohonan terlebih dahulu
        $response = $this->traitStore();

        // Tangani upload dokumen setelah permohonan tersimpan
        $this->handleDokumenUpload($this->crud->entry->id_permohonan);

        return $response;
    }

    public function update()
    {
        // Hapus field dokumen dari request sebelum disimpan
        $request = $this->crud->getRequest();
        foreach (JenisDokumen::all() as $jenis) {
            $request->request->remove('dokumen_'.$jenis->id_jenis);
        }

        // Update data permohonan terlebih dahulu
        $response = $this->traitUpdate();

        // Tangani upload dokumen setelah permohonan diupdate
        $this->handleDokumenUpload($this->crud->entry->id_permohonan);

        return $response;
    }


    protected function handleDokumenUpload($id_permohonan)
    {
        $request = $this->crud->getRequest();

        // Loop melalui setiap jenis dokumen
        foreach (JenisDokumen::all() as $jenis) {
            $fieldName = 'dokumen_' . $jenis->id_jenis;

            // Cek apakah ada file yang diupload
            if ($request->hasFile($fieldName) && $request->file($fieldName)->isValid()) {
                $file = $request->file($fieldName);

                // Simpan file ke storage
                $filePath = $file->store('dokumen', 'public');

                // Simpan atau update dokumen di tabel `dokumen`
                Dokumen::updateOrCreate(
                    [
                        'id_permohonan' => $id_permohonan,
                        'id_jenis' => $jenis->id_jenis
                    ],
                    [
                        'source' => $filePath,
                        'status' => 1 // Status 1 berarti dokumen aktif atau berhasil diupload
                    ]
                );
            } else {
                // Jika tidak ada file yang diupload, hapus dokumen terkait
                Dokumen::where('id_permohonan', $id_permohonan)
                    ->where('id_jenis', $jenis->id_jenis)
                    ->delete();
            }
        }
    }


    private function generateDocumentThumbnail($dokumen)
    {
        $fileUrl = Storage::url($dokumen->source);
        $fileExtension = pathinfo($dokumen->source, PATHINFO_EXTENSION);

        $previewHtml = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif'])
            ? '<img src="'.$fileUrl.'" class="img-fluid document-preview" style="max-height: 150px;">'
            : ($fileExtension === 'pdf'
                ? '<i class="la la-file-pdf-o" style="font-size: 50px; color: red;"></i>'
                : '<i class="la la-file" style="font-size: 50px;"></i>');

        return '
        <div class="col-md-4 mb-4">
            <div class="card document-thumbnail h-100">
                <div class="card-body text-center">
                    '.$previewHtml.'
                    <h6 class="mt-2">'.$dokumen->jenisDokumen->nama_jenis.'</h6>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="'.$fileUrl.'" target="_blank" class="btn btn-sm btn-primary btn-block">
                        <i class="la la-download"></i> Download
                    </a>
                </div>
            </div>
        </div>';
    }
}

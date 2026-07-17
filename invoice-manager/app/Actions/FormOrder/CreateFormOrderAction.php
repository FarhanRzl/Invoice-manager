<?php

namespace App\Actions\FormOrder;

use App\Models\Brand;
use App\Models\FormOrder;
use App\Services\FormOrderNumberService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateFormOrderAction
{
    public function __construct(
        protected FormOrderNumberService $numberService,
    ) {
    }

    public function execute(array $data, int $createdBy): FormOrder
    {
        return DB::transaction(function () use ($data, $createdBy) {

            $brand = Brand::findOrFail($data['brand_id']);

            $number = $this->numberService->generate($brand->id);

            $formOrder = FormOrder::create([
                'brand_id' => $brand->id,
                'created_by' => $createdBy,
                'invoice_id' => $data['invoice_id'] ?? null,
                'nomor' => $number['nomor'],
                'nomor_urut' => $number['nomor_urut'],
                'tahun' => $number['tahun'],
                'bulan' => $number['bulan'],
                'tanggal_order' => $data['tanggal_order'],
                'nama_klien' => $data['nama_klien'],
                'lokasi_project' => $data['lokasi_project'] ?? null,
                'jenis_pekerjaan' => $data['jenis_pekerjaan'] ?? null,
                'ukuran_bangunan' => $data['ukuran_bangunan'] ?? null,
                'arah_mata_angin' => $data['arah_mata_angin'] ?? null,
                'share_location' => $data['share_location'] ?? null,
                'lingkup_pekerjaan' => array_values(array_filter($data['lingkup_pekerjaan'] ?? [])),
                'catatan_klien' => $data['catatan_klien'] ?? null,
                'status' => 'draft',
            ]);

            foreach ($data['images'] ?? [] as $index => $image) {
                if (empty($image['file'])) {
                    continue;
                }

                $path = $image['file']->store('form-orders/images', 'public');

                $formOrder->images()->create([
                    'path' => $path,
                    'caption' => $image['caption'] ?? null,
                    'urutan' => $index + 1,
                ]);
            }

            return $formOrder;
        });
    }
}

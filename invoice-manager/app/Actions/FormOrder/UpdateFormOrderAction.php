<?php

namespace App\Actions\FormOrder;

use App\Models\FormOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateFormOrderAction
{
    public function execute(FormOrder $formOrder, array $data): FormOrder
    {
        return DB::transaction(function () use ($formOrder, $data) {

            $formOrder->update([
                'invoice_id' => $data['invoice_id'] ?? null,
                'tanggal_order' => $data['tanggal_order'],
                'nama_klien' => $data['nama_klien'],
                'lokasi_project' => $data['lokasi_project'] ?? null,
                'jenis_pekerjaan' => $data['jenis_pekerjaan'] ?? null,
                'ukuran_bangunan' => $data['ukuran_bangunan'] ?? null,
                'arah_mata_angin' => $data['arah_mata_angin'] ?? null,
                'share_location' => $data['share_location'] ?? null,
                'lingkup_pekerjaan' => array_values(array_filter($data['lingkup_pekerjaan'] ?? [])),
                'catatan_klien' => $data['catatan_klien'] ?? null,
            ]);

            $this->removeImages($formOrder, $data['remove_image_ids'] ?? []);
            $this->updateExistingCaptions($formOrder, $data['existing_images'] ?? []);
            $this->addNewImages($formOrder, $data['images'] ?? []);

            return $formOrder->refresh();
        });
    }

    protected function removeImages(FormOrder $formOrder, array $removeIds): void
    {
        if (empty($removeIds)) {
            return;
        }

        $images = $formOrder->images()->whereIn('id', $removeIds)->get();

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }

    protected function updateExistingCaptions(FormOrder $formOrder, array $existingImages): void
    {
        foreach ($existingImages as $id => $data) {
            $formOrder->images()->where('id', $id)->update([
                'caption' => $data['caption'] ?? null,
            ]);
        }
    }

    protected function addNewImages(FormOrder $formOrder, array $images): void
    {
        $nextUrutan = (int) $formOrder->images()->max('urutan') + 1;

        foreach ($images as $image) {
            if (empty($image['file'])) {
                continue;
            }

            $path = $image['file']->store('form-orders/images', 'public');

            $formOrder->images()->create([
                'path' => $path,
                'caption' => $image['caption'] ?? null,
                'urutan' => $nextUrutan++,
            ]);
        }
    }
}

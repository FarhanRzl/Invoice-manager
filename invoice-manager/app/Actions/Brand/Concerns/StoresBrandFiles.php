<?php

namespace App\Actions\Brand\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait StoresBrandFiles
{
    /**
     * @var array<string, string>
     */
    protected array $brandFileMap = [
        'logo' => 'logo_path',
        'qris' => 'qris_path',
        'ttd' => 'ttd_path',
        'stempel' => 'stempel_path',
        'materai' => 'materai_path',
    ];

    protected function storeBrandFiles(array $data, ?object $existingBrand = null): array
    {
        foreach ($this->brandFileMap as $field => $pathColumn) {
            if (! isset($data[$field]) || ! $data[$field] instanceof UploadedFile) {
                unset($data[$field]);

                continue;
            }

            if ($existingBrand && $existingBrand->{$pathColumn}) {
                Storage::disk('public')->delete($existingBrand->{$pathColumn});
            }

            $data[$pathColumn] = $data[$field]->store('brands', 'public');

            unset($data[$field]);
        }

        return $data;
    }

    protected function normalizeRekening(array $data): array
    {
        $data['rekening_config'] = collect($data['rekening'] ?? [])
            ->filter(fn ($row) => filled($row['bank'] ?? null) || filled($row['norek'] ?? null) || filled($row['nama'] ?? null))
            ->map(fn ($row) => [
                'bank' => $row['bank'] ?? '',
                'norek' => $row['norek'] ?? '',
                'nama' => $row['nama'] ?? '',
            ])
            ->values()
            ->all();

        unset($data['rekening']);

        return $data;
    }
}

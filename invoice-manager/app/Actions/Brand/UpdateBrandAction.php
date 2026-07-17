<?php

namespace App\Actions\Brand;

use App\Actions\Brand\Concerns\StoresBrandFiles;
use App\Models\Brand;

class UpdateBrandAction
{
    use StoresBrandFiles;

    public function execute(Brand $brand, array $data): Brand
    {
        $data = $this->storeBrandFiles($data, $brand);
        $data = $this->normalizeRekening($data);

        unset($data['code']);

        $brand->update($data);

        return $brand->refresh();
    }
}

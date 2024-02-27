<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\CertificateType;
class CreateCertificateTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = ['BS', 'MS','PhD'];
        foreach ($array as $element) {
            CertificateType::create([
                'name' => $element,
              
            ]);
        }

    }
}

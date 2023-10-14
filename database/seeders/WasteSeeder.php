<?php

namespace Database\Seeders;

use App\Helpers\FileHelper;
use App\Models\File;
use App\Models\Waste;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WasteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Sampah Organik',
                'desc' => 'Sampah organik merupakan sampah yang sifatnya mudah terurai di alam (mudah busuk) seperti sisa makanan, daun-daunan, atau ranting pohon. Sampah organik umumnya diwadahi dengan tempat sampah berwarna hijau. Dengan memisahkan sampah organik dalam wadah tersendiri, maka dapat memudahkan sampah organik diproses menjadi pupuk kompos.',
                'price' => 3000,
                'file_id' => File::all()->random(1)->first()->id,
            ],
            [
                'name' => 'Sampah Anorganik',
                'desc' => 'Sampah anorganik merupakan sampah yang sifatnya lebih sulit diurai seperti sampah plastik, kaleng, dan styrofoam. Sampah anorganik umumnya diwadahi dengan tempat sampah berwarna kuning. Dengan adanya tempat sampah khusus maka dapat mempermudah pemanfaatan sampah anorganik sebagai kerajinan daur ulang atau daur ulang di pabrik.',
                'price' => 4000,
                'file_id' => File::all()->random(1)->first()->id,
            ],
            [
                'name' => 'Sampah Bahan Berbahaya dan Beracun (B3)',
                'desc' => 'Sampah B3 umumnya diwadahi dengan tempat sampah berwarna merah. Sampah B3 merupakan sampah yang dapat membahayakan manusia, hewan, atau lingkungan sekitar. Contoh sampah B3 yaitu sampah kaca, kemasan detergen atau pembersih lainnya, serta pembasmi serangga dan sejenisnya. Agar meminimalisir dampak yang mungkin ditimbulkan, sampah B3 perlu dikelompokkan secara khusus dalam satu wadah.',
                'price' => 6000,
                'file_id' => File::all()->random(1)->first()->id,
            ],
            [
                'name' => 'Sampah Kertas',
                'desc' => 'Sampah kertas juga merupakan jenis sampah yang dapat dipilah secara khusus dalam wadah tempat sampah berwarna biru.Pemilahan sampah kertas berguna untuk memudahkan proses daur ulang kertas. Karton, potongan kertas, pamflet, bungkus kemasan berbahan kertas, dan buku juga termasuk dalam jenis sampah kertas.',
                'price' => 5000,
                'file_id' => File::all()->random(1)->first()->id,
            ],
            [
                'name' => 'Sampah Residu',
                'desc' => 'Sampah residu merupakan sampah sisa di luar keempat jenis sampah di atas. Tempat sampah yang diperuntukan bagi tempat sampah residu umumnya berwarna abu-abu. Contoh sampah residu yaitu seperti popok bekas, bekas pembalut, bekas permen karet, atau puntung rokok. Setelah mengenal kelima jenis sampah di atas, semoga Sobat SMP dapat mengelompokkan sampah-sampah yang hendak dibuang dengan tepat ya. Akan lebih baik lagi kalau Sobat SMP dapat memilah sampah yang ada di rumah sesuai dengan jenis sehingga memudahkan proses daur ulang. ',
                'price' => 8000,
                'file_id' => File::all()->random(1)->first()->id,
            ],
        ];

        foreach ($data as $item) {
            Waste::create($item);
        }
    }
}

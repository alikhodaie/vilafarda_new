<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinces = [
            [
                'name' => 'آذربایجان شرقی',
                'code' => '041',
                'latitude' => '38.052468',
                'longitude' => '46.284993',
            ],
            [
                'name' => 'آذربایجان غربی',
                'code' => '044',
                'latitude' => '37.529607',
                'longitude' => '45.046549',
            ],
            [
                'name' => 'اردبیل',
                'code' => '045',
                'latitude' => '38.246471',
                'longitude' => '48.295052',
            ],
            [
                'name' => 'اصفهان',
                'code' => '031',
                'latitude' => '32.65139',
                'longitude' => '51.679192',
            ],
            [
                'name' => 'البرز',
                'code' => '026',
                'latitude' => '35.821433',
                'longitude' => '50.962486',
            ],
            [
                'name' => 'ایلام',
                'code' => '084',
                'latitude' => '33.638531',
                'longitude' => '46.422649',
            ],
            [
                'name' => 'بوشهر',
                'code' => '077',
                'latitude' => '28.922041',
                'longitude' => '50.833092',
            ],
            [
                'name' => 'تهران',
                'code' => '021',
                'latitude' => '35.699731',
                'longitude' => '51.33805',
            ],
            [
                'name' => 'چهارمحال و بختیاری',
                'code' => '038',
                'latitude' => '32.355594',
                'longitude' => '50.827427',
            ],
            [
                'name' => 'خراسان جنوبی',
                'code' => '056',
                'latitude' => '32.846216',
                'longitude' => '59.291142',
            ],
            [
                'name' => 'خراسان رضوی',
                'code' => '051',
                'latitude' => '36.287961',
                'longitude' => '59.615753',
            ],
            [
                'name' => 'خراسان شمالی',
                'code' => '058',
                'latitude' => '37.452438',
                'longitude' => '57.323518',
            ],
            [
                'name' => 'خوزستان',
                'code' => '061',
                'latitude' => '31.531716',
                'longitude' => '49.880328',
            ],
            [
                'name' => 'زنجان',
                'code' => '024',
                'latitude' => '36.67094',
                'longitude' => '48.485111',
            ],
            [
                'name' => 'سمنان',
                'code' => '023',
                'latitude' => '35.572269',
                'longitude' => '53.396049',
            ],
            [
                'name' => 'سیستان و بلوچستان',
                'code' => '054',
                'latitude' => '29.454579',
                'longitude' => '60.853647',
            ],
            [
                'name' => 'فارس',
                'code' => '071',
                'latitude' => '29.617247',
                'longitude' => '52.543422',
            ],
            [
                'name' => 'قزوین',
                'code' => '028',
                'latitude' => '36.266819',
                'longitude' => '50.003811',
            ],
            [
                'name' => 'قم',
                'code' => '025',
                'latitude' => '34.643711',
                'longitude' => '50.89064',
            ],
            [
                'name' => 'کردستان',
                'code' => '087',
                'latitude' => '35.302942',
                'longitude' => '47.002631',
            ],
            [
                'name' => 'کرمان',
                'code' => '034',
                'latitude' => '30.28027',
                'longitude' => '57.06702',
            ],
            [
                'name' => 'کرمانشاه',
                'code' => '083',
                'latitude' => '34.346481',
                'longitude' => '46.420559',
            ],
            [
                'name' => 'کهگیلویه و بویراحمد',
                'code' => '074',
                'latitude' => '30.657075',
                'longitude' => '51.600294',
            ],
            [
                'name' => 'گلستان',
                'code' => '017',
                'latitude' => '36.863391',
                'longitude' => '54.448578',
            ],
            [
                'name' => 'گیلان',
                'code' => '013',
                'latitude' => '37.223431',
                'longitude' => '49.635509',
            ],
            [
                'name' => 'لرستان',
                'code' => '066',
                'latitude' => '33.466667',
                'longitude' => '48.35',
            ],
            [
                'name' => 'مازندران',
                'code' => '011',
                'latitude' => '36.565833',
                'longitude' => '53.059722',
            ],
            [
                'name' => 'مرکزی',
                'code' => '086',
                'latitude' => '34.080011',
                'longitude' => '49.677233',
            ],
            [
                'name' => 'هرمزگان',
                'code' => '076',
                'latitude' => '27.183333',
                'longitude' => '56.266667',
            ],
            [
                'name' => 'همدان',
                'code' => '081',
                'latitude' => '34.798439',
                'longitude' => '48.514939',
            ],
            [
                'name' => 'یزد',
                'code' => '035',
                'latitude' => '31.893664',
                'longitude' => '54.369836',
            ],
        ];
        DB::table('provinces')->insert($provinces);
    }
}

<?php

use App\AdPlacement;
use App\Media;
use App\Format;
use App\AdNetwork;
use App\BroadcastingArea;
use App\Frequency;
use App\Target;
use App\Theme;
use App\Support;
use App\Category;

class AdPlacementSeeder extends BaseSeeder
{

    private $medias = [
        [
            'name' => 'Madame Figaro',
            'support_id' => '2',
            'cover' => 'daxueconsulting-china-fashion-magazines-.jpg',
            'datas' => '437 124 exemplaires',
            'ad_placements' => [
                [
                    'name' => 'Page entière Rubrique Décoration',
                    'description' => 'Bénéficiez d\'une visibilité importante dans notre rubrique décoration, avec une page entière positionnée à côté d\'un article sur les tendances jardin 2016',
                    'price' => 5500,
                    'minimum_price' => 3250,
                    'type' => AdPlacement::TYPE_HYBRID,
                    'position' => 'Page 15, Rubrique Déco'
                ],
                [
                    'name' => '1/2 page Rubrique Beauté',
                    'description' => 'Bénéficiez d\'une visibilité importante dans notre rubrique beauté, avec une demie-page positionnée à côté d\'un article sur les secrets du contouring',
                    'price' => 7500,
                    'minimum_price' => 4250,
                    'type' => AdPlacement::TYPE_BOOKING,
                    'position' => 'Page 13, Rubrique Beauté'
                ],
            ]
        ],
        [
            'name' => 'JCDecaux',
            'support_id' => '1',
            'cover' => 'major-8.jpg',
            'datas' => '6450 vitrines',
            'ad_placements' => [
                [
                    'name' => 'Major 8+ (8m2)',
                    'description' => '754 millions de contacts délivrés : le dispositif Vitrine 8m2 le plus puissant de France',
                    'price' => 600000,
                    'minimum_price' => 600000,
                    'type' => AdPlacement::TYPE_AUCTION,
                    'position' => ''
                ],
            ]
        ],
        [
            'name' => 'Auto Plus',
            'support_id' => '2',
            'cover' => 'original.5240.demi.jpg',
            'datas' => '278 576 exemplaires',
            'ad_placements' => [
                [
                    'name' => 'Page entière - Auto Plus occasions',
                    'description' => 'Mettez en avant vos produits dans la notre magazine dédiées aux occasions automobiles',
                    'price' => 8900,
                    'minimum_price' => 6000,
                    'type' => AdPlacement::TYPE_HYBRID,
                    'position' => 'Page 8'
                ],
            ]
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('TRUNCATE ad_placement RESTART IDENTITY CASCADE');
        DB::statement('TRUNCATE media RESTART IDENTITY CASCADE');

        foreach ($this->medias as $media) {
            $support = Support::find($media['support_id']);
            $category = Category::where('support_id', $support->id)->orderByRaw("random()")->first();
            $theme = Theme::where('support_id', $support->id)->orderByRaw("random()")->first();
            $mediaObj = Media::create([
                'name' => $media['name'],
                'ad_network_id' => AdNetwork::orderByRaw("random()")->first()->id,
                'broadcasting_area_id' => BroadcastingArea::orderByRaw("random()")->first()->id,
                'support_id' => $support->id,
                'category_id' => $category === null ? null : $category->id,
                'theme_id' =>  $theme === null ? null : $theme->id,
                'frequency_id' => Frequency::orderByRaw("random()")->first()->id,
                'cover' => $media['cover'],
                'datas' => $media['datas'],
            ]);
            $mediaObj->targets()->attach(Target::orderByRaw("random()")->first()->id);
            $mediaObj->targets()->attach(Target::orderByRaw("random()")->first()->id);

            foreach ($media['ad_placements'] as $ad_placement) {
                for ($i = 1; $i <= 10; $i++) {
                    $start = $this->faker->dateTimeBetween('-2 days', '-1 days');
                    $end = $this->faker->dateTimeBetween($start, '+7 days');
                    $technical_deadline = $this->faker->dateTimeBetween($end, '+9 days');
                    $broadcasting_date = $this->faker->dateTimeBetween($technical_deadline, '+12 days');

                    $format = Format::where('support_id', $mediaObj->support_id)->orderByRaw("random()")->first();

                    AdPlacement::create([
                        'starting_at' => $start,
                        'ending_at' => $end,
                        'technical_deadline' => $technical_deadline,
                        'locking_up' => $technical_deadline,
                        'broadcasting_date' => $broadcasting_date,
                        'name' => $ad_placement['name'],
                        'description' => $ad_placement['description'],
                        'price' => $ad_placement['price'],
                        'minimum_price' => $ad_placement['minimum_price'],
                        'type' => $ad_placement['type'],
                        'media_id' => $mediaObj->id,
                        'edition' => ($support->name === 'Affichage' ? null : rand(1, 300)),
                        'format_id' => $format === null ? null : $format->id,
                        'position' => $this->faker->word,
                    ]);
                }
            }
        }
    }

}

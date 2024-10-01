<?php

use Illuminate\Database\Seeder;

class ConversionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('convertions')->insert([
            'id' => 1,
            'amountIn' => 1,
            'unitsIn' => 'kg',
            'amountOut' => 1000,
            'unitsOut' => 'gr'
        ]);

        \DB::table('convertions')->insert([
            'id' => 2,
            'amountIn' => 1,
            'unitsIn' => 'kg',
            'amountOut' => 2.20462,
            'unitsOut' => 'Lb'
        ]);

        \DB::table('convertions')->insert([
            'id' => 3,
            'amountIn' => 1,
            'unitsIn' => 'lb',
            'amountOut' => 0.4535920,
            'unitsOut' => 'kg'
        ]);

        \DB::table('convertions')->insert([
            'id' =>4 ,
            'amountIn' => 1,
            'unitsIn' => 'gr',
            'amountOut' => 0.001,
            'unitsOut' => 'Kg'
        ]);

        \DB::table('convertions')->insert([
            'id' => 5,
            'amountIn' => 1,
            'unitsIn' => 'gr',
            'amountOut' => 0.00220462,
            'unitsOut' => 'Lb'
        ]);

        \DB::table('convertions')->insert([
            'id' => 6,
            'amountIn' => 1,
            'unitsIn' => 'lb',
            'amountOut' => 453.59200,
            'unitsOut' => 'gr'
        ]);


        \DB::table('convertions')->insert([
            'id' => 7,
            'amountIn' => 1,
            'unitsIn' => 'gl',
            'amountOut' => 3.78541,
            'unitsOut' => 'l'
        ]);

        \DB::table('convertions')->insert([
            'id' => 8,
            'amountIn' => 1,
            'unitsIn' => 'gl',
            'amountOut' => 768,
            'unitsOut' => 'tsp'
        ]);

        \DB::table('convertions')->insert([
            'id' => 9,
            'amountIn' => 1,
            'unitsIn' => 'gl',
            'amountOut' => 256,
            'unitsOut' => 'tbs'
        ]);

        \DB::table('convertions')->insert([
            'id' => 10,
            'amountIn' => 1,
            'unitsIn' => 'gl',
            'amountOut' => 15.7725,
            'unitsOut' => 'cup'
        ]);
        \DB::table('convertions')->insert([
            'id' => 11,
            'amountIn' => 1,
            'unitsIn' => 'gl',
            'amountOut' => 3785.41,
            'unitsOut' => 'ml'
        ]);

        \DB::table('convertions')->insert([
            'id' => 12,
            'amountIn' => 1,
            'unitsIn' => 'l',
            'amountOut' => 0.264172,
            'unitsOut' => 'gl'
        ]);

        \DB::table('convertions')->insert([
            'id' => 13,
            'amountIn' => 1,
            'unitsIn' => 'l',
            'amountOut' => 202.884,
            'unitsOut' => 'tsp'
        ]);

        \DB::table('convertions')->insert([
            'id' => 14,
            'amountIn' => 1,
            'unitsIn' => 'l',
            'amountOut' => 67.628,
            'unitsOut' => 'tbs'
        ]);

        \DB::table('convertions')->insert([
            'id' => 15,
            'amountIn' => 1,
            'unitsIn' => 'l',
            'amountOut' => 4.16667,
            'unitsOut' => 'cup'
        ]);
        \DB::table('convertions')->insert([
            'id' => 16,
             'amountIn' => 1,
            'unitsIn' => 'l',
            'amountOut' => 1000,
            'unitsOut' => 'ml'
        ]);


        \DB::table('convertions')->insert([
            'id' => 17,
            'amountIn' => 1,
            'unitsIn' => 'cup',
            'amountOut' => 0.0634013,
            'unitsOut' => 'gl'
        ]);

        \DB::table('convertions')->insert([
            'id' => 18,
             'amountIn' => 1,
            'unitsIn' => 'cup',
            'amountOut' => 48.6922,
            'unitsOut' => 'tsp'
        ]);

        \DB::table('convertions')->insert([
            'id' => 19,
            'amountIn' => 1,
            'unitsIn' => 'cup',
            'amountOut' => 16.2307,
            'unitsOut' => 'tbs'
        ]);

        \DB::table('convertions')->insert([
            'id' => 20,
             'amountIn' => 1,
            'unitsIn' => 'cup',
            'amountOut' => 0.24,
            'unitsOut' => 'l'
        ]);
        \DB::table('convertions')->insert([
            'id' => 21,
            'amountIn' => 1,
            'unitsIn' => 'cup',
            'amountOut' => 240,
            'unitsOut' => 'ml'
        ]);



        \DB::table('convertions')->insert([
            'id' => 22,
             'amountIn' => 1,
            'unitsIn' => 'tsp',
            'amountOut' => 0.00130208,
            'unitsOut' => 'gl'
        ]);

        \DB::table('convertions')->insert([
            'id' =>23 ,
             'amountIn' => 1,
            'unitsIn' => 'tsp',
            'amountOut' => 0.0205372,
            'unitsOut' => 'cup'
        ]);

        \DB::table('convertions')->insert([
            'id' =>24 ,
            'amountIn' => 1,
            'unitsIn' => 'tsp',
            'amountOut' => 0.333333,
            'unitsOut' => 'tbs'
        ]);

        \DB::table('convertions')->insert([
            'id' => 25,
             'amountIn' => 1,
            'unitsIn' => 'tsp',
            'amountOut' => 0.00492892,
            'unitsOut' => 'l'
        ]);
        \DB::table('convertions')->insert([
            'id' =>26 ,
             'amountIn' => 1,
            'unitsIn' => 'tps',
            'amountOut' => 4.92892,
            'unitsOut' => 'ml'
        ]);


        \DB::table('convertions')->insert([
            'id' => 27,
             'amountIn' => 1,
            'unitsIn' => 'tbs',
            'amountOut' => 0.00390625,
            'unitsOut' => 'gl'
        ]);

        \DB::table('convertions')->insert([
            'id' => 28,
             'amountIn' => 1,
            'unitsIn' => 'tbs',
            'amountOut' => 0.0616115,
            'unitsOut' => 'cup'
        ]);

        \DB::table('convertions')->insert([
            'id' => 29,
            'amountIn' => 1,
            'unitsIn' => 'tbs',
            'amountOut' => 3,
            'unitsOut' => 'tsp'
        ]);

        \DB::table('convertions')->insert([
            'id' => 30,
            'amountIn' => 1,
            'unitsIn' => 'tbs',
            'amountOut' => 0.0147868,
            'unitsOut' => 'l'
        ]);
        \DB::table('convertions')->insert([
            'id' =>31 ,
            'amountIn' => 1,
            'unitsIn' => 'tbs',
            'amountOut' => 14.7868,
            'unitsOut' => 'ml'
        ]);


        \DB::table('convertions')->insert([
            'id' =>32 ,
            'amountIn' => 1,
            'unitsIn' => 'ml',
            'amountOut' => 0.000264172,
            'unitsOut' => 'gl'
        ]);

        \DB::table('convertions')->insert([
            'id' =>33 ,
            'amountIn' => 1,
            'unitsIn' => 'ml',
            'amountOut' => 0.00416667,
            'unitsOut' => 'cup'
        ]);

        \DB::table('convertions')->insert([
            'id' => 34,
            'amountIn' => 1,
            'unitsIn' => 'ml',
            'amountOut' => 0.202884,
            'unitsOut' => 'tsp'
        ]);

        \DB::table('convertions')->insert([
            'id' => 35,
            'amountIn' => 1,
            'unitsIn' => 'ml',
            'amountOut' => 0.001,
            'unitsOut' => 'l'
        ]);

        \DB::table('convertions')->insert([
            'id' => 36,
             'amountIn' => 1,
            'unitsIn' => 'ml',
            'amountOut' => 0.067628,
            'unitsOut' => 'tbs'
        ]);
    }
}

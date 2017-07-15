<?php

use App\Acquisition;
use App\AdPlacement;
use App\Invoice;

class AcquisitionSeeder extends BaseSeeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('TRUNCATE invoice RESTART IDENTITY CASCADE');
		DB::statement('TRUNCATE acquisition RESTART IDENTITY CASCADE');

		/*for ($i = 1; $i < AdPlacement::count(); $i++) {
			$ad_placement = AdPlacement::find($i);
			$price = $this->faker->randomFloat($nbMaxDecimals = NULL, $min = 100.23, $max = 243.58);
			$technical_support_price = $this->faker->randomFloat($nbMaxDecimals = NULL, $min = 10.23, $max = 30.58);
			$technical_support_id = rand (1, 3);
			$template_id = null;
			if($technical_support_id == 0) {
				$template_id = rand (1, 3);
			}

			if ($ad_placement->type == 'offer') {
				$invoice = Invoice::create([
					'name' => $this->faker->word + $price + $technical_support_price,
					'vat_rate' => 0.20,
					'amount' => $price + $technical_support_price,
					'status' => Invoice::INVOICE_PENDING,
				]);

				$acquisition = Acquisition::create([
					'user_id' => $i,
					'ad_placement_id' => $ad_placement->id,
					'price' => $price,
					'technical_support_price' => $technical_support_price,
					'technical_support_id' => $technical_support_id,
					'template_id' => $template_id,
					'invoice_id' => $invoice->id,
					'charge_status' => 'success',
					'charge_id' => '1234',
					'vat_rate' => 0.20,
				]);
			}
		}*/
	}

}

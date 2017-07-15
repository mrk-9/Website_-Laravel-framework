<?php

use App\Booking;
use App\AdPlacement;

class BookingSeeder extends BaseSeeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Truncate is impossible due to "ad_placement_lock_booking_id_foreign" foreign key
		// We assume there isn't any ad_placement.lock_booking_id
		DB::statement('DELETE FROM booking where 1 = 1');

		/*for ($i = 1; $i < AdPlacement::count(); $i++) {
			if (AdPlacement::find($i)->type == 'booking') {
				$booking = Booking::create([
					'user_id' => $i,
					'ad_placement_id' => $i
				]);
			}
		}*/
	}
}



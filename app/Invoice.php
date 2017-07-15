<?php namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Invoice extends BaseModel
{

	use SoftDeletes;

	const INVOICE_PENDING = 'invoice_pending';
	const INVOICE_PAID = 'invoice_paid';

	protected $table = 'invoice';

	public static function createFromAcquisition(Acquisition $acquisition) {
		$now = Carbon::now();
		$lastInvoiceName= Invoice::orderBy('id', 'DESC')->pluck('name');
		$id = 1;

		if(isset($lastInvoiceName)) {
			list($year, $lastId) = explode("-", substr($lastInvoiceName,1));

			if(intval($year) == $now->year) {
				$id = intval($lastId) + 1;
			}
		}

		$invoice = new Invoice();
		$invoice->name = "F".$now->year."-".$id;
		$invoice->amount = $acquisition->total;
		$invoice->buyer_name = $acquisition->user->buyer->name;
		$invoice->buyer_address = $acquisition->user->buyer->address;
		$invoice->buyer_zipcode = $acquisition->user->buyer->zipcode;
		$invoice->buyer_city = $acquisition->user->buyer->city;
		$invoice->save();
		return $invoice;
	}

	protected $fillable = [
		'vat_rate',
		'amount',
		'status',
		'name',
	];

	public function acquisition()
	{
		return $this->hasOne('App\Acquisition');
	}
}

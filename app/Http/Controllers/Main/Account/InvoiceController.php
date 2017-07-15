<?php namespace App\Http\Controllers\Main\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Invoice;
use Auth;
use PDF;

class InvoiceController extends Controller
{

    const PAGINATOR_NB_BY_PAGE = 10;

    public function getInvoices()
    {
        $query = Invoice::select('invoice.*')->join('acquisition', function ($join) {
            $join->on('acquisition.invoice_id', '=', 'invoice.id')
                ->where('acquisition.user_id', '=', Auth::user()->get()->id);
        });
        $paginator = $query->paginate(self::PAGINATOR_NB_BY_PAGE);
        return view('main.account.invoices', compact('paginator'));
    }

    public function get(Invoice $invoice)
    {
        if($invoice->acquisition->user_id !== Auth::user()->get()->id) {
            return response('Unauthorized.', 401);
        }
        return $this->generateFile($invoice)->stream('Facture-'. $invoice->name .'.pdf');
    }

    protected function generateFile(Invoice $invoice)
    {
        // See the file vendor/knplabs/knp-snappy/src/Knp/Snappy/Pdf.php for more options
        $pdf_options = [
            'viewport-size' => "1600px",
            'margin-bottom' => null,
            'margin-left' => null,
            'margin-right' => null,
            'margin-top' => null,
        ];

        return PDF::loadView('main.account.invoices-details', compact('invoice'))->setOptions($pdf_options);
    }


}

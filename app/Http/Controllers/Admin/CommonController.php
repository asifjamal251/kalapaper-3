<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Party;
use App\Models\PurchaseOrderItem;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller{

    public function clientList(Request $request){
        if (!$request->ajax()) {
            return response()->json(['message' => 'Invalid request'], 400);
        }

        $page        = max((int) $request->page, 1);
        $resultCount = 15;
        $offset      = ($page - 1) * $resultCount;
        $term        = $request->term ?? '';
        $type        = $request->type ?? null;

        // Base query
        $query = Party::query()
        ->when($term, function ($q) use ($term) {
            $q->where('company_name', 'LIKE', "%{$term}%");
        })
        ->when($type, function ($q) use ($type) {
                $q->where('type', $type);   // 🔥 CLIENT / VENDOR / FIRM
            });

        // Total count
        $count = $query->count();

        // Paginated result
        $clients = $query
        ->orderBy('company_name', 'asc')
        ->skip($offset)
        ->take($resultCount)
        ->get([
            'id',
            DB::raw('company_name as text')
        ]);

        return response()->json([
            'results' => $clients,
            'pagination' => [
                'more' => ($offset + $resultCount) < $count
            ]
        ]);
    }

    // public function clientList(Request $request){
    //     if ($request->ajax()) {
    //         $page = $request->page;
    //         $resultCount = 15;

    //         $offset = ($page - 1) * $resultCount;

    //         $name = Client::orderBy('company_name', 'asc')->where('company_name', 'LIKE', '%' . $request->term. '%')
    //         ->orderBy('created_at', 'asc')
    //         ->skip($offset)
    //         ->take($resultCount)
    //         ->selectRaw('id, company_name as text')
    //         ->get();

    //         $count = Count(Client::orderBy('company_name', 'asc')->where('company_name', 'LIKE', '%' . $request->term. '%')
    //             ->orderBy('created_at', 'asc')
    //             ->selectRaw('id, company_name as text')
    //             ->get());

    //         $endCount = $offset + $resultCount;
    //         $morePages = $count > $endCount;

    //         $results = [
    //           'results' => $name,
    //           'pagination' => [
    //               'more' => $morePages
    //           ]
    //       ];

    //         return response()->json($results);
    //     }
    //     return response()->json('oops');
    // }

    public function vendorList(Request $request){
        if ($request->ajax()) {
            $page = $request->page;
            $resultCount = 15;

            $offset = ($page - 1) * $resultCount;

            $name = Vendor::orderBy('company_name', 'asc')->where('company_name', 'LIKE', '%' . $request->term. '%')
            ->orderBy('company_name', 'asc')
            ->skip($offset)
            ->take($resultCount)
            ->selectRaw('id, company_name as text')
            ->get();

            $count = Count(Vendor::orderBy('company_name', 'asc')->where('company_name', 'LIKE', '%' . $request->term. '%')
                ->orderBy('company_name', 'asc')
                ->selectRaw('id, company_name as text')
                ->get());

            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;

            $results = [
              'results' => $name,
              'pagination' => [
                  'more' => $morePages
              ]
          ];

          return response()->json($results);
      }
      return response()->json('oops');
  }

  public function apiPincode($pincode){
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.postalpincode.in/pincode/{$pincode}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        return response()->json([
            'success' => false,
            'message' => $error,
        ]);
    }

    $data = json_decode($response, true);

    if (!isset($data[0]) || $data[0]['Status'] !== 'Success' || empty($data[0]['PostOffice'])) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid pincode',
        ]);
    }

    $postOffices = $data[0]['PostOffice'];

    $state = $postOffices[0]['State'] ?? null;
    $district = $postOffices[0]['District'] ?? null;

    $cities = collect($postOffices)->pluck('Block')->filter()->unique()->values();
    $names = collect($postOffices)->pluck('Name')->filter()->unique()->values();

    return response()->json([
        'success' => true,
        'state' => $state,
        'district' => $district,
        'cities' => $cities,
        'names' => $names,
    ]);
}


    public function poItemList(Request $request){
        $poid    = $request->poid;
        $quality = $request->quality;
        $gsm     = $request->gsm;
        $width   = $request->width;
        $term    = $request->term;
        $soldTo  = $request->soldTo;

        $query = PurchaseOrderItem::query()
        ->with(['quality:id,name,code'])
        ->where('purchase_order_id', $poid)
        ->where('sold_to', $soldTo);

        // match quality id
        if (!empty($quality)) {
            $query->where('quality_id', $quality);
        }

        // match gsm
        if (!empty($gsm)) {
            $query->where('gsm', $gsm);
        }

        // match width (66 vs 66.00 fix)
        if (!empty($width)) {
            $query->whereRaw('ROUND(width,2) = ROUND(?,2)', [(float)$width]);
        }

        // 🔍 Search in: quality code/name + gsm + width + length
        if (!empty($term)) {
            $query->where(function ($q) use ($term) {

                // search quality name/code
                $q->whereHas('quality', function ($qq) use ($term) {
                    $qq->where('name', 'like', "%{$term}%")
                    ->orWhere('code', 'like', "%{$term}%");
                });

                // search gsm/width/length
                $q->orWhere('gsm', 'like', "%{$term}%")
                ->orWhere('width_cm', 'like', "%{$term}%")
                ->orWhere('length_cm', 'like', "%{$term}%");
            });
        }

        $items = $query->orderBy('id', 'desc')->paginate(10);

        $results = $items->map(function ($item) {

            $qualityText = $item->quality
            ? ($item->quality->code ? $item->quality->code : $item->quality->name)
            : 'N/A';

            $text = "{$qualityText}({$item->gsm}) - {$item->length_cm}x{$item->width_cm}";

            return [
                'id' => $item->id,
                'text' => $text,
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $items->hasMorePages()
            ]
        ]);
    }

    public function poSoldTolist(Request $request){
        $poid = $request->poid;

        $soldTo = PurchaseOrderItem::where('purchase_order_id', $poid)
            ->pluck('sold_to')
            ->filter()
            ->unique()
            ->values();

        $parties = Party::whereIn('id', $soldTo)
            ->orderBy('company_name','asc')
            ->get(['id','company_name']);

        return response()->json($parties);
    }

    public function poItemSingle(Request $request){
        $item = PurchaseOrderItem::where('id', $request->id)->first();
        return response()->json([
            "datas" => $item,
        ]);
    }

}

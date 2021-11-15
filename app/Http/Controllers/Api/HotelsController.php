<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SimbaseApiRequest;
use App\Services\simBaseAuth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HotelsController extends Controller
{

    protected $api;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->api = new simBaseAuth();
    }

    public function index(Request $request)
    {

        return response()->json(
            $this->api->callFunction('f_api_return_accommodations_data')
        );
    }

    public function store(Request $request)
    {

        $data = $request->only(["accommodation", "number_of_rooms", "room_type", "check_in_date", "nts"]);
        $date = Carbon::createFromDate($data['check_in_date']);
        $datenull = new \DateTime('01.01.1970');

        $days = $date->diff($datenull)->days;


        return response()->json(
            $this->api->callFunction('f_api_save_accommodations_data', [
                'acc_id' => $data['accommodation'],
                'number' => $data['number_of_rooms'],
                'room type' => $data['room_type'],
                'chek in' => $days,
                'nights' => $data['nts'],
            ])
        );
    }
}

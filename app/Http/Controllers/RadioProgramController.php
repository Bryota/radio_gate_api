<?php

namespace App\Http\Controllers;

use App\Services\Radio\RadioProgramService;
use App\Http\Requests\RadioProgramRequest;

class RadioProgramController extends Controller
{
    // /**
    //  * @var RadioStationService $radio_station RadioStationServiceインスタンス
    //  */
    // private $radio_station;

    // public function __construct(RadioStationService $radio_station)
    // {
    //     $this->radio_station = $radio_station;
    // }

    // /**
    //  * ラジオ局作成
    //  *
    //  * @param  RadioStationRequest $request ラジオ局作成リクエストデータ
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(RadioStationRequest $request)
    // {
    //     $radio_station = $this->radio_station->storeRadioStation($request);
    //     if ($radio_station) {
    //         return response()->json([
    //             'message' => 'ラジオ局が作成されました。'
    //         ], 201, [], JSON_UNESCAPED_UNICODE);
    //     } else {
    //         return response()->json([
    //             'message' => 'ラジオ局の作成に失敗しました。'
    //         ], 409, [], JSON_UNESCAPED_UNICODE);
    //     }
    // }
}

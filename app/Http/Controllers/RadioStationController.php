<?php

namespace App\Http\Controllers;

use App\Services\Radio\RadioStationService;
use App\Http\Requests\StoreRadioStaionRequest;

class RadioStationController extends Controller
{
    /**
     * @var RadioStationService $radio_station RadioStationServiceインスタンス
     */
    private $radio_station;

    public function __construct(RadioStationService $radio_station)
    {
        $this->radio_station = $radio_station;
    }

    /**
     * ラジオ局一覧取得
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $radio_staitons = $this->radio_station->getAllRadioStations();
        if ($radio_staitons) {
            return response()->json([
                'radio_stations' => $radio_staitons
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'message' => 'ラジオ局一覧の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * ラジオ局作成
     *
     * @param  StoreRadioStaionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRadioStaionRequest $request)
    {
        $radio_station = $this->radio_station->storeRadioStation($request);
        if ($radio_station) {
            return response()->json([
                'message' => 'ラジオ局が作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } else {
            return response()->json([
                'message' => 'ラジオ局の作成に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

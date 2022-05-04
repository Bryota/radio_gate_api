<?php

namespace App\Http\Controllers;

use App\Services\Radio\RadioStationService;
use App\Http\Requests\RadioStationRequest;
use Illuminate\Support\Facades\DB;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $radio_staitons = $this->radio_station->getAllRadioStations();
            return response()->json([
                'radio_stations' => $radio_staitons
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'ラジオ局一覧の取得に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * ラジオ局作成
     *
     * @param  RadioStationRequest $request ラジオ局作成リクエストデータ
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RadioStationRequest $request)
    {
        try {
            // TODO: facade使うと誰かに怒られるかも
            DB::beginTransaction();
            $this->radio_station->storeRadioStation($request);
            DB::commit();
            return response()->json([
                'message' => 'ラジオ局が作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'ラジオ局の作成に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * ラジオ局更新
     *
     * @param RadioStationRequest $request ラジオ局更新リクエストデータ
     * @param int $radio_station_id ラジオ局ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(RadioStationRequest $request, int $radio_station_id)
    {
        try {
            // TODO: facade使うと誰かに怒られるかも
            DB::beginTransaction();
            $this->radio_station->updateRadioStation($request, $radio_station_id);
            DB::commit();
            return response()->json([
                'message' => 'ラジオ局が更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'ラジオ局の更新に失敗しました。'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * ラジオ局削除（1局のみ）
     *
     * @param int $radio_station_id ラジオ局ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $radio_station_id)
    {
        try {
            $this->radio_station->deleteRadioStation($radio_station_id);
            return response()->json([
                'message' => 'ラジオ局が削除されました。'
            ], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'ラジオ局の削除に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}

<?php

namespace App\Http\Controllers\Listener;

use App\Services\Radio\RadioStationService;
use App\Http\Requests\RadioStationRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RadioStationController extends Controller
{
    /**
     * @var RadioStationService $radio_station RadioStationServiceインスタンス
     */
    private $radio_station;

    /**
     * @var Connection $db_connection Connectionインスタンス
     */
    private $db_connection;

    public function __construct(RadioStationService $radio_station, Connection $db_connection)
    {
        $this->radio_station = $radio_station;
        $this->db_connection = $db_connection;
    }

    /**
     * ラジオ局一覧取得
     *
     * @param Request $request 検索用パラメーター
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $radio_staitons = $this->radio_station->getAllRadioStations($request);
            return response()->json(
                $radio_staitons,
                200,
                [],
                JSON_UNESCAPED_UNICODE
            );
        } catch (ModelNotFoundException $e) {
            Log::error('ラジオ局一覧がありませんでした。', ['error' => $e]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('ラジオ局一覧取得エラー', ['error' => $th]);
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
            $this->db_connection->beginTransaction();
            $this->radio_station->storeRadioStation($request);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'ラジオ局が作成されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            Log::error('ラジオ局作成エラー', ['error' => $th]);
            $this->db_connection->rollBack();
            return response()->json([
                'message' => 'ラジオ局の作成に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
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
            $this->db_connection->beginTransaction();
            $this->radio_station->updateRadioStation($request, $radio_station_id);
            $this->db_connection->commit();
            return response()->json([
                'message' => 'ラジオ局が更新されました。'
            ], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $e) {
            Log::error('ラジオ局がありませんでした。（更新）', ['error' => $e]);
            return response()->json([
                'message' => '該当のデータが見つかりませんでした。'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $th) {
            $this->db_connection->rollBack();
            Log::error('ラジオ局更新エラー', ['error' => $th]);
            return response()->json([
                'message' => 'ラジオ局の更新に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
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
            Log::error('ラジオ局削除エラー', ['error' => $th]);
            return response()->json([
                'message' => 'ラジオ局の削除に失敗しました。'
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}

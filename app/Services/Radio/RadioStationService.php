<?php

/**
 * ラジオ局用の機能関連のビジネスロジック
 *
 * ラジオ局の情報取得、更新、削除のビジネスロジック
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\Services\Radio;

use App\DataProviders\Repositories\RadioStationRepository;
use App\DataProviders\Models\RadioStation;
use App\Http\Requests\RadioStationRequest;
use Illuminate\Http\Request;

/**
 * ラジオ局用のサービスクラス
 *
 * @package App\Services
 * @version 1.0
 */
class RadioStationService
{
    /**
     * @var RadioStationRepository $radio_station RadioStationRepositoryインスタンス
     */
    private $radio_station;

    /**
     * コンストラクタ
     *
     * @param RadioStationRepository $radio_station RadioStationRepositoryインスタンス
     */
    public function __construct(RadioStationRepository $radio_station)
    {
        $this->radio_station = $radio_station;
    }

    /**
     * ラジオ局一覧取得
     * 
     * @param Request $request 検索用パラメーター
     * @return object ラジオ局一覧
     */
    public function getAllRadioStations(Request $request): object
    {
        return $this->radio_station->getAllRadioStations($request);
    }

    /**
     * 個別のラジオ局取得
     *
     * @param int $radio_station_id ラジオ局ID
     * @return RadioStation ラジオ局データ
     */
    public function getSingleRadioStation(int $radio_station_id): RadioStation
    {
        return $this->radio_station->getSingleRadioStation($radio_station_id);
    }

    /**
     * ラジオ局作成
     *
     * @param RadioStationRequest $request ラジオ局作成リクエストデータ
     * @return RadioStation ラジオ局生成データ
     */
    public function storeRadioStation(RadioStationRequest $request): RadioStation
    {
        return $this->radio_station->storeRadioStation($request);
    }

    /**
     * ラジオ局更新
     *
     * @param RadioStationRequest $request ラジオ局更新リクエストデータ
     * @param int $radio_station_id ラジオ局ID
     * @return bool 更新できたかどうか
     */
    public function updateRadioStation(RadioStationRequest $request, int $radio_station_id): bool
    {
        return $this->radio_station->updateRadioStation($request, $radio_station_id);
    }

    /**
     * ラジオ局削除
     *
     * @param int $radio_station_id ラジオ局ID
     * @return bool 削除できたかどうか
     */
    public function deleteRadioStation(int $radio_station_id): bool
    {
        return $this->radio_station->deleteRadioStation($radio_station_id);
    }

    /**
     * ラジオ局名取得
     * 
     * @param int $radio_station_id ラジオ局ID
     * @return string ラジオ局名
     */
    public function getRadioStationName(int $radio_station_id)
    {
        return $this->radio_station->getRadioStationName($radio_station_id);
    }
}

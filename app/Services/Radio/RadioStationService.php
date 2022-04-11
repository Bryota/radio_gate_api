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
use App\Http\Requests\StoreRadioStaionRequest;

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
     * @return object ラジオ局一覧
     */
    public function getAllRadioStations(): object
    {
        $radio_stations = $this->radio_station->getAllRadioStations();
        return $radio_stations;
    }

    /**
     * ラジオ局作成
     *
     * @param StoreRadioStaionRequest $request ラジオ局作成リクエストデータ
     * @return RadioStation ラジオ局生成データ
     */
    public function storeRadioStation(StoreRadioStaionRequest $request): RadioStation
    {
        $radio_station = $this->radio_station->storeRadioStation($request);
        return $radio_station;
    }
}

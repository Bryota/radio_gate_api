<?php

/**
 * ラジオ局用のデータリポジトリ
 *
 * DBからラジオ局の情報取得・更新、削除の責務を担う
 *
 * @author s_ryota sryotapersian@gmail.com
 * @version 1.0
 * @copyright 2022 Ryota Segawa
 */

namespace App\DataProviders\Repositories;

use App\DataProviders\Models\RadioStation;
use App\Http\Requests\RadioStationRequest;

/**
 * ラジオ局リポジトリクラス
 *
 * @package App\DataProviders\Repositories
 * @version 1.0
 */
class RadioStationRepository
{
    /**
     * @var RadioStation $radio_station RadioStationインスタンス
     */
    private $radio_station;

    /**
     * コンストラクタ
     *
     * @param RadioStation $radio_station RadioStationModel
     * @return void
     */
    public function __construct(RadioStation $radio_station)
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
        return $this->radio_station::get();
    }

    /**
     * ラジオ局作成
     *
     * @param RadioStationRequest $request ラジオ局作成リクエストデータ
     * @return RadioStation ラジオ局生成データ
     */
    public function storeRadioStation(RadioStationRequest $request): RadioStation
    {
        return $this->radio_station::create($request->all());
    }

    /**
     * ラジオ局作成
     *
     * @param RadioStationRequest $request ラジオ局更新リクエストデータ
     * @param int $radio_station_id ラジオ局ID
     * @return bool 更新できたかどうか
     */
    public function updateRadioStation(RadioStationRequest $request, int $radio_station_id): bool
    {
        $radio_station = $this->radio_station::find($radio_station_id);
        $radio_station->name = $request->name;
        return $radio_station->save();
    }

    /**
     * ラジオ局削除
     *
     * @param int $radio_station_id ラジオ局ID
     * @return int 削除した件数
     */
    public function deleteRadioStation(int $radio_station_id): int
    {
        $radio_station = $this->radio_station::find($radio_station_id);
        return $radio_station->delete();
    }
}

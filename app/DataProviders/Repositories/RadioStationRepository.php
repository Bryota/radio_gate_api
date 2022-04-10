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
use App\Http\Requests\StoreRadioStaionRequest;

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
     * ラジオ局作成
     *
     * @param StoreRadioStaionRequest $request ラジオ局作成リクエストデータ
     * @return RadioStation ラジオ局生成データ
     */
    public function storeRadioStation(StoreRadioStaionRequest $request): RadioStation
    {
        return $this->radio_station::create($request->all());
    }
}

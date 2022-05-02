<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ListenerMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string|null $corner コーナー（件名）
     */
    private ?string $corner;

    /**
     * @var string|null $full_name 本名
     */
    private ?string $full_name;

    /**
     * @var string|null $full_name_kana 本名かな
     */
    private ?string $full_name_kana;

    /**
     * @var string|null $radio_name ラジオネーム
     */
    private ?string $radio_name;

    /**
     * @var int|null $post_code 郵便番号
     */
    private ?int $post_code;

    /**
     * @var string|null $prefecture 都道府県
     */
    private ?string $prefecture;

    /**
     * @var string|null $city 市区町村
     */
    private ?string $city;

    /**
     * @var string|null $house_number 住所
     */
    private ?string $house_number;

    /**
     * @var string|null $building 建物
     */
    private ?string $building;

    /**
     * @var string|null $room_number 部屋番号
     */
    private ?string $room_number;

    /**
     * @var string|null $tel 電話番号
     */
    private ?string $tel;

    /**
     * @var string|null $email メールアドレス
     */
    private ?string $email;

    /**
     * @var string|null $content 本文
     */
    private ?string $content;

    /**
     * Create a new message instance.
     *
     * @param string|null $corner コーナー（件名）
     * @param string|null $full_name 本名
     * @param string|null $full_name_kana 本名かな
     * @param string|null $radio_name ラジオネーム
     * @param int|null    $post_code 郵便番号
     * @param string|null $prefecture 都道府県
     * @param string|null $city 市区町村
     * @param string|null $house_number 住所
     * @param string|null $building 建物
     * @param string|null $room_number 部屋番号
     * @param string|null $tel 電話番号
     * @param string|null $email メールアドレス
     * @param string|null $content 本文
     * @return void
     */
    public function __construct(
        ?string $corner = null,
        ?string $full_name = null,
        ?string $full_name_kana = null,
        ?string $radio_name = null,
        ?int    $post_code = null,
        ?string $prefecture = null,
        ?string $city = null,
        ?string $house_number = null,
        ?string $building = null,
        ?string $room_number = null,
        ?string $tel = null,
        ?string $email = null,
        ?string $content = null
    ) {
        $this->corner = $corner;
        $this->full_name = $full_name;
        $this->full_name_kana = $full_name_kana;
        $this->radio_name = $radio_name;
        $this->post_code = $post_code;
        $this->prefecture = $prefecture;
        $this->city = $city;
        $this->house_number = $house_number;
        $this->building = $building;
        $this->room_number = $room_number;
        $this->tel = $tel;
        $this->email = $email;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'))
            ->subject($this->corner)
            ->view('email.listener_message')
            ->with([
                'full_name' => $this->full_name,
                'full_name_kana' => $this->full_name_kana,
                'radio_name' => $this->radio_name,
                'post_code' => $this->post_code,
                'prefecture' => $this->prefecture,
                'city' => $this->city,
                'house_number' => $this->house_number,
                'building' => $this->building,
                'room_number' => $this->room_number,
                'tel' => $this->tel,
                'email' => $this->email,
                'content' => $this->content,
            ]);
    }

    /**
     * ListenerMessageMailインスタンスを取得
     *
     * @param string|null $corner コーナー（件名）
     * @param string|null $full_name 本名
     * @param string|null $full_name_kana 本名かな
     * @param string|null $radio_name ラジオネーム
     * @param int|null    $post_code 郵便番号
     * @param string|null $prefecture 都道府県
     * @param string|null $city 市区町村
     * @param string|null $house_number 住所
     * @param string|null $building 建物
     * @param string|null $room_number 部屋番号
     * @param string|null $tel 電話番号
     * @param string|null $email メールアドレス
     * @param string|null $content 本文
     * @return ListenerMessageMail ListenerMessageMailインスタンス
     */
    public function getSelf(
        string|null $corner,
        string|null $full_name,
        string|null $full_name_kana,
        string|null $radio_name,
        int|null    $post_code,
        string|null $prefecture,
        string|null $city,
        string|null $house_number,
        string|null $building,
        string|null $room_number,
        string|null $tel,
        string|null $email,
        string|null $content
    ) {
        return new ListenerMessageMail(
            $corner,
            $full_name,
            $full_name_kana,
            $radio_name,
            $post_code,
            $prefecture,
            $city,
            $house_number,
            $building,
            $room_number,
            $tel,
            $email,
            $content
        );
    }
}

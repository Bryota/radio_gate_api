{{ $prefecture }}
RN: {{ $radio_name }}

{{ $content }}

----------------------------------------------------------------
@if($listener_info_flag)
〒{{ $post_code }}
住所:{{ $prefecture }}　{{ $city }}　{{ $house_number }}　{{ $building }}　{{ $room_number }}
本名:{{ $full_name}}（{{ $full_name_kana}}）
@endif
@if($tel_flag)
電話番号:{{ $tel }}
@endif
メールアドレス:{{ $email }}
<p>{{ $prefecture }}</p>
<p>RN: {{ $radio_name }}</p>
<div>
    {{ $content }}
</div>

<div>
    <p>----------------------------------------------------------------</p>
    <p>〒{{ $post_code }}</p>
    <p>住所:{{ $prefecture }}　{{ $city }}　{{ $house_number }}　{{ $building }}　{{ $room_number }}</p>
    <p>本名:{{ $full_name}}（{{ $full_name_kana}}）</p>
    <p>電話番号:{{ $tel }}</p>
    <p>メールアドレス:{{ $email }}</p>
</div>
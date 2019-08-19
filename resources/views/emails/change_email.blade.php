@component('mail::message')
# Запрос на изменение почтового ящика.

Здравствуйте, {!! $username !!}. Вы отправили запрос на изменение почтового ящика. Если Вы не отправляли этот запрос, просто проигнорируйте данное письмо.

@component('mail::button', ['url' => $url, 'color' => 'blue'])
Подтвердить изменение email
@endcomponent

@endcomponent
@include('src.header',['title' => 'Пользователи'])
@include('src/admintitle', ['title' => 'Пользователи'])
<table>
    <tr>
        <td>Загрузка...</td>
    </tr>
</table>
@include('src/authForm',[
    'title' => 'Регистрация',
    'buttonName' => 'Зарегистрировать',
    'onclick' => 'register()',
    'backLink' => '/admin',
    'hidden' => true
])
<script src='/js/ApiRequest.js'></script>
<script src='/js/users.js'></script>
@include('src.footer')
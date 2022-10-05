@include('src/header',['title' => 'Вход'])
@include('src/authForm',[
    'title' => 'Вход',
    'buttonName' => 'Войти',
    'onclick' => 'login()',
])
<script src='/js/auth.js'></script>
<script src='/js/ApiRequest.js'></script>
@include('src/footer')
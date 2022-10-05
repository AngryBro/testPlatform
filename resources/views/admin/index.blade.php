@include('src.header',['title' => 'Админка'])
<h1>Админка</h1>
<ul>
    <li>
        <a href='/admin/users'>Пользователи</a>
    </li>
    <li>
        <a href='/admin/kims'>КИМы</a>
    </li>
    <li>
        <a href='/admin/test'>Тесты</a>
    </li>
    <li>
        <a href='/admin/results'>Результаты тестов</a>
    </li>
    <li>
        <a href='/logout'>Выйти</a>
    </li>
</ul>
@include('src.footer')
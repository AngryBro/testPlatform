<form @isset($hidden) hidden @endif>
    <table>
        <tr>
            <td>Email:</td>
            <td><input name='email'/></td>
        </tr>
        <tr>
            <td>Пароль</td>
            <td><input
                @empty($backLink)
                    type='password'
                @endif
            name='password'/></td>
        </tr>
        @isset($backLink)
            <tr>
                <td>Ким</td>
                <td><input name='kim'/></td>
            </tr>
        @endisset
        <tr>
            <td></td>
            <td><button type='button' onclick="{{$onclick}}">{{$buttonName}}</button></td>
        </tr>
    </table>
</form>
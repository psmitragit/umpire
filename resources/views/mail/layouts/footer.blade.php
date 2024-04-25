@if (isset($unsubMail))
    @php
        $encMail = Illuminate\Support\Facades\Crypt::encryptString($unsubMail);
    @endphp
    <div>
        <a href="{{ url('unsubscribe/' . $encMail) }}">Unsubscribe</a>
    </div>
@endif
</body>

</html>

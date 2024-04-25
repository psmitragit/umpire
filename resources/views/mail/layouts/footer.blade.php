@if (isset($unsubMail))
    @php
        $encMail = Illuminate\Support\Facades\Crypt::encryptString($unsubMail);
    @endphp
    <div style="margin-top: 10px; margin-bottom: 10px;">
        <a href="{{ url('unsubscribe/' . $encMail) }}" style="text-decoration: none; color: #ffffff; background-color: #a41515; padding: 10px 20px; border-radius: 5px; display: inline-block;">Unsubscribe</a>
    </div>

@endif
</body>

</html>

@if (isset($unsubMail))
    @php
        $encMail = Illuminate\Support\Facades\Crypt::encryptString($unsubMail);
    @endphp
    <div style="margin-top: 10px; margin-bottom: 10px;">
        <a href="{{ url('unsubscribe/' . $encMail) }}"
            style="text-decoration: none; color: #ffffff; background-color: #a41515; padding: 10px 20px; border-radius: 5px; display: inline-block;">Unsubscribe</a>
    </div>
@endif
</div>

<div style="text-align: center; background: #161e2d; padding:10px" >
    <a href="{{ url('/') }}" class="logocancor-s" style="color: #fff;font-size:16px;margin-right: 20px;"> Umpire Central </a>
    <a href="{{ url('privacy-policy') }}" style="text-decoration: underline;color: #fff;font-size:16px; ">Privacy Policy</a>
    <a href="{{ url('terms-of-use') }}" style="text-decoration: underline;color: #fff;font-size:16px;margin-left: 12px;">Terms of use</a>
</div>
</div>
</body>

</html>

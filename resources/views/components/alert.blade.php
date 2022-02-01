<div>
    <!-- I begin to speak only when I am certain what I will say is not better left unsaid. - Cato the Younger -->
    @if (Session::has("type"))
        <div class="alert alert-{{Session::get("type")}}">
            {{Session::get("message")}}
        </div>
    @endif
</div>

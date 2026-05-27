<form hidden action="{{ route('main.logout') }}" method="post">
    @csrf
    <button id="logout"></button>
</form>

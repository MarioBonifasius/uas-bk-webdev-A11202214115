<div class="navbar bg-base-100 shadow-sm">
    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </div>
        </div>
        <img src={{ asset("storage/logo_bengkod.svg") }} />
    </div>
    <div class="navbar-center hidden lg:flex">
        <input class="input w-72" placeholder="Cari Event..." />
    </div>
    <div class="navbar-end gap-2">
        <form method="GET" action="{{route('form.login')}}">
            @csrf
            <button type="submit" class="btn bg-blue-900 text-white">Login</button>
        </form>
        <a href="{{route('register')}}" class="btn text-blue-900">Register</a>
    </div>
</div>
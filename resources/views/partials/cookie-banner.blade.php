<div id="cookie-banner" class="fixed bottom-0 left-0 w-full bg-slate-900 text-white p-4 shadow-lg z-[60] hidden">
    <div class="container mx-auto px-6 lg:px-12 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-sm text-gray-300">
            <p>
                🍪 <strong>{{ __('cookies.title') }}:</strong> {{ __('cookies.text') }} 
                {{ __('cookies.agree_text') }} <a href="{{ route('legal.cookies') }}" class="underline text-white hover:text-blue-400">{{ __('cookies.link_policy') }}</a> {{ __('cookies.and') }} <a href="{{ route('legal.privacy') }}" class="underline text-white hover:text-blue-400">{{ __('cookies.link_privacy') }}</a>.
            </p>
        </div>
        <div class="flex gap-3">
            <button onclick="acceptCookies()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded text-sm font-semibold transition">
                {{ __('cookies.btn_accept') }}
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (!localStorage.getItem('cookie_consent')) {
            document.getElementById('cookie-banner').classList.remove('hidden');
        }
    });

    function acceptCookies() {
        localStorage.setItem('cookie_consent', 'true');
        document.getElementById('cookie-banner').classList.add('hidden');
    }
</script>
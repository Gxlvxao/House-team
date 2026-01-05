<div id="cookie-banner" class="fixed bottom-0 left-0 w-full bg-slate-900 text-white p-4 shadow-lg z-[60] hidden">
    <div class="max-container px-6 lg:px-12 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-sm text-gray-300">
            <p>
                🍪 <strong>Política de Cookies:</strong> Utilizamos cookies para garantir a melhor experiência no nosso site. 
                Ao continuar a navegar, aceita a nossa <a href="{{ route('legal.cookies') }}" class="underline text-white hover:text-blue-400">Política de Cookies</a> e <a href="{{ route('legal.privacy') }}" class="underline text-white hover:text-blue-400">Privacidade</a>.
            </p>
        </div>
        <div class="flex gap-3">
            <button onclick="acceptCookies()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded text-sm font-semibold transition">
                Aceitar Todos
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
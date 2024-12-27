@if ($errors->any())
    <div {{ $attributes }}>
        <div class="font-medium text-red-600">{{ __('¡Vaya! Algo salió mal.') }}</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            <li>Estas credenciales no coinciden con nuestros registros.</li>
        </ul>
    </div>
@endif

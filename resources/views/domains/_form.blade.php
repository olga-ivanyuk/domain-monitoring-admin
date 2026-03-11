@csrf

<div>
    <x-input-label for="domain" :value="__('Domain')" />
    <x-text-input id="domain" name="domain" type="text" class="mt-1 block w-full"
                  :value="old('domain', $domain->domain ?? '')"
                  placeholder="example.com"
                  required />
    <p class="mt-1 text-xs text-gray-500">Example: google.com or subdomain.example.com</p>
    <x-input-error class="mt-2" :messages="$errors->get('domain')" />
</div>

<div class="mt-4">
    <x-input-label for="check_interval" :value="__('Check Interval (seconds)')" />
    <x-text-input id="check_interval" name="check_interval" type="number" class="mt-1 block w-full"
                  :value="old('check_interval', $domain->check_interval ?? 60)"
                  min="30"
                  max="86400"
                  required />
    <x-input-error class="mt-2" :messages="$errors->get('check_interval')" />
</div>

<div class="mt-4">
    <x-input-label for="timeout" :value="__('Timeout (seconds)')" />
    <x-text-input id="timeout" name="timeout" type="number" class="mt-1 block w-full"
                  :value="old('timeout', $domain->timeout ?? 10)"
                  min="1"
                  max="60"
                  required />
    <x-input-error class="mt-2" :messages="$errors->get('timeout')" />
</div>

<div class="mt-4">
    <x-input-label for="method" :value="__('Method')" />
    <select id="method" name="method" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        @php($selectedMethod = old('method', $domain->method ?? 'GET'))
        <option value="GET" @selected($selectedMethod === 'GET')>GET</option>
        <option value="HEAD" @selected($selectedMethod === 'HEAD')>HEAD</option>
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('method')" />
</div>

<div class="flex items-center gap-4 mt-6">
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
    <a href="{{ route('domains.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
        {{ __('Cancel') }}
    </a>
</div>

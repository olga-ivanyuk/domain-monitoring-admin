<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Domains') }}
            </h2>
            <a href="{{ route('domains.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                {{ __('Add Domain') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <p class="mb-4 text-sm text-green-600">
                            {{ __('Operation completed successfully.') }}
                        </p>
                    @endif

                    @if ($domains->isEmpty())
                        <p class="text-sm text-gray-600">{{ __('No domains yet. Add your first domain.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Domain</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Last Check</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Interval</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Timeout</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                @foreach ($domains as $domain)
                                    <tr>
                                        <td class="px-4 py-3">{{ $domain->domain }}</td>
                                        <td class="px-4 py-3">
                                            @if ($domain->latestCheck)
                                                @if ($domain->latestCheck->status)
                                                    <span class="text-green-600 text-sm font-medium">UP</span>
                                                @else
                                                    <span class="text-red-600 text-sm font-medium">DOWN</span>
                                                @endif
                                                <p class="text-xs text-gray-500">
                                                    {{ $domain->latestCheck->checked_at?->diffForHumans() }}
                                                </p>
                                            @else
                                                <span class="text-gray-500 text-sm">No checks yet</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">{{ $domain->check_interval }}s</td>
                                        <td class="px-4 py-3">{{ $domain->timeout }}s</td>
                                        <td class="px-4 py-3">{{ $domain->method }}</td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('domains.checks.index', $domain) }}" class="text-gray-700 hover:text-gray-900 text-sm mr-3">
                                                {{ __('History') }}
                                            </a>
                                            <a href="{{ route('domains.edit', $domain) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                                {{ __('Edit') }}
                                            </a>
                                            <form action="{{ route('domains.destroy', $domain) }}" method="POST" class="inline-block ml-3">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm"
                                                        onclick="return confirm('Delete this domain?')">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $domains->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

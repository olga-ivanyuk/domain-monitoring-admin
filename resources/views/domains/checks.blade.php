<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Check History') }}: {{ $domain->domain }}
            </h2>
            <a href="{{ route('domains.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                {{ __('Back to Domains') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($checks->isEmpty())
                        <p class="text-sm text-gray-600">{{ __('No checks found for this domain yet.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Checked At</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Result</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status Code</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Response Time</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Error</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                @foreach ($checks as $check)
                                    <tr>
                                        <td class="px-4 py-3">{{ $check->checked_at?->format('Y-m-d H:i:s') }}</td>
                                        <td class="px-4 py-3">
                                            @if ($check->status)
                                                <span class="text-green-600 font-medium">UP</span>
                                            @else
                                                <span class="text-red-600 font-medium">DOWN</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">{{ $check->status_code ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $check->response_time ?? '-' }} ms</td>
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $check->error ?? '-' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $checks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

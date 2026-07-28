@extends('layouts.admin')
@section('title', 'Newsletter Subscribers')
@section('content')

<div class="subscribers-panel">
    <div class="rounded-md p-6 my-4" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold" style="color: var(--primary-color);">Newsletter Subscribers</h2>
            <span class="px-3 py-1 text-sm font-semibold rounded bg-indigo-50 text-indigo-700">
                Total: {{ $subscribers->total() }}
            </span>
        </div>

        @if($subscribers->isEmpty())
            <div class="text-center py-12 text-gray-500">
                <i class="fa-solid fa-paper-plane text-4xl mb-3 block text-gray-300"></i>
                No newsletter subscribers yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <th class="py-3 px-4">Subscription Date</th>
                            <th class="py-3 px-4">Email Address</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($subscribers as $subscriber)
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-3.5 px-4 text-gray-500 whitespace-nowrap">
                                    {{ $subscriber->created_at->format('d M Y, h:i A') }}
                                </td>
                                <td class="py-3.5 px-4 font-medium text-gray-900">
                                    {{ $subscriber->email }}
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <form action="{{ route('admin.communication.subscribers.delete', $subscriber->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this subscriber?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $subscribers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.admin')
@section('title', 'Leads & Inquiries')
@section('content')

<div class="inquiries-panel">
    <div class="rounded-md p-6 my-4" style="background-color: var(--secondary-background); border: 1px solid var(--border-color);">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold" style="color: var(--primary-color);">Contact Form Inquiries</h2>
            <span class="px-3 py-1 text-sm font-semibold rounded bg-indigo-50 text-indigo-700">
                Total: {{ $inquiries->total() }}
            </span>
        </div>

        @if($inquiries->isEmpty())
            <div class="text-center py-12 text-gray-500">
                <i class="fa-solid fa-inbox text-4xl mb-3 block text-gray-300"></i>
                No contact inquiries received yet.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <th class="py-3 px-4">Date</th>
                            <th class="py-3 px-4">Sender</th>
                            <th class="py-3 px-4">Subject</th>
                            <th class="py-3 px-4">Message</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($inquiries as $inquiry)
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-3.5 px-4 text-gray-500 whitespace-nowrap">
                                    {{ $inquiry->created_at->format('d M Y, h:i A') }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-gray-900">{{ $inquiry->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $inquiry->email }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-gray-700 font-medium max-w-xs truncate">
                                    {{ $inquiry->subject }}
                                </td>
                                <td class="py-3.5 px-4 text-gray-600 max-w-md">
                                    <div class="whitespace-pre-line">{{ $inquiry->message }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    <form action="{{ route('admin.communication.inquiries.delete', $inquiry->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this inquiry?')">
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
                {{ $inquiries->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

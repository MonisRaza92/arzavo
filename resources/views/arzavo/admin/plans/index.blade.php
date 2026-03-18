@extends('layouts.arzavo')

@section('title', 'Plans Management')

@section('content')

    <div class="p-6 space-y-6">

        <!-- HEADER -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Plans</h1>

            <button onclick="openModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:opacity-90">
                + Create Plan
            </button>
        </div>

        <!-- PLANS GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($plans as $plan)
                <div class="border rounded-xl p-5 bg-white shadow-sm">

                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold">{{ $plan->name }}</h2>

                        @if($plan->is_popular)
                            <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded">
                                Popular
                            </span>
                        @endif
                    </div>

                    <p class="text-gray-500 text-sm mt-1">
                        {{ $plan->short_description }}
                    </p>

                    <div class="mt-4">
                        <p class="text-2xl font-bold">
                            ₹{{ $plan->monthly_price }}/mo
                        </p>
                        @if($plan->yearly_price)
                            <p class="text-sm text-gray-500">
                                ₹{{ $plan->yearly_price }}/year
                            </p>
                        @endif
                    </div>

                    <div class="mt-4 space-y-1 text-sm">
                        
                    </div>

                    <div class="flex gap-2 mt-5">
                        <button onclick="editPlan({{ $plan->id }})" class="flex-1 border rounded px-3 py-2 text-sm">
                            Edit
                        </button>

                        <button onclick="deletePlan({{ $plan->id }})"
                            class="flex-1 bg-red-500 text-white rounded px-3 py-2 text-sm">
                            Delete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <!-- MODAL -->
    <div id="planModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center">

        <div class="bg-white w-full max-w-2xl rounded-xl p-6 space-y-4">

            <h2 class="text-lg font-semibold">Create Plan</h2>

            <form id="planForm">

                <!-- BASIC -->
                <div class="grid grid-cols-2 gap-4">
                    <input name="name" placeholder="Plan Name" class="input" required>
                    <input name="slug" placeholder="Slug" class="input" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <input name="monthly_price" placeholder="Monthly Price" type="number" class="input">
                    <input name="yearly_price" placeholder="Yearly Price" type="number" class="input">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <input name="trial_days" placeholder="Trial Days" type="number" class="input">
                    <input name="short_description" placeholder="Short Description" class="input">
                </div>

                <textarea name="description" placeholder="Description" class="input w-full"></textarea>

                <!-- SWITCHES -->
                <div class="flex gap-4">
                    <label>
                        <input type="checkbox" name="is_active"> Active
                    </label>
                    <label>
                        <input type="checkbox" name="is_popular"> Popular
                    </label>
                </div>

                <!-- FEATURES -->
                <div>
                    <h3 class="font-semibold mb-2">Features</h3>

                    <div id="featuresWrapper" class="space-y-2"></div>

                    <button type="button" onclick="addFeature()" class="text-sm text-primary mt-2">
                        + Add Feature
                    </button>
                </div>

                <!-- ACTION -->
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded">
                        Cancel
                    </button>

                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded">
                        Save Plan
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('planModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('planModal').classList.add('hidden');
        }

        function addFeature(key = '', value = '') {
            let html = `
            <div class="flex gap-2">
                <input name="features[${key}]" value="${value}" placeholder="value" class="input flex-1">
                <input placeholder="key" class="input flex-1 feature-key">
                <button type="button" onclick="this.parentElement.remove()">X</button>
            </div>
        `;
            document.getElementById('featuresWrapper').insertAdjacentHTML('beforeend', html);
        }

        // FIX dynamic key binding
        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('feature-key')) {
                let valueInput = e.target.parentElement.querySelector('input[name^="features"]');
                valueInput.name = `features[${e.target.value}]`;
            }
        });

        // SUBMIT
        document.getElementById('planForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            let res = await fetch("{{ route('arzavo.admin.plans.store') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: formData
            });

            let data = await res.json();

            if (data.success) {
                location.reload();
            } else {
                alert('Error');
            }
        });
    </script>
@endsection
<!-- MODAL -->
<div id="planModal" class="fixed inset-0 flex bg-black/90 hidden items-center justify-center z-100">

    <div class="bg-white w-full max-w-4xl border-rounded shadow-2xl p-4 space-y-6 overflow-y-auto max-h-[90vh]">

        <!-- HEADER -->
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold">Create Plan</h2>
                <p class="text-sm text-gray-500">Define pricing, features and limits for your SaaS plan</p>
            </div>

            <button onclick="closeModal()" class="text-gray-600 hover:text-black text-xl"><i
                    class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="planForm" action="{{ route('arzavo.admin.plans.store') }}" method="POST">
            @csrf

            <!-- BASIC INFO -->
            <div class="bg-secondary p-4 border-rounded space-y-4">
                <h3 class="font-semibold text-lg flex items-center gap-2">Basic Information
                </h3>

                <div class="grid grid-cols-2 gap-4">
                    <input name="name" placeholder="Plan Name (e.g. Starter)"
                        class="input border-primary border-rounded p-3 bg-primary" required>
                    <input name="slug" placeholder="Slug (starter-plan)"
                        class="input border-primary border-rounded p-3 bg-primary" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <input name="monthly_price" type="number" placeholder="Monthly Price (₹)"
                        class="input border-primary border-rounded p-3 bg-primary">
                    <input name="yearly_price" type="number" placeholder="Yearly Price (₹)"
                        class="input border-primary border-rounded p-3 bg-primary">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <input name="trial_days" type="number" placeholder="Trial Days (e.g. 7)"
                        class="input border-primary border-rounded p-3 bg-primary">
                    <input name="short_description" placeholder="Short Description"
                        class="input border-primary border-rounded p-3 bg-primary">
                </div>

                <textarea name="description" placeholder="Full description of the plan..." rows="5"
                    class="input border-primary border-rounded p-3 bg-primary w-full"></textarea>
            </div>

            <!-- SETTINGS ROW -->
            <div class="space-y-3 my-6">

                <!-- ACTIVE -->
                <div class="flex items-center justify-between border-primary border-rounded px-4 py-3">
                    <div>
                        <p class="font-medium"><i class="fa-regular fa-circle-dot mr-1"></i> Active Plan</p>
                        <p class="text-xs text-gray-500">
                            If disabled, users will not be able to subscribe to this plan
                        </p>
                    </div>

                    <x-input.switch name="is_active" :checked="true" containerClass="w-auto!" />
                </div>

                <!-- POPULAR -->
                <div class="flex items-center justify-between border-primary border-rounded px-4 py-3">
                    <div>
                        <p class="font-medium flex items-center">
                            <i class="fa-regular fa-star mr-2"></i> Popular Plan
                        </p>
                        <p class="text-xs text-gray-500">
                            Highlight this plan as recommended to attract more users
                        </p>
                    </div>

                    <x-input.switch name="is_popular" containerClass="w-auto!" />
                </div>

            </div>


            <!-- FEATURES -->
            <div class="bg-blue-100 p-4 border-rounded mt-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-lg flex items-center gap-2">
                            <i class="fa-solid fa-chart-simple"></i> Features
                        </h3>
                        <p class="text-xs text-gray-500">
                            Enable or disable features available in this plan
                        </p>
                    </div>

                    <button type="button" onclick="addFeature()"
                        class="bg-blue-500 text-white p-3 w-30 border-rounded text-sm hover:opacity-90">
                        + Add Feature
                    </button>
                </div>

                <div id="featuresWrapper" class="space-y-2"></div>
            </div>

            <!-- LIMITS -->
            <div class="bg-purple-100 p-4 border-rounded mt-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-lg flex items-center gap-2">
                            <i class="fa-regular fa-circle-pause"></i> Limits
                        </h3>
                        <p class="text-xs text-gray-500">
                            Define usage limits like students, storage, etc.
                        </p>
                    </div>

                    <button type="button" onclick="addLimit()"
                        class="bg-purple-500 text-white p-3 w-30 border-rounded text-sm hover:opacity-90">
                        + Add Limit
                    </button>
                </div>

                <div id="limitsWrapper" class="space-y-2"></div>
            </div>

            <!-- ACTION -->
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border-rounded border-primary">
                    Cancel
                </button>

                <button type="submit" class="px-5 py-2 bg-invert text-invert border-rounded">
                    <i class="fa-regular fa-floppy-disk mr-2"></i> Save Plan
                </button>
            </div>

        </form>
    </div>
</div>
<script>
    function addFeature() {
        let index = Date.now();

        let html = `
        <div class="flex gap-2 items-center text-sm bg-white p-2 border-rounded border-primary mt-4">
            <input name="features[${index}][key]" placeholder="Feature (e.g. blogs)" class="input border-primary border-rounded p-2 bg-primary flex-1">
            <select name="features[${index}][value]" class="input border-primary border-rounded p-2 bg-primary w-28">
                <option value="1" selected>ALLOW</option>
                <option value="0">BLOCK</option>
            </select>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 px-2"><i class="fa-solid fa-trash"></i></button>
        </div>
    `;

        document.getElementById('featuresWrapper').insertAdjacentHTML('beforeend', html);
    }

    function addLimit() {
        let index = Date.now();

        let html = `
        <div class="flex gap-2 text-sm items-center bg-white p-2 border-rounded border-primary mt-4">
            <input name="limits[${index}][key]" placeholder="Limit (e.g. students)" class="input border-primary border-rounded p-2 bg-primary flex-1">
            <input type="number" name="limits[${index}][value]" placeholder="Value" class="input border-primary border-rounded p-2 bg-primary w-28">
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 px-2"><i class="fa-solid fa-trash"></i></button>
        </div>
    `;

        document.getElementById('limitsWrapper').insertAdjacentHTML('beforeend', html);
    }
</script>
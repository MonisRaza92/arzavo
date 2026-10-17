{{-- ======================================================== --}}
{{-- 6. MANUAL FEE PAYMENT COLLECTION MODAL                     --}}
{{-- ======================================================== --}}
<div x-cloak
     x-show="collectModalOpen"
     @keydown.escape.window="collectModalOpen = false"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
    <div @click.outside="collectModalOpen = false"
         class="relative w-full max-w-lg rounded-lg bg-primary shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

        {{-- Modal Header --}}
        <div class="px-6 py-5 bg-primary flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-brand/10 text-brand flex items-center justify-center text-base">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-base text-theme-text">Collect Student Fee Payment</h3>
                    <p class="text-xs text-theme-text-muted font-normal">Record tuition payment and update student ledger</p>
                </div>
            </div>
            <button type="button" @click="collectModalOpen = false" class="text-theme-text-muted hover:text-theme-text transition p-1 cursor-pointer">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>

        {{-- Modal Form --}}
        <template x-if="selectedStudent">
            <form :action="'{{ url('admin/student/fee/payment') }}/' + selectedStudent.id"
                  method="POST"
                  class="flex-1 overflow-y-auto px-6 pb-6 space-y-4 scrollbar">
                @csrf

                {{-- Student Summary Badge --}}
                <div class="p-3.5 bg-secondary rounded-lg flex items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-3 min-w-0">
                        <template x-if="selectedStudent.profile_picture">
                            <img :src="'/' + selectedStudent.profile_picture" :alt="selectedStudent.fname" class="w-10 h-10 rounded-full object-cover shrink-0">
                        </template>
                        <template x-if="!selectedStudent.profile_picture">
                            <div class="w-10 h-10 rounded-full bg-brand text-primary font-semibold flex items-center justify-center text-sm shrink-0 uppercase"
                                 x-text="(selectedStudent.fname.charAt(0) + (selectedStudent.lname ? selectedStudent.lname.charAt(0) : '')).toUpperCase()">
                            </div>
                        </template>
                        <div class="min-w-0">
                            <div class="font-semibold text-theme-text truncate" x-text="selectedStudent.fname + ' ' + (selectedStudent.lname || '')"></div>
                            <div class="text-[10px] text-theme-text-muted truncate" x-text="(selectedStudent.class_name || 'No Class') + ' &bull; ' + (selectedStudent.category_name || 'No Category')"></div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-[10px] text-theme-text-muted uppercase tracking-wider block font-normal">Balance Due</span>
                        <span class="font-semibold text-sm"
                              :class="selectedStudent.due_fee > 0 ? 'text-warning' : 'text-success'"
                              x-text="'₹' + Number(selectedStudent.due_fee || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })">
                        </span>
                    </div>
                </div>

                {{-- Payment Amount --}}
                <div>
                    <label class="block text-xs font-normal text-theme-text mb-1.5">Amount to Collect (₹) <span class="text-danger font-semibold">*</span></label>
                    <input type="number"
                           step="0.01"
                           min="1"
                           name="amount_paid"
                           x-model="collectPaymentAmount"
                           required
                           placeholder="Enter fee amount..."
                           class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                    <span class="text-[10px] text-theme-text-muted mt-1 block">Full or partial payment amount being deposited right now.</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Payment Date <span class="text-danger font-semibold">*</span></label>
                        <input type="date"
                               name="payment_date"
                               x-model="collectPaymentDate"
                               required
                               class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Payment Method <span class="text-danger font-semibold">*</span></label>
                        <select name="payment_method" required class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                            <option value="Cash">Cash</option>
                            <option value="UPI / QR">UPI / QR</option>
                            <option value="Online / Net Banking">Online / Net Banking</option>
                            <option value="Debit / Credit Card">Debit / Credit Card</option>
                            <option value="Cheque">Cheque</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Transaction Ref / Slip No. <span class="text-theme-text-muted text-[10px]">(Optional)</span></label>
                        <input type="text"
                               name="transaction_id"
                               placeholder="e.g. UPI-92817291 or Receipt #12"
                               class="w-full bg-secondary text-theme-text placeholder:text-theme-text-muted text-xs px-4 py-3 rounded-full focus:outline-none font-normal">
                    </div>
                    <div>
                        <label class="block text-xs font-normal text-theme-text mb-1.5">Payment Status <span class="text-danger font-semibold">*</span></label>
                        <select name="status" required class="w-full bg-secondary text-theme-text text-xs px-4 py-3 rounded-full focus:outline-none font-normal cursor-pointer">
                            <option value="paid">Paid (Confirmed)</option>
                            <option value="pending">Pending Verification</option>
                        </select>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button"
                            @click="collectModalOpen = false"
                            class="px-5 py-3 bg-secondary text-theme-text rounded-full font-normal text-xs hover:bg-subtle transition inline-flex items-center gap-2 cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-3 bg-brand text-primary rounded-full font-normal text-xs transition hover:opacity-90 inline-flex items-center gap-2 cursor-pointer shadow-xs">
                        <i class="fa-solid fa-check"></i>
                        <span>Save Payment Record</span>
                    </button>
                </div>
            </form>
        </template>
    </div>
</div>

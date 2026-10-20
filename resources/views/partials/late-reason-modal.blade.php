{{-- LATE REASON DIALOG (uncloseable until a reason is given) --}}
@if(isset($lateRecord) && $lateRecord)
    <div class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/80 backdrop-blur-xl p-4"
        x-data="{ reason: '' }"
        @keydown.escape.window.prevent @keydown.tab.prevent
        @click.self.stop>
        <div class="relative w-full max-w-lg card !rounded-3xl shadow-card-lg p-6 overflow-hidden">

            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-400 via-orange-500 to-amber-400"></div>

            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 flex items-center justify-center rounded-2xl bg-amber-500/15 border border-amber-500/30">
                    <i data-lucide="alarm-clock" class="w-6 h-6 text-amber-500"></i>
                </div>
                <div>
                    <h3 class="font-display font-bold text-xl text-[var(--text)]">Late Arrival</h3>
                    <p class="text-xs text-amber-600 dark:text-amber-400">You clocked in after 10:15 AM</p>
                </div>
            </div>

            <p class="text-sm text-[var(--text-soft)] mb-4">
                Please provide a reason for your late arrival. This dialog cannot be closed
                until a reason is submitted.
            </p>

            <form method="POST" action="{{ route('attendance.late-reason') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="attendance_id" value="{{ $lateRecord->id }}">

                <textarea name="late_reason" rows="4" required
                    x-model="reason"
                    placeholder="Explain the reason you were late…"
                    class="textarea resize-none"></textarea>

                <p class="text-xs text-amber-600 dark:text-amber-400 font-semibold" x-show="reason.trim().length === 0">
                    A reason is required to close this dialog.
                </p>

                <button type="submit"
                    :disabled="reason.trim().length === 0"
                    class="btn btn-primary w-full !py-3 disabled:opacity-40">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    Submit Late Reason
                </button>
            </form>
        </div>
    </div>
@endif
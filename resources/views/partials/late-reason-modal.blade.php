{{-- ⚠ LATE REASON DIALOG (uncloseable until a reason is given) --}}
@if(isset($lateRecord) && $lateRecord)
    <div class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/85 backdrop-blur-xl p-4"
        x-data="{ reason: '' }"
        @keydown.escape.window.prevent @keydown.tab.prevent
        @click.self.stop>
        <div class="relative w-full max-w-lg bg-white/10 backdrop-blur-2xl
                    border border-amber-400/30 rounded-3xl shadow-2xl p-6">

            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 flex items-center justify-center rounded-2xl bg-amber-500/20 border border-amber-400/30">
                    <i data-lucide="alarm-clock" class="w-6 h-6 text-amber-400"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-white">Late Arrival</h3>
                    <p class="text-xs text-amber-300/90">You clocked in after 10:15 AM</p>
                </div>
            </div>

            <p class="text-white/80 text-sm mb-4">
                Please provide a reason for your late arrival. This dialog cannot be closed
                until a reason is submitted.
            </p>

            <form method="POST" action="{{ route('attendance.late-reason') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="attendance_id" value="{{ $lateRecord->id }}">

                <textarea name="late_reason" rows="4" required
                    x-model="reason"
                    placeholder="Explain the reason you were late…"
                    class="w-full px-4 py-3 rounded-2xl bg-black/40 border border-white/10
                           text-white resize-none focus:border-amber-400/50 outline-none"></textarea>

                <p class="text-xs text-amber-400/90" x-show="reason.trim().length === 0">
                    A reason is required to close this dialog.
                </p>

                <button type="submit"
                    :disabled="reason.trim().length === 0"
                    class="w-full py-3 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600
                           text-white font-bold transition
                           disabled:opacity-40 disabled:cursor-not-allowed">
                    Submit Late Reason
                </button>
            </form>
        </div>
    </div>
@endif
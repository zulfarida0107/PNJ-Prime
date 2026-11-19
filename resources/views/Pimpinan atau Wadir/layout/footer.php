            </main>
        </div>

    </div>

    <!-- Interactive Script for Approval Modal, Fiscal Calendar, Responsive Sidebar & System Functionalities -->
    <script>
        // =========================================================================
        // 1. RESPONSIVE MOBILE SIDEBAR TOGGLE
        // =========================================================================
        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
            if (backdrop) {
                backdrop.classList.toggle('hidden');
            }
        }

        // =========================================================================
        // 2. INTERACTIVE FISCAL YEAR & MINI CALENDAR POPOVER
        // =========================================================================
        const monthNamesIndo = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        let currentCalYear = 2025;
        let currentCalMonth = 1; // 0-indexed (1 = Februari 2025, Semester Genap 2024/2025)
        let selectedCalDay = 17;

        function renderMiniCalendar() {
            const monthYearLabel = document.getElementById('calendar-month-year');
            const daysGrid = document.getElementById('calendar-days-grid');
            if (!monthYearLabel || !daysGrid) return;

            monthYearLabel.textContent = monthNamesIndo[currentCalMonth] + ' ' + currentCalYear;
            daysGrid.innerHTML = '';

            const firstDayOfMonth = new Date(currentCalYear, currentCalMonth, 1).getDay();
            const daysInMonth = new Date(currentCalYear, currentCalMonth + 1, 0).getDate();

            // Empty slots before first day
            for (let i = 0; i < firstDayOfMonth; i++) {
                const emptySlot = document.createElement('span');
                emptySlot.className = 'h-7 w-7';
                daysGrid.appendChild(emptySlot);
            }

            // Days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                const isSelected = (day === selectedCalDay);
                btn.className = isSelected
                    ? 'h-7 w-7 mx-auto flex items-center justify-center rounded-full bg-[#00998A] text-white font-bold text-[11px] shadow-xs transition cursor-pointer'
                    : 'h-7 w-7 mx-auto flex items-center justify-center rounded-full text-[#0B1C30] hover:bg-[#EFF4FF] hover:text-[#00998A] font-medium text-[11px] transition cursor-pointer';
                btn.textContent = day;
                btn.onclick = function(e) {
                    e.stopPropagation();
                    selectCalendarDay(day);
                };
                daysGrid.appendChild(btn);
            }
        }

        function toggleFiscalCalendar(event) {
            if (event) event.stopPropagation();
            const popover = document.getElementById('fiscal-calendar-popover');
            const chevron = document.getElementById('fiscal-chevron');
            if (!popover) return;

            const isHidden = popover.classList.contains('hidden');
            if (isHidden) {
                popover.classList.remove('hidden');
                if (chevron) chevron.classList.add('rotate-180');
                renderMiniCalendar();
            } else {
                closeFiscalCalendar();
            }
        }

        function closeFiscalCalendar() {
            const popover = document.getElementById('fiscal-calendar-popover');
            const chevron = document.getElementById('fiscal-chevron');
            if (popover) popover.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
        }

        function changeCalendarMonth(delta) {
            currentCalMonth += delta;
            if (currentCalMonth < 0) {
                currentCalMonth = 11;
                currentCalYear--;
            } else if (currentCalMonth > 11) {
                currentCalMonth = 0;
                currentCalYear++;
            }
            renderMiniCalendar();
        }

        function selectCalendarDay(day) {
            selectedCalDay = day;
            renderMiniCalendar();

            // Format YYYY-MM-DD for the date input
            const mm = String(currentCalMonth + 1).padStart(2, '0');
            const dd = String(day).padStart(2, '0');
            const dateInput = document.getElementById('fiscal-custom-date');
            if (dateInput) {
                dateInput.value = `${currentCalYear}-${mm}-${dd}`;
            }

            // Automatically determine Academic Year & Semester (Ganjil: Sep-Feb, Genap: Mar-Aug)
            let academicYear = '';
            let semester = '';
            if (currentCalMonth >= 8) { // Sep - Dec
                academicYear = `${currentCalYear}/${currentCalYear + 1}`;
                semester = 'Ganjil';
            } else if (currentCalMonth <= 1) { // Jan - Feb
                academicYear = `${currentCalYear - 1}/${currentCalYear}`;
                semester = 'Ganjil';
            } else { // Mar - Aug
                academicYear = `${currentCalYear - 1}/${currentCalYear}`;
                semester = 'Genap';
            }

            const display = document.getElementById('fiscal-year-display');
            if (display) {
                display.textContent = `${academicYear} (${semester})`;
            }
        }

        function selectFiscalYear(label, year, monthIndex) {
            const display = document.getElementById('fiscal-year-display');
            if (display) {
                display.textContent = label;
            }
            if (year && typeof monthIndex === 'number') {
                currentCalYear = year;
                currentCalMonth = monthIndex;
                selectedCalDay = 1;
                renderMiniCalendar();
            }

            // Update active styling on period list buttons
            const options = document.querySelectorAll('#fiscal-period-list .fiscal-option');
            options.forEach(btn => {
                if (btn.textContent.includes(label)) {
                    btn.className = 'fiscal-option flex items-center justify-between w-full rounded-lg px-3 py-2 text-left text-xs font-bold bg-[#EFF4FF] text-[#00998A] border border-[#00998A]/30 transition';
                } else {
                    btn.className = 'fiscal-option flex items-center justify-between w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-[#3D4947] hover:bg-[#EFF4FF] hover:text-[#00998A] transition';
                }
            });
        }

        function applyCustomFiscalDate(val) {
            if (!val) return;
            const parts = val.split('-');
            if (parts.length === 3) {
                currentCalYear = parseInt(parts[0], 10);
                currentCalMonth = parseInt(parts[1], 10) - 1;
                selectedCalDay = parseInt(parts[2], 10);
                selectCalendarDay(selectedCalDay);
            }
        }

        // =========================================================================
        // 3. APPROVAL MODAL FUNCTIONS
        // =========================================================================
        function openApprovalModal() {
            const modal = document.getElementById('modal-approval-pesanan');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function closeApprovalModal() {
            const modal = document.getElementById('modal-approval-pesanan');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Close on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeApprovalModal();
                closeFiscalCalendar();
                const sidebar = document.getElementById('main-sidebar');
                if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                    toggleSidebar();
                }
            }
        });

        // Close when clicking outside popover or on modal backdrop
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('modal-approval-pesanan');
            if (modal && !modal.classList.contains('hidden')) {
                const card = modal.querySelector('div.relative.w-full');
                if (card && !card.contains(event.target)) {
                    if (!event.target.closest('[onclick*="openApprovalModal"]')) {
                        closeApprovalModal();
                    }
                }
            }

            const calendarWrapper = document.getElementById('fiscal-calendar-wrapper');
            if (calendarWrapper && !calendarWrapper.contains(event.target)) {
                closeFiscalCalendar();
            }
        });
    </script>

</body>
</html>

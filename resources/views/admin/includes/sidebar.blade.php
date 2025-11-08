<div id="adminMobileMenu" class="admin-sidebar -translate-x-full lg:-translate-x-0 transition-all duration-300 ease-in-out w-[260px] h-full fixed top-0 left-0 overflow-y-auto p-4 pt-0 pb-10 z-9 scrollbar bg-primary border-right" style="margin-top: calc( var(--logo-size) + 14px);">
    <div class="sticky top-0 z-9 bg-primary pt-6">
        <div class="school-coaching-name relative mb-2">
            <label for="school_coaching" class="text-xs absolute left-2 -top-2 px-1 text-secondary bg-primary">School/Coaching</label>
            <select name="school_coaching" id="school_coaching" class="mt-2 block w-full border-rounded p-2 border-primary">
                <option value="PJC" selected>{{ $settings['site_name'] ?? 'ARZAQ INSIGHTS' }}</option>
            </select>
        </div>
        <div class="search-bar relative w-full mb-4">
            <input id="searchInput" type="text" placeholder="Search..." class="search-input w-full border-rounded px-3 py-2 input-focus border-primary">
            <button class="search-button absolute right-0 top-0 mt-2.5 mr-2 text-teriary"><i class="fas fa-search"></i></button>
        </div>
    </div>
    <ul class="flex flex-col gap-2 mb-12 admin-nav">

        <!-- MAIN -->
        <li class="text-tertiary text-xs py-2 font-medium">Reports & Analytics</li>

        <!-- DASHBOARD -->
        <li><a href="{{ route('admin.dashboard') }}" class="block p-2 border-rounded {{ request()->is('admin/dashboard') ? 'active-li text-primary' : 'text-secondary' }}"><i class="fa-solid fa-bars-progress mr-1"></i> Dashboard</a></li>
        <!-- REPORTS -->
        <li><a href="" class="block relative p-2 border-rounded"><i class="fa-solid fa-chart-line mr-2"></i> Reports & Analytics</a></li>


        <!-- ACADEMICS -->
        <li class="text-tertiary text-xs py-2 font-medium">Academics</li>
        <li>
            <button onclick="toggleMenu('academicsMenu', 'arrow-academics')" class="block relative w-full text-left p-2 border-rounded">
                <i class="fa-solid fa-school mr-2"></i> Academics
                <i id="arrow-academics" class="fas fa-angle-right absolute right-2 top-1/2 transform -translate-y-1/2 transition-all duration-300 ease-in-out"></i>
            </button>
            <ul id="academicsMenu" class="ml-4 pl-2 border-left overflow-hidden max-h-0 transition-all duration-300 ease-linear space-y-2">
                <li><a href="{{ url('admin-courses') }}" class="block p-2 border-rounded"><i class="fa-solid fa-book-open mr-1"></i> Courses</a></li>
                <li><a href="{{ url('admin-exams') }}" class="block p-2 border-rounded"><i class="fa-solid fa-file-pen mr-1"></i> Exams</a></li>
                <li><a href="{{ url('admin-results') }}" class="block p-2 border-rounded"><i class="fa-solid fa-graduation-cap mr-1"></i> Results</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-book-bookmark mr-1"></i> Assignments</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-certificate mr-1"></i> Certificates</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-stopwatch mr-1"></i> Timetable</a></li>
            </ul>
        </li>

        <!-- CLASSES / BATCHES -->
        <li>
            <button onclick="toggleMenu('classesMenu', 'arrow-classes')" class="block relative w-full text-left p-2 border-rounded">
                <i class="fa-solid fa-layer-group mr-2"></i> Classes & Batches
                <i id="arrow-classes" class="fas fa-angle-right absolute right-2 top-1/2 transform -translate-y-1/2 transition-all duration-300 ease-in-out"></i>
            </button>
            <ul id="classesMenu" class="ml-4 pl-2 border-left overflow-hidden max-h-0 transition-all duration-300 ease-linear space-y-2">
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-clipboard-list mr-1"></i> Class List</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-users-viewfinder mr-1"></i> Batch Management</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-calendar-check mr-1"></i> Class Schedule</a></li>
            </ul>
        </li>

        <!-- FINANCE -->
        <li>
            <button onclick="toggleMenu('financeMenu', 'arrow-finance')" class="block relative w-full text-left p-2 border-rounded">
                <i class="fa-solid fa-wallet mr-2"></i> Finance
                <i id="arrow-finance" class="fas fa-angle-right absolute right-2 top-1/2 transform -translate-y-1/2 transition-all duration-300 ease-in-out"></i>
            </button>
            <ul id="financeMenu" class="ml-4 pl-2 border-left overflow-hidden max-h-0 transition-all duration-300 ease-linear space-y-2">
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-money-bill mr-1"></i> Student Fees</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-sack-dollar mr-1"></i> Teacher Salaries</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-hand-holding-dollar mr-1"></i> Expenses</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-receipt mr-1"></i> Invoices</a></li>
            </ul>
        </li>

        <!-- CONTENT -->
        <li>
            <button onclick="toggleMenu('contentMenu', 'arrow-content')" class="block relative w-full text-left p-2 border-rounded">
                <i class="fa-solid fa-folder-open mr-2"></i> Content
                <i id="arrow-content" class="fas fa-angle-right absolute right-2 top-1/2 transform -translate-y-1/2 transition-all duration-300 ease-in-out"></i>
            </button>
            <ul id="contentMenu" class="ml-4 pl-2 border-left overflow-hidden max-h-0 transition-all duration-300 ease-linear space-y-2">
                <li><a href="{{ url('admin-notes') }}" class="block p-2 border-rounded {{ request()->is('admin/notes') ? 'active-li text-primary' : 'text-secondary' }}"><i class="fa-solid fa-file-alt mr-1"></i> Notes</a></li>
                <li><a href="{{ url('admin-books') }}" class="block p-2 border-rounded {{ request()->is('admin/books') ? 'active-li text-primary' : 'text-secondary' }}"><i class="fa-solid fa-book mr-1"></i> Books</a></li>
                <li><a href="{{ url('admin-videos') }}" class="block p-2 border-rounded {{ request()->is('admin/videos') ? 'active-li text-primary' : 'text-secondary' }}"><i class="fa-solid fa-video mr-1"></i> Videos</a></li>
                <li><a href="{{ url('admin-blogs') }}" class="block p-2 border-rounded {{ request()->is('admin/blogs') ? 'active-li text-primary' : 'text-secondary' }}"><i class="fa-solid fa-newspaper mr-1"></i> Blogs</a></li>
                <li><a href="{{ url('admin-events') }}" class="block p-2 border-rounded {{ request()->is('admin/events') ? 'active-li text-primary' : 'text-secondary' }}"><i class="fa-solid fa-calendar mr-1"></i> Events</a></li>
            </ul>
        </li>


        <li class="text-tertiary text-xs py-2 font-medium">Users & Staff</li>
        <!-- STUDENTS -->
        <li>
            <button onclick="toggleMenu('studentsMenu', 'arrow-students')" class="block relative w-full text-left p-2 border-rounded">
                <i class="fa-solid fa-user-graduate mr-2"></i> Students
                <i id="arrow-students" class="fas fa-angle-right absolute right-2 top-1/2 transform -translate-y-1/2 transition-all duration-300 ease-in-out"></i>
            </button>
            <ul id="studentsMenu" class="ml-4 pl-2 border-left overflow-hidden max-h-0 transition-all duration-300 ease-linear space-y-2">
                <li><a href="{{ url('admin-students') }}" class="block p-2 border-rounded"><i class="fa-solid fa-list-ol mr-1"></i> Student List</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-user-plus mr-1"></i> Admissions</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-user-clock mr-1"></i> Attendance</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-chart-simple mr-1"></i> Progress Reports</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-money-bill-wave mr-1"></i> Fees Management</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-comment-dots mr-1"></i> Feedback</a></li>
            </ul>
        </li>

        <!-- TEACHERS -->
        <li>
            <button onclick="toggleMenu('teachersMenu', 'arrow-teachers')" class="block relative w-full text-left p-2 border-rounded">
                <i class="fa-solid fa-chalkboard-teacher mr-2"></i> Teachers
                <i id="arrow-teachers" class="fas fa-angle-right absolute right-2 top-1/2 transform -translate-y-1/2 transition-all duration-300 ease-in-out"></i>
            </button>
            <ul id="teachersMenu" class="ml-4 pl-2 border-left overflow-hidden max-h-0 transition-all duration-300 ease-linear space-y-2">
                <li><a href="{{ url('admin-teachers') }}" class="block p-2 border-rounded"><i class="fa-solid fa-list-ol mr-1"></i> Teacher List</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-user-tie mr-1"></i> Assign Subjects</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-user-clock mr-1"></i> Attendance</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-money-check-dollar mr-1"></i> Salary</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-pie-chart mr-1"></i> Performance</a></li>
            </ul>
        </li>

        <!-- STAFF -->
        <li>
            <button onclick="toggleMenu('staffMenu', 'arrow-staff')" class="block relative w-full text-left p-2 border-rounded">
                <i class="fa-solid fa-users-gear mr-2"></i> Employes
                <i id="arrow-staff" class="fas fa-angle-right absolute right-2 top-1/2 transform -translate-y-1/2 transition-all duration-300 ease-in-out"></i>
            </button>
            <ul id="staffMenu" class="ml-4 pl-2 border-left overflow-hidden max-h-0 transition-all duration-300 ease-linear space-y-2">
                <li><a href="{{ url('admin-teachers') }}" class="block p-2 border-rounded"><i class="fa-solid fa-list-ol mr-1"></i> Staff List</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-user-clock mr-1"></i> Attendance</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-money-check-dollar mr-1"></i> Salary</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-pie-chart mr-1"></i> Performance</a></li>
            </ul>
        </li>

        <!-- USERS -->
        <li><a href="{{ url('admin-users') }}" class="block relative p-2 border-rounded"><i class="fa-solid fa-users mr-2"></i> Users</a></li>


        <li class="text-tertiary text-xs py-2 font-medium">Communication & Settings</li>
        <!-- COMMUNICATION -->
        <li>
            <button onclick="toggleMenu('commMenu', 'arrow-comm')" class="block relative w-full text-left p-2 border-rounded">
                <i class="fa-solid fa-comments mr-2"></i> Communication
                <i id="arrow-comm" class="fas fa-angle-right absolute right-2 top-1/2 transform -translate-y-1/2 transition-all duration-300 ease-in-out"></i>
            </button>
            <ul id="commMenu" class="ml-4 pl-2 border-left overflow-hidden max-h-0 transition-all duration-300 ease-linear space-y-2">
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-envelope mr-1"></i> Messages</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-bell mr-1"></i> Notifications</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-video mr-1"></i> Live Classes</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-users-rectangle mr-1"></i> Forums</a></li>
            </ul>
        </li>


        <!-- REVIEWS -->
        <li><a href="{{ url('admin-reviews') }}" class="block relative p-2 border-rounded"><i class="fa-solid fa-star mr-2"></i> Reviews</a></li>

        <!-- SETTINGS -->
        <li>
            <button onclick="toggleMenu('settingsMenu', 'arrow-settings')" class="block relative w-full text-left p-2 border-rounded">
                <i class="fa-solid fa-gear mr-2"></i> Settings
                <i id="arrow-settings" class="fas fa-angle-right absolute right-2 top-1/2 transform -translate-y-1/2 transition-all duration-300 ease-in-out"></i>
            </button>
            <ul id="settingsMenu" class="ml-4 pl-2 border-left overflow-hidden max-h-0 transition-all duration-300 ease-linear space-y-2">
                <li><a href="{{ url('admin-modules') }}" class="block p-2 border-rounded {{ request()->is('admin/modules') ? 'active-li text-primary' : 'text-secondary' }}"><i class="fa-solid fa-list mr-1"></i> Modules</a></li>
                <li><a href="{{ url('admin-settings') }}" class="block p-2 border-rounded"><i class="fa-solid fa-sliders mr-1"></i> General Settings</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-lock mr-1"></i> Roles & Permissions</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-shield-halved mr-1"></i> Security</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-globe mr-1"></i> Language & Region</a></li>
                <li><a href="#" class="block p-2 border-rounded"><i class="fa-solid fa-headset mr-1"></i> Support Center</a></li>
            </ul>
        </li>

        <!-- WEBSITE -->
        <li>
            <button onclick="toggleMenu('websiteMenu', 'arrow-website')" class="block relative w-full text-left p-2 border-rounded">
                <i class="fa-solid fa-globe mr-2"></i> Website & Theme
                <i id="arrow-website" class="fas fa-angle-right absolute right-2 top-1/2 transform -translate-y-1/2 transition-all duration-300 ease-in-out"></i>
            </button>
            <ul id="websiteMenu" class="ml-4 pl-2 border-left overflow-hidden max-h-0 transition-all duration-300 ease-linear space-y-2">
                <li><a href="{{ route('admin.builder.index') }}" class="block p-2 border-rounded {{ request()->is('admin/builder') ? 'active-li text-primary' : 'text-secondary' }}"><i class="fa-solid fa-pen-nib mr-1"></i> Page Builder</a></li>
                <li><a href="{{ route('admin.pages.index') }}" class="block p-2 border-rounded {{ request()->is('admin/pages') ? 'active-li text-primary' : 'text-secondary' }}"><i class="fa-solid fa-window-restore mr-1"></i> Web Pages</a></li>
                <li><a href="{{ route('admin.images.index') }}" class="block p-2 border-rounded {{ request()->is('admin/images') ? 'active-li text-primary' : 'text-secondary' }}"><i class="fa-solid fa-image mr-1"></i> Media Library</a></li>
            </ul>
        </li>
    </ul>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('adminMobileMenu');
        const toggleButton = document.getElementById('adminSidebarToggle');

        window.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !toggleButton.contains(e.target)) {
                sidebar.classList.add('-translate-x-full');
            }
        });
    });

    function toggleMenu(menuId, arrowId) {
        const menu = document.getElementById(menuId);
        const arrow = document.getElementById(arrowId);

        // Toggle menu open/close
        const isClosed = menu.classList.contains('max-h-0');
        if (isClosed) {
            // Open
            menu.classList.remove('max-h-0');
            menu.classList.add('max-h-200', 'pt-2');
            arrow.classList.add('rotate-90');
            localStorage.setItem(menuId, 'open'); // Save open state
        } else {
            // Close
            menu.classList.remove('max-h-200', 'pt-2');
            menu.classList.add('max-h-0');
            arrow.classList.remove('rotate-90');
            localStorage.setItem(menuId, 'closed'); // Save closed state
        }
    }

    // Restore menu state on page load
    document.addEventListener("DOMContentLoaded", function() {
        const menuIds = ['studentsMenu', 'academicsMenu', 'classesMenu', 'financeMenu', 'contentMenu', 'teachersMenu', 'staffMenu', 'commMenu', 'settingsMenu', 'websiteMenu']; // Add all expandable menus here

        menuIds.forEach(menuId => {
            const menu = document.getElementById(menuId);
            const arrow = document.getElementById('arrow-' + menuId.replace('Menu', ''));
            const state = localStorage.getItem(menuId);

            if (!menu) return;

            if (state === 'open') {
                menu.classList.remove('max-h-0');
                menu.classList.add('max-h-200', 'pt-2');
                if (arrow) arrow.classList.add('rotate-90');
            } else {
                menu.classList.remove('max-h-200', 'pt-2');
                menu.classList.add('max-h-0');
                if (arrow) arrow.classList.remove('rotate-90');
            }
        });
    });

    document.getElementById('searchInput').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        const navItems = document.querySelectorAll('.admin-nav li');

        navItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            const menu = item.closest('ul');
            const parentButton = menu?.previousElementSibling;

            if (text.includes(query)) {
                item.style.display = '';
                if (menu && parentButton) {
                    menu.classList.remove('max-h-0');
                    menu.classList.add('max-h-200', 'pt-2');
                    const arrow = parentButton.querySelector('.fas');
                    if (arrow) arrow.classList.add('rotate-90');
                }
            } else {
                item.style.display = 'none';
            }
        });

        // Hide all menus if input is empty
        if (query === '') {
            const menus = document.querySelectorAll('.admin-nav ul');
            menus.forEach(menu => {
                menu.classList.remove('max-h-200', 'pt-2');
                menu.classList.add('max-h-0');
                const parentButton = menu.previousElementSibling;
                const arrow = parentButton?.querySelector('.fas');
                if (arrow) arrow.classList.remove('rotate-90');
            });
        }
    });
</script>
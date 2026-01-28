<div class="w-75 bg-primary border-right h-[calc(100vh-60px)] fixed left-0 top-[60px] flex flex-col z-30">
    <div class="links-tabs p-4 h-full">
        <div class="tabs flex flex-col justify-start items-start gap-2">
            <button onclick="openTab('overview')" tab-btn="overview" class="p-2 tab-btn text-sm flex items-center justify-between border-rounded w-full text-left bg-invert text-invert border-invert"><span><i class="fa-solid fa-bars-progress mr-1"></i> Overview</span> <i class="fa-solid fa-angle-right"></i></button>
            <button onclick="openTab('editor')" tab-btn="editor" class="p-2 tab-btn text-sm flex items-center justify-between bg-hover-secondary border-rounded w-full text-left border-primary"><span><i class="fa-solid fa-edit mr-1"></i> Editor</span> <i class="fa-solid fa-angle-right"></i></button>
            <button onclick="openTab('settings')" tab-btn="settings" class="p-2 tab-btn text-sm flex items-center justify-between bg-hover-secondary border-rounded w-full text-left border-primary"><span><i class="fa-solid fa-cog mr-1"></i> Settings</span> <i class="fa-solid fa-angle-right"></i></button>
            <button onclick="openTab('reviews')" tab-btn="reviews" class="p-2 tab-btn text-sm flex items-center justify-between bg-hover-secondary border-rounded w-full text-left border-primary"><span><i class="fa-solid fa-star mr-1"></i> Reviews</span> <i class="fa-solid fa-angle-right"></i></button>
        </div>
    </div>
    <!-- Exit btn -->
    <button class="p-4 sticky bottom-0 text-sm border-top w-full text-left"><i class="fa-solid fa-arrow-left mr-2"></i> Courses List</button>
</div>
<script>
    function openTab(tabData) {
        const btns = document.querySelectorAll('.tab-btn');
        const btn = document.querySelector(`[tab-btn="${tabData}"]`);

        btns.forEach(el => {
            el.classList.remove('bg-invert', 'text-invert', 'border-invert');
            el.classList.add('bg-hover-secondary', 'text-hover-secondary', 'border-primary');
        });

        if (btn) {
            btn.classList.add('bg-invert', 'text-invert', 'border-invert');
            btn.classList.remove('bg-hover-secondary', 'text-hover-secondary', 'border-primary');
        }

        const tabs = document.querySelectorAll('.tab-content');
        const tab = document.querySelector(`[tab-content="${tabData}"]`);

        tabs.forEach(el => el.classList.add('hidden'));
        if (tab) tab.classList.remove('hidden');

        localStorage.setItem('activeCourseTab', tabData);
    }

    document.addEventListener('turbo:load', () => {
        const activeTab = localStorage.getItem('activeCourseTab') || 'overview';
        openTab(activeTab);
    });
</script>
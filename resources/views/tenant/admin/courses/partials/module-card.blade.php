<div id="module-{{ $module->id }}" class="border-primary border-rounded bg-primary">
    <div class="header border-bottom p-4">
        <div class="flex justify-between items-center">
            <h4 class="font-medium">{{ $module->title }}</h4>
            <div class="actions relative">
                <button onclick="toggleModel('moduleActions-{{ $module->id }}')"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <div id="moduleActions-{{ $module->id }}" class="menu absolute hidden right-0 bottom-full bg-primary w-42 z-50 border-primary border-rounded p-2">
                    <button class="text-secondary block py-2 px-1 text-left w-full text-sm"><i class="fa-solid fa-edit mr-2"></i> Update</button>
                    <button class="text-secondary block py-2 px-1 text-left w-full text-sm"><i class="fa-solid fa-arrow-up mr-2"></i> Move Up</button>
                    <button class="text-secondary block py-2 px-1 text-left w-full text-sm"><i class="fa-solid fa-arrow-down mr-2"></i> Move Down</button>
                    <button type="button" onclick="deleteModule({{ $module->id }})" class="text-secondary block py-2 px-1 pt-3 border-top mt-2 text-left w-full text-sm"> <i class="fa-solid fa-trash mr-2 text-red-400"></i> Delete
                    </button>
                </div>
            </div>
        </div>
        @if($module->description)
        <p class="text-sm text-tertiary mt-1">
            {{ $module->description }}
        </p>
        @endif
    </div>
    <div class="p-4 pl-16 relative space-y-4">
        <div style="border-style: dashed;" class="absolute top-0 left-8 w-1 h-[calc(100%-58px)] bg-transparent border-l-2 border-gray-200 before:content-[''] before:absolute before:-left-0.5 before:-bottom-4 before:w-8 before:h-10 before:rounded-bl-xl before:bg-transparent before:border-b-2 before:border-l-2 before:border-gray-200 before:border-dashed"></div>
        <div id="moduleLessonsContainer{{ $module->id }}" class="space-y-4">
            @foreach($module->lessons as $lesson)
            @include('tenant.admin.courses.partials.lesson-card', ['lesson' => $lesson , 'module' => $module])
            @endforeach
        </div>
        @include('tenant.admin.courses.partials.add-module-lesson', ['moduleId' => $module->id])
        <button id="addModuleLessonBtn{{ $module->id }}" onclick="addModuleLesson({{ $module->id }})" class="text-secondary font-semibold border-rounded p-4 w-full border-primary" style="border-style: dashed; border-width: 2px;"><i class="fa-solid fa-plus mr-2"></i> Add Lesson</button>
    </div>
</div>
<h4 class="text-xs border-bottom uppercase text-primary py-4 px-2 cursor-pointer {{ $title !== 'Header' ? 'border-top' : '' }} font-semibold"
    onclick="toggleSectionGroup('{{ $target }}')" style="border-style: dashed;">
    <i class="fa-solid fa-chevron-down mr-2 text-[10px]" id="{{ $target }}-chevron"></i> {{ $title }}
</h4>
@if (count($sections)>0)
    <ul class="sortable-section-list section-list p-2 space-y-2" data-target="{{ $target }}"
        id="{{ $target }}-section-list">

        @foreach($sections as $section)
            @include('tenant.admin.builder.sections.section-card', [
                'section' => $section,
                'rules' => $rules

        ])    @endforeach
    </ul>
@endif
<button class="cursor-pointer border-rounded m-2 {{ count($sections)>0 ? 'mt-0' : ''}} text-blue-600 text-sm bg-hover-secondary p-2.5 w-[calc(100%-1rem)] text-left" onclick="openAddSection('{{ $target }}')"> <i class="fa-regular fa-square-plus ml-5.5 mr-1"></i>
        Add Section
</button>


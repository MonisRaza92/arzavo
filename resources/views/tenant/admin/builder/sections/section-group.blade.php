<h4 class="text-sm capitalize font-semibold text-primary pt-3 px-4 cursor-pointer {{ $title !== 'Header' ? 'border-top' : '' }} font-semibold"
    style="border-style: dashed;">
    {{ $title }}
</h4>
@if (count($sections) > 0)
    <ul class="sortable-section-list section-list p-2 pb-0" data-target="{{ $target }}"
        id="{{ $target }}-section-list">

        @foreach($sections as $section)
            @include('tenant.admin.builder.sections.section-card', [
                'section' => $section,
                'rules' => $rules

        ])    @endforeach
        </ul>
@endif
<button class="cursor-pointer border-rounded mx-2 mb-2 text-blue-600 text-sm bg-hover-secondary p-2 w-[calc(100%-1rem)] text-left" onclick="openAddSection('{{ $target }}')"> <i class="fa-solid fa-circle-plus ml-5.75 mr-1 text-[13px]"></i>
        Add Section
</button>


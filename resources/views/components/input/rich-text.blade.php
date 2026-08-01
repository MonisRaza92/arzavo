@props([
    'name',
    'label' => '',
    'value' => '',
    'rows' => 5,
    'placeholder' => '',
    'hint' => null,
    'toolbar' => 'full'
])

@php
    $content = is_string($value) ? htmlspecialchars_decode(htmlspecialchars_decode($value, ENT_QUOTES), ENT_QUOTES) : ($value ?? '');
    $uid = 'rt_' . md5($name . uniqid());
@endphp

<x-input.wrapper :label="$label" :hint="$hint">

    <div class="richtext-wrapper bg-primary border-primary border-rounded overflow-hidden shadow-xs transition-all"
        data-richtext-id="{{ $uid }}">

        {{-- Primary Clean Toolbar --}}
        <div class="flex flex-wrap items-center gap-1.5 p-2 bg-secondary border-bottom text-xs select-none">

            {{-- Heading Selector --}}
            <select class="heading-selector bg-primary border-primary border-rounded px-2.5 py-1 text-xs text-primary font-medium focus:outline-none cursor-pointer">
                <option value="p">Normal Text</option>
                <option value="h1">Heading 1</option>
                <option value="h2">Heading 2</option>
                <option value="h3">Heading 3</option>
                <option value="h4">Heading 4</option>
            </select>

            {{-- Font Size Selector --}}
            <select class="font-size-selector bg-primary border-primary border-rounded px-2 py-1 text-xs text-primary font-medium focus:outline-none cursor-pointer w-20" title="Font Size">
                <option value="">Size</option>
                <option value="10px">10</option>
                <option value="11px">11</option>
                <option value="12px">12</option>
                <option value="13px">13</option>
                <option value="14px">14</option>
                <option value="16px">16</option>
                <option value="18px">18</option>
                <option value="20px">20</option>
                <option value="22px">22</option>
                <option value="24px">24</option>
                <option value="28px">28</option>
                <option value="32px">32</option>
                <option value="36px">36</option>
                <option value="48px">48</option>
                <option value="60px">60</option>
                <option value="72px">72</option>
            </select>

            <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 mx-0.5"></div>

            {{-- Essential Formatting: Bold, Italic, Underline --}}
            <button type="button" data-action="bold" title="Bold (Ctrl+B)" class="px-2.5 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary font-bold text-primary transition-all">B</button>
            <button type="button" data-action="italic" title="Italic (Ctrl+I)" class="px-2.5 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary italic text-primary transition-all">I</button>
            <button type="button" data-action="underline" title="Underline (Ctrl+U)" class="px-2.5 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary underline text-primary transition-all">U</button>

            <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 mx-0.5"></div>

            {{-- Single Text Color Button --}}
            <label class="relative flex items-center justify-center px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary cursor-pointer transition-all text-primary font-bold" title="Text Color">
                <span class="text-xs border-b-2 border-accent px-0.5">A</span>
                <input type="color" class="text-color-picker absolute inset-0 opacity-0 cursor-pointer w-full h-full" value="#1a1a1a">
            </label>

            {{-- Single Highlight Color Button --}}
            <label class="relative flex items-center justify-center px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary cursor-pointer transition-all text-amber-500" title="Highlight Color">
                <i class="fa-solid fa-highlighter text-xs"></i>
                <input type="color" class="highlight-color-picker absolute inset-0 opacity-0 cursor-pointer w-full h-full" value="#fef08a">
            </label>

            <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 mx-0.5"></div>

            {{-- Lists --}}
            <button type="button" data-action="bulletList" title="Bullet List" class="px-2.5 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all"><i class="fa-solid fa-list-ul"></i></button>
            <button type="button" data-action="orderedList" title="Numbered List" class="px-2.5 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all"><i class="fa-solid fa-list-ol"></i></button>

            <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 mx-0.5"></div>

            {{-- Link --}}
            <button type="button" data-action="link" title="Insert / Edit Link" class="px-2.5 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all"><i class="fa-solid fa-link"></i></button>

            <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 mx-0.5"></div>

            {{-- Undo / Redo --}}
            <button type="button" data-action="undo" title="Undo" class="px-2.5 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all"><i class="fa-solid fa-rotate-left"></i></button>
            <button type="button" data-action="redo" title="Redo" class="px-2.5 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all"><i class="fa-solid fa-rotate-right"></i></button>


            {{-- More Tools Toggle Button --}}
            <button type="button" onclick="this.closest('.richtext-wrapper').querySelector('.more-tools-drawer').classList.toggle('hidden')" title="More Formatting Tools" class="px-2.5 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all ml-auto flex items-center gap-1">
                <span>More</span>
                <i class="fa-solid fa-ellipsis-vertical text-xs text-secondary"></i>
            </button>
        </div>

        {{-- More Tools Expandable Drawer (Secondary Controls) --}}
        <div class="more-tools-drawer hidden p-2 bg-secondary border-bottom border-primary flex flex-wrap items-center gap-1.5 text-xs select-none">
            
            {{-- Strikethrough & Math --}}
            <button type="button" data-action="strike" title="Strikethrough" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary line-through text-primary transition-all">S</button>
            <button type="button" data-action="subscript" title="Subscript (x₂)" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all">x₂</button>
            <button type="button" data-action="superscript" title="Superscript (x²)" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all">x²</button>

            <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 mx-0.5"></div>

            {{-- Alignments --}}
            <button type="button" data-action="alignLeft" title="Align Left" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all"><i class="fa-solid fa-align-left"></i></button>
            <button type="button" data-action="alignCenter" title="Align Center" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all"><i class="fa-solid fa-align-center"></i></button>
            <button type="button" data-action="alignRight" title="Align Right" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all"><i class="fa-solid fa-align-right"></i></button>
            <button type="button" data-action="alignJustify" title="Justify Text" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all"><i class="fa-solid fa-align-justify"></i></button>

            <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 mx-0.5"></div>

            {{-- Blocks --}}
            <button type="button" data-action="blockquote" title="Quote Block" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all"><i class="fa-solid fa-quote-right"></i></button>
            <button type="button" data-action="codeBlock" title="Code Block" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all"><i class="fa-solid fa-code"></i></button>
            <button type="button" data-action="hr" title="Horizontal Line" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all"><i class="fa-solid fa-minus"></i></button>

            <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 mx-0.5"></div>

            {{-- Media & Tables --}}
            <button type="button" data-action="image" title="Insert Image from URL" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all"><i class="fa-solid fa-image"></i></button>
            <button type="button" data-action="table" title="Insert 3x3 Table" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all"><i class="fa-solid fa-table"></i></button>

            <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 mx-0.5"></div>

            {{-- Utilities --}}
            <button type="button" data-action="clear" title="Clear Formatting" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-red-500 transition-all"><i class="fa-solid fa-broom"></i></button>

            <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 mx-0.5"></div>

            {{-- Toggle Raw HTML Code Mode --}}
            <button type="button" data-action="toggleHtml" title="Edit Raw HTML Code" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary font-mono text-xs font-bold transition-all flex items-center gap-1">
                <i class="fa-solid fa-code text-accent"></i>
                <span>HTML</span>
            </button>

            <button type="button" data-action="fullscreen" title="Toggle Fullscreen Mode" class="px-2 py-1 border-rounded bg-primary border border-primary hover:bg-tertiary text-primary transition-all ml-auto"><i class="fa-solid fa-expand"></i></button>
        </div>

        {{-- Floating Link Bar --}}
        <div class="link-bar hidden p-2 border-bottom bg-secondary text-xs flex items-center gap-2">
            <i class="fa-solid fa-link text-secondary ml-1"></i>
            <input type="text" class="link-url bg-primary border-primary border-rounded px-2.5 py-1 w-full text-xs text-primary" placeholder="Enter target URL (e.g. https://example.com)">
            <button type="button" class="link-apply px-3 py-1 border-rounded bg-invert text-invert font-medium hover:opacity-90 transition-all">Save Link</button>
            <button type="button" class="link-remove px-3 py-1 border-rounded bg-red-500 text-white font-medium hover:opacity-90 transition-all">Remove</button>
        </div>

        {{-- Floating Image Bar --}}
        <div class="image-bar hidden p-2 border-bottom bg-secondary text-xs flex items-center gap-2">
            <i class="fa-solid fa-image text-secondary ml-1"></i>
            <input type="text" class="image-url bg-primary border-primary border-rounded px-2.5 py-1 w-full text-xs text-primary" placeholder="Enter Image URL (e.g. https://example.com/photo.jpg)">
            <button type="button" class="image-apply px-3 py-1 border-rounded bg-invert text-invert font-medium hover:opacity-90 transition-all">Insert Image</button>
        </div>

        {{-- Visual Editor --}}
        <div class="tiptap-editor p-3.5 w-full text-sm min-h-64 max-h-[500px] overflow-y-auto focus:outline-none cursor-text text-primary prose prose-sm max-w-none flex flex-col justify-start" data-content="{{ $content }}" onclick="if(this.editor) this.editor.chain().focus().run()">
        </div>

        {{-- Raw HTML Editor --}}
        <textarea class="raw-html-editor hidden p-3.5 w-full text-xs font-mono min-h-64 max-h-[500px] bg-dark text-emerald-400 focus:outline-none overflow-y-auto resize-none" placeholder="Paste or type raw HTML markup here..."></textarea>

        {{-- Editor Footer Bar (Word & Character Counter) --}}
        <div class="flex justify-between items-center px-3 py-1.5 bg-secondary border-top text-[11px] text-tertiary font-medium">
            <div class="flex items-center gap-3">
                <span><strong class="word-count-val text-primary">0</strong> words</span>
                <span><strong class="char-count-val text-primary">0</strong> characters</span>
            </div>
        </div>

        {{-- Hidden Input for Form Submission --}}
        <input type="hidden" name="{{ $name }}" value="{{ $content }}">

    </div>

</x-input.wrapper>

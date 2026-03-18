@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 mb-4 text-emerald-400 text-xs font-bold uppercase tracking-widest">
        Content Management
    </div>

    <h2 class="!mt-0">How to Upload a Blog</h2>
    
    <p>
        Maintaining an active blog is a great way to attract new students via SEO and to keep your current students informed. Arzavo provides a powerful, built-in blogging engine.
    </p>

    <h3>Publishing your first Blog Post</h3>
    
    <ol class="space-y-6 !mt-6">
        <li class="glass-box !p-6 !mb-0">
            <strong class="text-white text-xl block mb-2">1. Navigate to the Blogs section</strong>
            From your Tenant Admin Dashboard, look at the left sidebar under <strong>Content Management</strong>, and click on <strong>Blogs</strong>.
        </li>
        
        <li class="glass-box !p-6 !mb-0">
            <strong class="text-white text-xl block mb-2">2. Click "Add New Post"</strong>
            This will open the Blog Editor interface.
        </li>

        <li class="glass-box !p-6 !mb-0">
            <strong class="text-white text-xl block mb-2">3. Fill in the Meta Data</strong>
            <ul>
                <li><strong>Title:</strong> The main heading of your post.</li>
                <li><strong>Slug:</strong> The URL-friendly version of the title (e.g., <code>my-first-post</code>). Usually auto-generated.</li>
                <li><strong>Category & Tags:</strong> Helps organize your content for readers.</li>
                <li><strong>Featured Image:</strong> Upload a high-quality thumbnail image.</li>
            </ul>
        </li>

        <li class="glass-box !p-6 !mb-0">
            <strong class="text-white text-xl block mb-2">4. Write your Content</strong>
            Use the Rich Text Editor to write your blog. You can format text, embed YouTube videos, add images, and create tables directly within the editor workspace.
        </li>

        <li class="glass-box !p-6 !mb-0">
            <strong class="text-white text-xl block mb-2">5. Publish or Schedule</strong>
            Set the status to <strong>Published</strong> to make it live immediately, or keep it as <strong>Draft</strong> if you wish to finish it later.
        </li>
    </ol>

    <div class="mt-12 flex justify-between border-t border-white/10 pt-8">
        <a href="{{ route('documentation.show', 'choose-plan') }}" class="inline-flex flex-col items-start text-left group">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 group-hover:text-slate-400 transition-colors">Previous</span>
            <span class="text-lg font-bold text-white group-hover:text-slate-300 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> How to Choose a Plan
            </span>
        </a>
        <a href="{{ route('documentation.show', 'upload-course') }}" class="inline-flex flex-col items-end text-right group">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1 group-hover:text-slate-400 transition-colors">Next</span>
            <span class="text-lg font-bold text-accent group-hover:text-accent-secondary transition-colors flex items-center gap-2">
                How to Upload a Course <i class="fa-solid fa-arrow-right"></i>
            </span>
        </a>
    </div>
@endsection

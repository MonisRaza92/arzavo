@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-500/10 border border-slate-500/20 mb-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
        Advanced Topic: User Profiles
    </div>

    <h2 class="mt-0!">Student Profile Setup</h2>
    
    <p>
        Students and Teachers have full control over their public profiles via the `ProfileController`.
    </p>

    <h3>Profile Customization Routes</h3>
    <ul class="space-y-4 mt-6!">
        <li><strong>Profile Picture Updates:</strong> Users can upload a new avatar via the `/profile/picture/update` route. The system crops and compresses this image before storing it in the tenant's media disk.</li>
        <li><strong>Profile Banner Updates:</strong> Similar to social media, users can upload a wide cover photo (`/profile/banner/update`) to personalize their portfolio.</li>
        <li><strong>Biography & Links:</strong> Using the `/profile/info/update` endpoint, users can edit their biography text, update social media links, and change their display names.</li>
    </ul>

    <div class="glass-box bg-slate-900/50! border-white/5 mt-8!">
        <h4 class="mt-0! text-white">Admin Overrides</h4>
        <p class="mb-0! text-sm text-slate-300">
            If a student uploads inappropriate imagery, an Admin can navigate to the Student Management panel and manually overwrite these profile settings.
        </p>
    </div>
@endsection

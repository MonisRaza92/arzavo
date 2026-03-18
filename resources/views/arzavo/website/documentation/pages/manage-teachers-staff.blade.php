@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 mb-4 text-blue-400 text-xs font-bold uppercase tracking-widest">
        User Management
    </div>

    <h2 class="mt-0!">Teachers & Staff</h2>
    
    <p>
        Scaling your institute requires a team. Arzavo allows you to invite Teachers and administrative Staff to help manage the workload.
    </p>

    <h3>Adding a Teacher</h3>
    <ol class="space-y-4 mt-6!">
        <li>Navigate to <strong>Users > Teachers</strong>.</li>
        <li>Click on <strong>Add New Teacher</strong>.</li>
        <li>Fill in their professional details, bio, and credentials.</li>
        <li>Assign them to specific <strong>Subjects</strong> or <strong>Classes</strong> so they only have access to relevant curriculums.</li>
    </ol>

    <div class="glass-box bg-blue-500/5! border-blue-500/20 mt-8!">
        <h4 class="mt-0! text-blue-400"><i class="fa-solid fa-shield-halved"></i> Role Based Access (Staff)</h4>
        <p class="mb-0! text-sm text-slate-300">
            For non-teaching roles (e.g., Accountants, Content Managers), navigate to <strong>Users > Staff</strong>. When creating a staff account, you can select specific permissions limiting their access to only Billing or only the Blog Engine, keeping your core settings secure.
        </p>
    </div>
@endsection

@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 mb-4 text-blue-400 text-xs font-bold uppercase tracking-widest">
        User Management
    </div>

    <h2 class="mt-0!">How to Manage Students</h2>
    
    <p>
        The Students module in Arzavo allows you to oversee every learner registered to your institution. You can approve their accounts, update their fee statuses, and modify their profiles.
    </p>

    <h3>Student Overview</h3>
    <p>
        Navigate to <strong>Users > Students</strong> in your Admin Dashboard. Here you will find a table listing all registered students, their enrollment dates, and their current status (Active, Pending, or Banned).
    </p>

    <h3>Approving & Updating Status</h3>
    <ol class="space-y-4 mt-6!">
        <li>Locate the student in the table.</li>
        <li>Under the <strong>Actions</strong> column, click the status toggle.</li>
        <li>You can change a pending student to <strong>Active</strong> to grant them immediate access to their purchased courses.</li>
    </ol>

    <h3>Managing Profiles & Fees</h3>
    <p>
        Clicking on a student's name opens their detailed profile view. 
    </p>
    <div class="grid md:grid-cols-2 gap-6 mt-6!">
        <div class="glass-box p-5! mb-0! border-white/5">
            <h4 class="mt-0! text-white">Profile Editing</h4>
            <p class="text-sm">As an admin, you can correct typos in a student's name, update their contact email, or reset their assigned role via the Profile Info tab.</p>
        </div>
        <div class="glass-box p-5! mb-0! border-white/5">
            <h4 class="mt-0! text-white">Fee Management</h4>
            <p class="text-sm">In the Fee tab, track offline payments or manual fee collections. Update their outstanding balances directly to keep the ledger accurate.</p>
        </div>
    </div>
@endsection

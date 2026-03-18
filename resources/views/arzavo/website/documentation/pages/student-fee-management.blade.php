@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-500/10 border border-slate-500/20 mb-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
        Advanced Topic: Accounting
    </div>

    <h2 class="mt-0!">Manual Fee Updates</h2>
    
    <p>
        While online course purchases are tracked automatically, you will often need to manually collect fees (offline, cash drops) for blended or offline classes.
    </p>

    <h3>Using the `studentFeeUpdate` Method</h3>
    <ol class="space-y-4 mt-6!">
        <li>Navigate to <strong>Admin > Students</strong>.</li>
        <li>Open the required student's profile.</li>
        <li>Switch to the <strong>Fee Operations</strong> tab.</li>
        <li>Here you can enter the nominal offline fee collected.</li>
    </ol>
    
    <p class="mt-4!">
        This tool fires a secure request to the server, appending the amount to the student's ledger. It is pivotal for maintaining accurate tax reports within the platform.
    </p>
@endsection

@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-500/10 border border-slate-500/20 mb-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
        Configuration
    </div>

    <h2 class="mt-0!">Reviews & Analytics</h2>
    
    <p>
        Feedback loops are essential for improving course quality and proving social proof to prospective students.
    </p>

    <h3>Managing Course Reviews</h3>
    <ol class="space-y-4 mt-6!">
        <li>Navigate to <strong>Configuration > Reviews</strong>.</li>
        <li>When a student completes a course, they are prompted to leave a 1-5 star rating and a written review.</li>
        <li>These reviews land in your dashboard pool as "Pending".</li>
        <li>You must manually <strong>Approve</strong> a review for it to be publicly displayed on your Course Sales page. This prevents spam or inappropriate language from appearing on your storefront.</li>
    </ol>

    <h3>System Reports</h3>
    <p class="mt-6!">
        Under <strong>Configuration > Reports</strong>, admins can run complex queries to generate CSV exports of:
    </p>
    <ul class="space-y-2 mt-4!">
        <li>Monthly enrollment numbers per Course.</li>
        <li>Financial transaction ledgers.</li>
        <li>Student activity logs and completion rates.</li>
    </ul>
    <p>
        These reports are crucial for accounting and end-of-year tax filing.
    </p>
@endsection

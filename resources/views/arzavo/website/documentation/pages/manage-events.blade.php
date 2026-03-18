@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 mb-4 text-emerald-400 text-xs font-bold uppercase tracking-widest">
        Content Hub
    </div>

    <h2 class="mt-0!">Events & Webinars</h2>
    
    <p>
        Hosting live classes, seminars, or offline events? Use the Events module to showcase upcoming activities on your public website.
    </p>

    <h3>Publishing an Event</h3>
    <ol class="space-y-4 mt-6!">
        <li>Navigate to <strong>Content > Events</strong>.</li>
        <li>Click <strong>Create New Event</strong>.</li>
        <li>Define the Details:
            <ul class="mt-2">
                <li><strong>Title & Description</strong></li>
                <li><strong>Datetime:</strong> Start and end times.</li>
                <li><strong>Location & Venue:</strong> Use physical addresses or Zoom/Google Meet links.</li>
                <li><strong>Banner Image:</strong> Upload a promotional graphic.</li>
            </ul>
        </li>
        <li>Once published, the event automatically appears on your front-end "Events" page, allowing students to RSVP or add it to their calendars.</li>
    </ol>
@endsection

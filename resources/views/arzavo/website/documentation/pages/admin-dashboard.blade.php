@extends('arzavo.website.documentation.layout')

@section('doc_content')
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 mb-4 text-blue-400 text-xs font-bold uppercase tracking-widest">
        Dashboard
    </div>

    <h2 class="!mt-0">Admin Dashboard Overview</h2>
    
    <p>
        Your Admin Dashboard is the central command center for your entire educational institution. Arzavo groups all critical tools logically in your sidebar to give you a 360-degree view of your operations.
    </p>

    <h3>Key Metrics at a Glance</h3>
    <p>
        Upon logging in, the primary dashboard view surfaces your high-level analytics:
    </p>
    <ul>
        <li><strong>Total Revenue:</strong> Month-to-date and all-time revenue generated through course sales and fee collections.</li>
        <li><strong>Active Students:</strong> How many students are enrolled and active inside your platform.</li>
        <li><strong>Recent Activity:</strong> Logs of recent course enrollments or signups.</li>
    </ul>

    <h3>Understanding the Sidebar Categories</h3>
    
    <div class="grid md:grid-cols-2 gap-6 !mt-6">
        <div class="glass-box !p-5 !mb-0">
            <h4 class="!mt-0 font-bold text-white"><i class="fa-solid fa-users text-accent"></i> Users</h4>
            <p class="text-sm">Manage student profiles, approve registrations, track fee transactions, and oversee teachers and staff permissions.</p>
        </div>
        <div class="glass-box !p-5 !mb-0">
            <h4 class="!mt-0 font-bold text-white"><i class="fa-solid fa-graduation-cap text-accent"></i> Academics</h4>
            <p class="text-sm">Structure your school by defining Classes (grades/tiers), Subjects, and assigning teachers to specific subjects.</p>
        </div>
        <div class="glass-box !p-5 !mb-0">
            <h4 class="!mt-0 font-bold text-white"><i class="fa-solid fa-video text-accent"></i> Courses (LMS)</h4>
            <p class="text-sm">The core builder for video courses, modular curriculum delivery, and drip content pacing.</p>
        </div>
        <div class="glass-box !p-5 !mb-0">
            <h4 class="!mt-0 font-bold text-white"><i class="fa-solid fa-paint-roller text-accent"></i> Website Builder</h4>
            <p class="text-sm">Your visual storefront tools. Switch themes, open the drag-and-drop page builder, and customize your menus.</p>
        </div>
    </div>
@endsection

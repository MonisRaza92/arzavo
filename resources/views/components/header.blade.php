<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="keywords" content="school management software, coaching institute management software, institute management system, school ERP, coaching class software, student management system, fees management software, education management platform, online school software, institute ERP">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{ $customizes['meta_description'] ?? 'Run your school or coaching institute smarter with Arzavo. Manage admissions, fees, staff, students, online classes, reports & more in one simple platform' }}">
<meta name="author" content="Monis Raza Khan">
<meta name="robots" content="index, follow">
<link rel="canonical" href="https://arzavo.com/">
<link rel="icon" type="image/x-icon" href="{{ media($customizes['favicon'] ?? 'images/favicon.ico') }}">
<title>@yield('title', 'Arzavo – School, College & Coaching Institute Management Software')</title>
<x-variables :customizes="$customizes" />
@vite(['resources/css/app.css', 'resources/js/app.js'])
<!-- fontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
<!-- Google fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
<!-- Chart Js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
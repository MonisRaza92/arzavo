@extends('layouts.app')
@section('title', 'Documentation - Arzavo Educational Platform')
@section('content')
@include('arzavo.partials.navbar')

<!-- Documentation Header -->
<section class="bg-secondary py-16">
    <div class="container">
        <div class="text-center">
            <h1 class="text-4xl lg:text-5xl font-bold text-primary mb-6">
                Arzavo Documentation
            </h1>
            <p class="text-xl text-secondary max-w-3xl mx-auto mb-8">
                Complete guide to setting up, customizing, and managing your educational platform with Arzavo.
            </p>
            
            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto">
                <div class="relative">
                    <input type="text" placeholder="Search documentation..." 
                           class="w-full px-6 py-4 pl-12 bg-primary border-primary border-rounded input-focus text-primary text-lg">
                    <i class="fa-solid fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-tertiary"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Start Guide -->
<section class="bg-primary py-16">
    <div class="container">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">Quick Start Guide</h2>
            <p class="text-secondary">Get your educational platform up and running in just a few steps</p>
        </div>
        
        <div class="grid md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold">1</span>
                </div>
                <h3 class="text-lg font-semibold text-primary mb-2">Sign Up</h3>
                <p class="text-secondary text-sm">Create your Arzavo account and verify your email</p>
            </div>
            
            <div class="text-center">
                <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold">2</span>
                </div>
                <h3 class="text-lg font-semibold text-primary mb-2">Setup Tenant</h3>
                <p class="text-secondary text-sm">Configure your subdomain and basic settings</p>
            </div>
            
            <div class="text-center">
                <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold">3</span>
                </div>
                <h3 class="text-lg font-semibold text-primary mb-2">Customize</h3>
                <p class="text-secondary text-sm">Brand your platform with colors, logo, and content</p>
            </div>
            
            <div class="text-center">
                <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold">4</span>
                </div>
                <h3 class="text-lg font-semibold text-primary mb-2">Launch</h3>
                <p class="text-secondary text-sm">Add users, create courses, and go live</p>
            </div>
        </div>
    </div>
</section>

<!-- Documentation Categories -->
<section class="bg-secondary py-16">
    <div class="container">
        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <div class="bg-primary border-primary border-rounded p-6 sticky top-24">
                    <h3 class="text-lg font-semibold text-primary mb-4">Categories</h3>
                    <nav class="space-y-2">
                        <a href="#getting-started" class="block text-secondary hover:text-accent py-2 px-3 border-rounded hover:bg-secondary transition-all duration-300">
                            <i class="fa-solid fa-rocket mr-2"></i>Getting Started
                        </a>
                        <a href="#tenant-setup" class="block text-secondary hover:text-accent py-2 px-3 border-rounded hover:bg-secondary transition-all duration-300">
                            <i class="fa-solid fa-cog mr-2"></i>Tenant Setup
                        </a>
                        <a href="#user-management" class="block text-secondary hover:text-accent py-2 px-3 border-rounded hover:bg-secondary transition-all duration-300">
                            <i class="fa-solid fa-users mr-2"></i>User Management
                        </a>
                        <a href="#course-creation" class="block text-secondary hover:text-accent py-2 px-3 border-rounded hover:bg-secondary transition-all duration-300">
                            <i class="fa-solid fa-graduation-cap mr-2"></i>Course Management
                        </a>
                        <a href="#customization" class="block text-secondary hover:text-accent py-2 px-3 border-rounded hover:bg-secondary transition-all duration-300">
                            <i class="fa-solid fa-palette mr-2"></i>Customization
                        </a>
                        <a href="#page-builder" class="block text-secondary hover:text-accent py-2 px-3 border-rounded hover:bg-secondary transition-all duration-300">
                            <i class="fa-solid fa-hammer mr-2"></i>Page Builder
                        </a>
                        <a href="#payment-setup" class="block text-secondary hover:text-accent py-2 px-3 border-rounded hover:bg-secondary transition-all duration-300">
                            <i class="fa-solid fa-credit-card mr-2"></i>Payment Setup
                        </a>
                        <a href="#troubleshooting" class="block text-secondary hover:text-accent py-2 px-3 border-rounded hover:bg-secondary transition-all duration-300">
                            <i class="fa-solid fa-wrench mr-2"></i>Troubleshooting
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-12">
                <!-- Getting Started -->
                <div id="getting-started" class="bg-primary border-primary border-rounded p-8">
                    <h2 class="text-2xl font-bold text-primary mb-6">
                        <i class="fa-solid fa-rocket mr-3 text-accent"></i>Getting Started
                    </h2>
                    
                    <div class="space-y-6">
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">Account Creation</h3>
                            <p class="text-secondary mb-4">
                                Start your journey with Arzavo by creating your account and understanding the platform basics.
                            </p>
                            <div class="bg-secondary p-4 border-rounded mb-4">
                                <h4 class="font-semibold text-primary mb-2">Step-by-Step Process:</h4>
                                <ol class="list-decimal list-inside space-y-2 text-tertiary">
                                    <li>Visit arzavo.in and click "Get Started"</li>
                                    <li>Fill in your personal and institution details</li>
                                    <li>Verify your email address</li>
                                    <li>Complete your profile setup</li>
                                    <li>Access your dashboard</li>
                                </ol>
                            </div>
                        </div>
                        
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">Dashboard Overview</h3>
                            <p class="text-secondary mb-4">
                                Understand your main dashboard and navigation to get the most out of Arzavo.
                            </p>
                            <ul class="space-y-2 text-tertiary">
                                <li class="flex items-center">
                                    <i class="fa-solid fa-check text-accent mr-2"></i>
                                    Tenant management and creation
                                </li>
                                <li class="flex items-center">
                                    <i class="fa-solid fa-check text-accent mr-2"></i>
                                    Subscription and billing overview
                                </li>
                                <li class="flex items-center">
                                    <i class="fa-solid fa-check text-accent mr-2"></i>
                                    Quick access to platform features
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Tenant Setup -->
                <div id="tenant-setup" class="bg-primary border-primary border-rounded p-8">
                    <h2 class="text-2xl font-bold text-primary mb-6">
                        <i class="fa-solid fa-cog mr-3 text-accent"></i>Tenant Setup
                    </h2>
                    
                    <div class="space-y-6">
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">Creating Your First Tenant</h3>
                            <p class="text-secondary mb-4">
                                Set up your educational platform with a unique subdomain and basic configuration.
                            </p>
                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                <div class="bg-secondary p-4 border-rounded">
                                    <h4 class="font-semibold text-primary mb-2">Basic Information</h4>
                                    <ul class="text-tertiary text-sm space-y-1">
                                        <li>• Institution name and description</li>
                                        <li>• Contact information</li>
                                        <li>• Address and location</li>
                                    </ul>
                                </div>
                                <div class="bg-secondary p-4 border-rounded">
                                    <h4 class="font-semibold text-primary mb-2">Domain Configuration</h4>
                                    <ul class="text-tertiary text-sm space-y-1">
                                        <li>• Choose subdomain (e.g., school.arzavo.in)</li>
                                        <li>• Custom domain setup (optional)</li>
                                        <li>• SSL certificate configuration</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">Domain Verification</h3>
                            <p class="text-secondary mb-4">
                                Learn how to verify your custom domain and ensure proper DNS configuration.
                            </p>
                            <div class="bg-invert text-invert p-4 border-rounded mb-4 font-mono text-sm">
                                <div class="text-accent-secondary mb-2">DNS Records Required:</div>
                                <div>CNAME: www.yourdomain.com → your-tenant.arzavo.in</div>
                                <div>A Record: yourdomain.com → 123.456.789.0</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- User Management -->
                <div id="user-management" class="bg-primary border-primary border-rounded p-8">
                    <h2 class="text-2xl font-bold text-primary mb-6">
                        <i class="fa-solid fa-users mr-3 text-accent"></i>User Management
                    </h2>
                    
                    <div class="space-y-6">
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">User Roles & Permissions</h3>
                            <p class="text-secondary mb-4">
                                Understand the different user roles and how to manage permissions effectively.
                            </p>
                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                <div class="bg-secondary p-4 border-rounded">
                                    <h4 class="font-semibold text-primary mb-2 flex items-center">
                                        <i class="fa-solid fa-crown text-accent mr-2"></i>Admin
                                    </h4>
                                    <p class="text-tertiary text-sm">Full platform access, user management, settings configuration</p>
                                </div>
                                <div class="bg-secondary p-4 border-rounded">
                                    <h4 class="font-semibold text-primary mb-2 flex items-center">
                                        <i class="fa-solid fa-chalkboard-teacher text-accent mr-2"></i>Teacher
                                    </h4>
                                    <p class="text-tertiary text-sm">Course creation, student management, content upload</p>
                                </div>
                                <div class="bg-secondary p-4 border-rounded">
                                    <h4 class="font-semibold text-primary mb-2 flex items-center">
                                        <i class="fa-solid fa-user-graduate text-accent mr-2"></i>Student
                                    </h4>
                                    <p class="text-tertiary text-sm">Course access, profile management, assignment submission</p>
                                </div>
                                <div class="bg-secondary p-4 border-rounded">
                                    <h4 class="font-semibold text-primary mb-2 flex items-center">
                                        <i class="fa-solid fa-user-tie text-accent mr-2"></i>Staff
                                    </h4>
                                    <p class="text-tertiary text-sm">Administrative support, limited management access</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">Adding Users</h3>
                            <p class="text-secondary mb-4">
                                Learn different methods to add users to your platform.
                            </p>
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-accent text-invert w-6 h-6 border-rounded flex items-center justify-center text-sm font-bold">1</div>
                                    <div>
                                        <h5 class="font-semibold text-primary">Manual Addition</h5>
                                        <p class="text-tertiary text-sm">Add users one by one through the admin panel</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="bg-accent text-invert w-6 h-6 border-rounded flex items-center justify-center text-sm font-bold">2</div>
                                    <div>
                                        <h5 class="font-semibold text-primary">Bulk Import</h5>
                                        <p class="text-tertiary text-sm">Upload CSV files with multiple user records</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="bg-accent text-invert w-6 h-6 border-rounded flex items-center justify-center text-sm font-bold">3</div>
                                    <div>
                                        <h5 class="font-semibold text-primary">Self Registration</h5>
                                        <p class="text-tertiary text-sm">Allow users to register themselves with approval workflow</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Course Management -->
                <div id="course-creation" class="bg-primary border-primary border-rounded p-8">
                    <h2 class="text-2xl font-bold text-primary mb-6">
                        <i class="fa-solid fa-graduation-cap mr-3 text-accent"></i>Course Management
                    </h2>
                    
                    <div class="space-y-6">
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">Creating Courses</h3>
                            <p class="text-secondary mb-4">
                                Step-by-step guide to creating engaging courses with multimedia content.
                            </p>
                            <div class="bg-secondary p-4 border-rounded mb-4">
                                <h4 class="font-semibold text-primary mb-2">Course Creation Workflow:</h4>
                                <div class="space-y-2 text-tertiary text-sm">
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-arrow-right text-accent mr-2"></i>
                                        Basic Information (Title, Description, Category)
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-arrow-right text-accent mr-2"></i>
                                        Pricing & Enrollment Settings
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-arrow-right text-accent mr-2"></i>
                                        Content Upload (Videos, Notes, Books)
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-arrow-right text-accent mr-2"></i>
                                        Class & Subject Assignment
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-arrow-right text-accent mr-2"></i>
                                        Publishing & Management
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">Content Management</h3>
                            <p class="text-secondary mb-4">
                                Organize and manage different types of educational content.
                            </p>
                            <div class="grid md:grid-cols-3 gap-4">
                                <div class="bg-secondary p-4 border-rounded text-center">
                                    <i class="fa-solid fa-file-pdf text-accent text-2xl mb-2"></i>
                                    <h5 class="font-semibold text-primary mb-1">Notes</h5>
                                    <p class="text-tertiary text-xs">PDF documents, study materials</p>
                                </div>
                                <div class="bg-secondary p-4 border-rounded text-center">
                                    <i class="fa-solid fa-book text-accent text-2xl mb-2"></i>
                                    <h5 class="font-semibold text-primary mb-1">Books</h5>
                                    <p class="text-tertiary text-xs">Textbooks, reference materials</p>
                                </div>
                                <div class="bg-secondary p-4 border-rounded text-center">
                                    <i class="fa-solid fa-video text-accent text-2xl mb-2"></i>
                                    <h5 class="font-semibold text-primary mb-1">Videos</h5>
                                    <p class="text-tertiary text-xs">Lectures, tutorials, demos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Customization -->
                <div id="customization" class="bg-primary border-primary border-rounded p-8">
                    <h2 class="text-2xl font-bold text-primary mb-6">
                        <i class="fa-solid fa-palette mr-3 text-accent"></i>Customization
                    </h2>
                    
                    <div class="space-y-6">
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">Branding & Appearance</h3>
                            <p class="text-secondary mb-4">
                                Customize your platform's look and feel to match your institution's brand.
                            </p>
                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                <div class="bg-secondary p-4 border-rounded">
                                    <h4 class="font-semibold text-primary mb-2">Visual Elements</h4>
                                    <ul class="text-tertiary text-sm space-y-1">
                                        <li>• Logo upload and positioning</li>
                                        <li>• Favicon configuration</li>
                                        <li>• Color scheme selection</li>
                                        <li>• Font family choices</li>
                                    </ul>
                                </div>
                                <div class="bg-secondary p-4 border-rounded">
                                    <h4 class="font-semibold text-primary mb-2">Layout Options</h4>
                                    <ul class="text-tertiary text-sm space-y-1">
                                        <li>• Border radius settings</li>
                                        <li>• Border width options</li>
                                        <li>• Background patterns</li>
                                        <li>• Component spacing</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">Color Schemes</h3>
                            <p class="text-secondary mb-4">
                                Create and manage custom color schemes for your platform.
                            </p>
                            <div class="bg-secondary p-4 border-rounded">
                                <h4 class="font-semibold text-primary mb-2">Available Color Options:</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-accent border-rounded mr-2"></div>
                                        <span class="text-tertiary">Primary Colors</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-secondary border-rounded mr-2"></div>
                                        <span class="text-tertiary">Background Colors</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-tertiary border-rounded mr-2"></div>
                                        <span class="text-tertiary">Text Colors</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-4 h-4 bg-primary border-rounded mr-2"></div>
                                        <span class="text-tertiary">Accent Colors</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Page Builder -->
                <div id="page-builder" class="bg-primary border-primary border-rounded p-8">
                    <h2 class="text-2xl font-bold text-primary mb-6">
                        <i class="fa-solid fa-hammer mr-3 text-accent"></i>Page Builder
                    </h2>
                    
                    <div class="space-y-6">
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">Understanding the Page Builder</h3>
                            <p class="text-secondary mb-4">
                                Create custom pages using our hierarchical page builder system.
                            </p>
                            <div class="bg-secondary p-4 border-rounded mb-4">
                                <h4 class="font-semibold text-primary mb-2">Page Structure:</h4>
                                <div class="space-y-2 text-tertiary text-sm">
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-file text-accent mr-2"></i>
                                        <strong>Pages</strong> - Top level containers
                                    </div>
                                    <div class="flex items-center ml-4">
                                        <i class="fa-solid fa-layer-group text-accent mr-2"></i>
                                        <strong>Sections</strong> - Major content areas
                                    </div>
                                    <div class="flex items-center ml-8">
                                        <i class="fa-solid fa-cube text-accent mr-2"></i>
                                        <strong>Blocks</strong> - Individual content elements
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">Creating Custom Pages</h3>
                            <p class="text-secondary mb-4">
                                Step-by-step process to build custom pages for your website.
                            </p>
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-accent text-invert w-6 h-6 border-rounded flex items-center justify-center text-sm font-bold">1</div>
                                    <div>
                                        <h5 class="font-semibold text-primary">Create Page</h5>
                                        <p class="text-tertiary text-sm">Set page title, slug, and basic settings</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="bg-accent text-invert w-6 h-6 border-rounded flex items-center justify-center text-sm font-bold">2</div>
                                    <div>
                                        <h5 class="font-semibold text-primary">Add Sections</h5>
                                        <p class="text-tertiary text-sm">Create sections to organize your content</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="bg-accent text-invert w-6 h-6 border-rounded flex items-center justify-center text-sm font-bold">3</div>
                                    <div>
                                        <h5 class="font-semibold text-primary">Insert Blocks</h5>
                                        <p class="text-tertiary text-sm">Add content blocks within sections</p>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3">
                                    <div class="bg-accent text-invert w-6 h-6 border-rounded flex items-center justify-center text-sm font-bold">4</div>
                                    <div>
                                        <h5 class="font-semibold text-primary">Publish</h5>
                                        <p class="text-tertiary text-sm">Review and publish your page</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Setup -->
                <div id="payment-setup" class="bg-primary border-primary border-rounded p-8">
                    <h2 class="text-2xl font-bold text-primary mb-6">
                        <i class="fa-solid fa-credit-card mr-3 text-accent"></i>Payment Setup
                    </h2>
                    
                    <div class="space-y-6">
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">Fee Management</h3>
                            <p class="text-secondary mb-4">
                                Set up and manage student fees, course pricing, and payment tracking.
                            </p>
                            <div class="grid md:grid-cols-2 gap-4 mb-4">
                                <div class="bg-secondary p-4 border-rounded">
                                    <h4 class="font-semibold text-primary mb-2">Fee Plans</h4>
                                    <ul class="text-tertiary text-sm space-y-1">
                                        <li>• Create custom fee structures</li>
                                        <li>• Set due dates and installments</li>
                                        <li>• Apply discounts and scholarships</li>
                                    </ul>
                                </div>
                                <div class="bg-secondary p-4 border-rounded">
                                    <h4 class="font-semibold text-primary mb-2">Payment Tracking</h4>
                                    <ul class="text-tertiary text-sm space-y-1">
                                        <li>• Monitor payment status</li>
                                        <li>• Generate payment reports</li>
                                        <li>• Send payment reminders</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">Course Pricing</h3>
                            <p class="text-secondary mb-4">
                                Configure pricing for individual courses and manage enrollments.
                            </p>
                            <div class="bg-secondary p-4 border-rounded">
                                <h4 class="font-semibold text-primary mb-2">Pricing Options:</h4>
                                <div class="grid md:grid-cols-3 gap-3 text-sm">
                                    <div class="text-center">
                                        <i class="fa-solid fa-tag text-accent text-lg mb-1"></i>
                                        <div class="font-semibold text-primary">Fixed Price</div>
                                        <div class="text-tertiary">One-time payment</div>
                                    </div>
                                    <div class="text-center">
                                        <i class="fa-solid fa-percentage text-accent text-lg mb-1"></i>
                                        <div class="font-semibold text-primary">Discounted</div>
                                        <div class="text-tertiary">Special offers</div>
                                    </div>
                                    <div class="text-center">
                                        <i class="fa-solid fa-gift text-accent text-lg mb-1"></i>
                                        <div class="font-semibold text-primary">Free</div>
                                        <div class="text-tertiary">No cost courses</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Troubleshooting -->
                <div id="troubleshooting" class="bg-primary border-primary border-rounded p-8">
                    <h2 class="text-2xl font-bold text-primary mb-6">
                        <i class="fa-solid fa-wrench mr-3 text-accent"></i>Troubleshooting
                    </h2>
                    
                    <div class="space-y-6">
                        <div class="border-l-4 border-accent pl-6">
                            <h3 class="text-xl font-semibold text-primary mb-3">Common Issues</h3>
                            <p class="text-secondary mb-4">
                                Solutions to frequently encountered problems and their fixes.
                            </p>
                            
                            <div class="space-y-4">
                                <div class="bg-secondary p-4 border-rounded">
                                    <h4 class="font-semibold text-primary mb-2">Domain Not Working</h4>
                                    <p class="text-tertiary text-sm mb-2">If your custom domain is not accessible:</p>
                                    <ul class="text-tertiary text-sm space-y-1 ml-4">
                                        <li>• Check DNS records configuration</li>
                                        <li>• Verify domain verification status</li>
                                        <li>• Wait for DNS propagation (24-48 hours)</li>
                                        <li>• Contact support if issue persists</li>
                                    </ul>
                                </div>
                                
                                <div class="bg-secondary p-4 border-rounded">
                                    <h4 class="font-semibold text-primary mb-2">Login Issues</h4>
                                    <p class="text-tertiary text-sm mb-2">If users cannot log in to the platform:</p>
                                    <ul class="text-tertiary text-sm space-y-1 ml-4">
                                        <li>• Verify email address is correct</li>
                                        <li>• Check if account is active</li>
                                        <li>• Reset password if needed</li>
                                        <li>• Clear browser cache and cookies</li>
                                    </ul>
                                </div>
                                
                                <div class="bg-secondary p-4 border-rounded">
                                    <h4 class="font-semibold text-primary mb-2">File Upload Problems</h4>
                                    <p class="text-tertiary text-sm mb-2">If content uploads are failing:</p>
                                    <ul class="text-tertiary text-sm space-y-1 ml-4">
                                        <li>• Check file size limits (max 100MB)</li>
                                        <li>• Verify supported file formats</li>
                                        <li>• Ensure stable internet connection</li>
                                        <li>• Try uploading smaller files first</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Help & Support -->
<section class="bg-primary py-16">
    <div class="container">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-4">Need More Help?</h2>
            <p class="text-secondary">Our support team is here to help you succeed</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-comments text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-primary mb-3">Live Chat</h3>
                <p class="text-secondary mb-4">Get instant help during business hours (9 AM - 6 PM IST)</p>
                <a href="#" class="bg-accent text-invert px-6 py-3 border-rounded font-semibold hover-invert transition-all duration-300 inline-block">
                    Start Chat
                </a>
            </div>
            
            <div class="text-center">
                <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-envelope text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-primary mb-3">Email Support</h3>
                <p class="text-secondary mb-4">Detailed responses within 24 hours</p>
                <a href="mailto:support@arzavo.in" class="bg-secondary text-primary px-6 py-3 border-rounded font-semibold hover-primary transition-all duration-300 inline-block">
                    Send Email
                </a>
            </div>
            
            <div class="text-center">
                <div class="bg-accent text-invert w-16 h-16 border-rounded flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-phone text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-primary mb-3">Phone Support</h3>
                <p class="text-secondary mb-4">Direct phone support for urgent issues</p>
                <a href="tel:+919876543210" class="bg-secondary text-primary px-6 py-3 border-rounded font-semibold hover-primary transition-all duration-300 inline-block">
                    Call Now
                </a>
            </div>
        </div>
    </div>
</section>

@include('arzavo.partials.footer')
@endsection
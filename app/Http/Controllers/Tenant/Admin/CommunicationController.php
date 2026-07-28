<?php

namespace App\Http\Controllers\Tenant\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Inquiry;
use App\Models\Tenant\NewsletterSubscriber;

class CommunicationController extends Controller
{
    public function inquiries()
    {
        $inquiries = Inquiry::orderBy('created_at', 'desc')->paginate(15);
        return view('tenant.admin.communication.inquiries', compact('inquiries'));
    }

    public function inquiryDelete($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->delete();
        return back()->with('success', 'Inquiry deleted successfully.');
    }

    public function subscribers()
    {
        $subscribers = NewsletterSubscriber::orderBy('created_at', 'desc')->paginate(15);
        return view('tenant.admin.communication.subscribers', compact('subscribers'));
    }

    public function subscriberDelete($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->delete();
        return back()->with('success', 'Subscriber deleted successfully.');
    }
}

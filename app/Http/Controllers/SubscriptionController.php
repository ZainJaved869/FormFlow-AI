<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentPlan = $user->subscription_plan ?? 'free';
        
        $plans = [
            'free' => [
                'name' => 'Free',
                'price' => '$0',
                'period' => 'forever',
                'features' => ['5 forms', '100 submissions', 'Basic analytics'],
                'color' => 'secondary'
            ],
            'pro' => [
                'name' => 'Pro',
                'price' => '$29',
                'period' => '/ month',
                'features' => ['Unlimited forms', '10,000 submissions', 'Advanced analytics', 'Export CSV/Excel/PDF', 'AI Generator'],
                'color' => 'primary'
            ],
            'business' => [
                'name' => 'Business',
                'price' => '$99',
                'period' => '/ month',
                'features' => ['Everything in Pro', '50,000 submissions', 'Custom branding', 'Webhooks', 'API access', 'Priority support'],
                'color' => 'dark'
            ]
        ];

        return view('subscription.index', compact('currentPlan', 'plans'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $plan = $request->plan;
        
        // Simple plan update – no Stripe
        $user->subscription_plan = $plan;
        $user->save();

        return redirect()->route('subscription.index')
            ->with('success', 'Plan updated to ' . ucfirst($plan) . ' successfully!');
    }

    public function cancel()
    {
        $user = Auth::user();
        $user->subscription_plan = 'free';
        $user->save();

        return redirect()->route('subscription.index')
            ->with('success', 'Subscription cancelled. You are now on the Free plan.');
    }
}
<?php

namespace Database\Seeders;



use App\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'name' => 'Student Admission Form',
                'slug' => 'student-admission',
                'category' => 'admission',
                'icon' => 'bi bi-person-graduate',
                'description' => 'A complete student admission form with personal details and course selection.',
                'fields' => [
                    ['type' => 'text', 'label' => 'Full Name', 'placeholder' => 'Enter full name', 'required' => true],
                    ['type' => 'email', 'label' => 'Email Address', 'placeholder' => 'you@example.com', 'required' => true],
                    ['type' => 'phone', 'label' => 'Phone Number', 'placeholder' => '+1 234 567 890', 'required' => true],
                    ['type' => 'date', 'label' => 'Date of Birth', 'required' => true],
                    ['type' => 'dropdown', 'label' => 'Course Applied For', 'options' => ['Computer Science', 'Business', 'Engineering', 'Medicine'], 'required' => true],
                    ['type' => 'textarea', 'label' => 'Previous Education', 'placeholder' => 'Describe your academic background', 'required' => false],
                    ['type' => 'file', 'label' => 'Upload Transcript', 'required' => false],
                ]
            ],
            [
                'name' => 'Job Application Form',
                'slug' => 'job-application',
                'category' => 'job',
                'icon' => 'bi bi-briefcase',
                'description' => 'A professional job application form with personal info, cover letter, and CV upload.',
                'fields' => [
                    ['type' => 'text', 'label' => 'Full Name', 'placeholder' => 'Your full name', 'required' => true],
                    ['type' => 'email', 'label' => 'Email', 'placeholder' => 'you@example.com', 'required' => true],
                    ['type' => 'phone', 'label' => 'Phone', 'placeholder' => '+1 234 567 890', 'required' => true],
                    ['type' => 'dropdown', 'label' => 'Position Applied For', 'options' => ['Developer', 'Designer', 'Manager', 'Sales'], 'required' => true],
                    ['type' => 'textarea', 'label' => 'Cover Letter', 'placeholder' => 'Tell us about yourself', 'required' => false],
                    ['type' => 'file', 'label' => 'Upload CV', 'required' => true],
                ]
            ],
            [
                'name' => 'Customer Feedback Survey',
                'slug' => 'feedback-survey',
                'category' => 'feedback',
                'icon' => 'bi bi-chat-dots',
                'description' => 'Gather customer feedback with ratings, suggestions, and satisfaction metrics.',
                'fields' => [
                    ['type' => 'text', 'label' => 'Your Name', 'placeholder' => 'Optional', 'required' => false],
                    ['type' => 'email', 'label' => 'Email', 'placeholder' => 'Optional', 'required' => false],
                    ['type' => 'rating', 'label' => 'Overall Experience', 'required' => true],
                    ['type' => 'radio', 'label' => 'Would you recommend us?', 'options' => ['Yes', 'No', 'Maybe'], 'required' => true],
                    ['type' => 'textarea', 'label' => 'What did you like?', 'placeholder' => 'Your feedback', 'required' => false],
                    ['type' => 'textarea', 'label' => 'Suggestions for improvement', 'placeholder' => '...', 'required' => false],
                ]
            ],
            [
                'name' => 'Event Registration Form',
                'slug' => 'event-registration',
                'category' => 'event',
                'icon' => 'bi bi-calendar-check',
                'description' => 'Register attendees for events, conferences, and workshops.',
                'fields' => [
                    ['type' => 'text', 'label' => 'Full Name', 'placeholder' => 'Your name', 'required' => true],
                    ['type' => 'email', 'label' => 'Email', 'placeholder' => 'you@example.com', 'required' => true],
                    ['type' => 'phone', 'label' => 'Phone', 'placeholder' => '+1 234 567 890', 'required' => true],
                    ['type' => 'dropdown', 'label' => 'Event Session', 'options' => ['Morning', 'Afternoon', 'Evening'], 'required' => true],
                    ['type' => 'checkbox', 'label' => 'I agree to the terms and conditions', 'required' => true],
                ]
            ],
            [
                'name' => 'Donation Form',
                'slug' => 'donation-form',
                'category' => 'donation',
                'icon' => 'bi bi-heart',
                'description' => 'Accept donations with amount selection, payment method, and donor details.',
                'fields' => [
                    ['type' => 'text', 'label' => 'Full Name', 'placeholder' => 'Your name', 'required' => true],
                    ['type' => 'email', 'label' => 'Email', 'placeholder' => 'you@example.com', 'required' => true],
                    ['type' => 'radio', 'label' => 'Donation Amount', 'options' => ['$10', '$25', '$50', 'Other'], 'required' => true],
                    ['type' => 'dropdown', 'label' => 'Payment Method', 'options' => ['Credit Card', 'PayPal', 'Bank Transfer'], 'required' => true],
                    ['type' => 'checkbox', 'label' => 'I want to remain anonymous', 'required' => false],
                ]
            ],
            [
                'name' => 'Order Form',
                'slug' => 'order-form',
                'category' => 'order',
                'icon' => 'bi bi-cart',
                'description' => 'A simple order form for products or services.',
                'fields' => [
                    ['type' => 'text', 'label' => 'Customer Name', 'placeholder' => 'Your name', 'required' => true],
                    ['type' => 'email', 'label' => 'Email', 'placeholder' => 'you@example.com', 'required' => true],
                    ['type' => 'phone', 'label' => 'Phone', 'placeholder' => '+1 234 567 890', 'required' => true],
                    ['type' => 'dropdown', 'label' => 'Product', 'options' => ['Product A', 'Product B', 'Product C'], 'required' => true],
                    ['type' => 'text', 'label' => 'Quantity', 'placeholder' => '1', 'required' => true],
                    ['type' => 'textarea', 'label' => 'Delivery Address', 'placeholder' => 'Street, city, zip code', 'required' => true],
                ]
            ],
        ];

        foreach ($templates as $template) {
            Template::create($template);
        }
    }
}

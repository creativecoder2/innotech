<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Service;
use App\Models\Blog;
use App\Models\Testimonial;
use App\Models\Partner;
use App\Models\GalleryItem;
use App\Models\TeamMember;
use App\Models\NavMenu;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::firstOrCreate(
            ['email' => 'admin@innotech.com'],
            [
                'name' => 'Innotech Admin',
                'password' => Hash::make('password123'),
            ]
        );

        // General & Section Settings
        $settings = [
            'site_title' => 'INNOTECH MEDICAL PVT LTD',
            'site_tagline' => 'Innovating Health Care with Advance Technologies',
            'support_email' => 'info@innotecmedical.org',
            'helpdesk_phone' => '+92 331 6699992',
            'office_address' => '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.',
            'working_hours' => 'Office Hours: 10AM - 6PM',
            'logo_path' => 'assets/img/logo/logo.png',
            'favicon_path' => 'assets/img/logo/favicon.png',
            'facebook_url' => 'https://facebook.com',
            'twitter_url' => 'https://twitter.com',
            'youtube_url' => 'https://youtube.com',
            'linkedin_url' => 'https://linkedin.com',
            'copyright_text' => '© Copyright ©2026 - 2027 INNOTECH MEDICAL Pvt Ltd. All Rights Reserved',
            'footer_about' => 'Innotech Medical Pvt Ltd is Growing distributor of top-quality medical equipment across Pakistan At Innotech Medical.',

            // Section Toggles
            'section_header_enabled' => '1',
            'section_banner_enabled' => '1',
            'section_services_enabled' => '1',
            'section_about_enabled' => '1',
            'section_counter_enabled' => '1',
            'section_gallery_enabled' => '1',
            'section_choose_enabled' => '1',
            'section_appointment_enabled' => '1',
            'section_team_enabled' => '1',
            'section_testimonial_enabled' => '1',
            'section_brand_enabled' => '1',
            'section_cta_enabled' => '1',
            'section_blog_enabled' => '1',
            'section_footer_enabled' => '1',

            // Section Contents
            'banner_badge' => 'Welcome to Innotech Medical Pvt Ltd',
            'banner_title' => 'Innovating Health Care with Advance Technologies',
            'banner_description' => 'Innotech Medical Pvt Ltd is Growing distributor of top-quality medical equipment across Pakistan At Innotech Medical, we are dedicated to bridging the gap between world-class medical innovation and Pakistan’s healthcare sector. As a leading provider of advanced biomedical technologies, diagnostic systems, and specialized clinical equipment, we empower healthcare institutions to deliver accurate diagnoses and superior patient care.',
            'banner_btn_text' => 'Contact with Us',
            'banner_btn_link' => '/contact',
            'banner_btn2_text' => 'About us',
            'banner_btn2_link' => '/about',
            'banner_video_url' => 'https://www.youtube.com/watch?v=d8w5SICzzxc',
            'banner_feature_1' => '100% Customer Satisfaction',
            'banner_feature_2' => 'Help and Acess is Our Mission',
            'banner_feature_3' => '100% Quality Laboratory service',

            'services_subtitle' => 'our Services',
            'services_title' => 'Service Area',
            'services_search_placeholder' => 'What are you looking for?',

            'about_experience_years' => '7',
            'about_experience_label' => 'Years of Experience',
            'about_badge' => 'Welcome to Innotech Medical Pvt Ltd',
            'about_heading' => 'Innovating Healthcare with Advance Technologies',
            'about_italic_text' => '—Empowering hospitals, diagnostic labs, and surgical suites with world-class technology and end-to-end engineering support.',
            'about_description' => 'Innotech Medical Pvt Ltd is Established & Reputable distributor of top-quality medical equipment across Pakistan. From state-of-the-art Medical Devices and Surgical Disposable solutions to comprehensive turnkey hospital projects, our commitment goes beyond equipment distribution. We provide end-to-end technical support, regulatory compliance, and seamless integration, ensuring that healthcare providers across the nation have access to reliable, cutting-edge medical technologies.',
            'about_point_1' => 'Critical Care & ICU Equipment',
            'about_point_2' => 'Advanced Diagnostic & Lab Instruments',
            'about_point_3' => 'Operating Room & General Medical Solutions',
            'about_point_4' => 'Turnkey Projects & Technical Support',

            'counter_1_number' => '1492',
            'counter_1_title' => 'Laboratories in 100+ states',
            'counter_2_number' => '152',
            'counter_2_title' => 'Laboratory specialists',
            'counter_3_number' => '1022',
            'counter_3_title' => 'Material collection points',
            'counter_4_number' => '24332',
            'counter_4_title' => 'Patients diagnosed in 2022',

            'gallery_subtitle' => 'Work Gallery',
            'gallery_title' => 'INNOTECH Gallery',
            'gallery_btn_text' => 'Explore More',
            'gallery_btn_link' => '/contact',

            'choose_subtitle' => 'Our Specialists',
            'choose_title' => 'Why Choose Us',
            'choose_card1_title' => 'Global Standards & Quality',
            'choose_card1_desc' => 'Delivering FDA, CE, and ISO certified medical equipment from global principal brands, ensuring maximum clinical accuracy and patient safety.',
            'choose_card2_title' => 'Swift Turnkey Delivery',
            'choose_card2_desc' => 'Streamlined procurement and rapid installation, delivering complete healthcare projects with minimal turnaround time and total precision.',
            'choose_card3_title' => '24/7 Emergency Support',
            'choose_card3_desc' => 'Round-the-clock technical coverage and rapid dispatch troubleshooting to eliminate critical equipment downtime in ICUs and OTs.',
            'choose_card4_title' => 'Certified Biomedical Experts',
            'choose_card4_desc' => 'Backed by OEM-trained engineers executing precision calibration, complex repairs, and proactive maintenance to global standards.',
            'choose_bottom_text' => 'Scientific Research Laboratories:',
            'choose_bottom_btn_text' => 'Contact Us',
            'choose_bottom_btn_link' => '/contact',

            'appointment_title' => 'GET IN TOUCH WITH US',
            'appointment_phone' => '+92 331 6699992',
            'appointment_subtitle' => '24/7 Emergency Service',

            'team_subtitle' => 'Our Team',
            'team_title' => 'Meet Specialist',

            'testimonial_subtitle' => 'Testimonial',
            'testimonial_title' => 'Customer Feedback',

            'cta_title' => 'Looking for a best lebatory Service',
            'cta_phone' => '+92 331 6699992',
            'cta_btn_text' => 'Call :',

            'blog_subtitle' => 'Waht’s New',
            'blog_title' => 'Blog & Article',
        ];

        foreach ($settings as $k => $v) {
            Setting::set($k, $v);
        }

        // Default Nav Menus
        if (NavMenu::count() == 0) {
            $home = NavMenu::create([
                'title' => 'Home',
                'url' => '/',
                'page_route' => 'home',
                'order' => 1,
                'show_on_home' => true,
                'show_on_inner' => true,
                'is_active' => true,
            ]);

            $about = NavMenu::create([
                'title' => 'About',
                'url' => '/about',
                'page_route' => 'about',
                'order' => 2,
                'show_on_home' => true,
                'show_on_inner' => true,
                'is_active' => true,
            ]);

            $services = NavMenu::create([
                'title' => 'Products & Services',
                'url' => '#',
                'page_route' => 'custom',
                'order' => 3,
                'show_on_home' => true,
                'show_on_inner' => true,
                'is_active' => true,
            ]);

            NavMenu::create([
                'title' => 'Medical Equipment & Devices',
                'url' => '/services/medical-equipment-icu-systems',
                'page_route' => 'service',
                'parent_id' => $services->id,
                'order' => 1,
                'show_on_home' => true,
                'show_on_inner' => true,
                'is_active' => true,
            ]);

            NavMenu::create([
                'title' => 'Diagnostic & Lab Systems',
                'url' => '/services/biomedical-diagnostic-laboratory',
                'page_route' => 'service',
                'parent_id' => $services->id,
                'order' => 2,
                'show_on_home' => true,
                'show_on_inner' => true,
                'is_active' => true,
            ]);

            NavMenu::create([
                'title' => 'Consumables & Disposables',
                'url' => '/services/surgical-solutions-disposables',
                'page_route' => 'service',
                'parent_id' => $services->id,
                'order' => 3,
                'show_on_home' => true,
                'show_on_inner' => true,
                'is_active' => true,
            ]);

            $partners = NavMenu::create([
                'title' => 'Partners',
                'url' => '/#brand-section',
                'page_route' => 'partners',
                'order' => 4,
                'show_on_home' => true,
                'show_on_inner' => true,
                'is_active' => true,
            ]);

            $contact = NavMenu::create([
                'title' => 'Contact',
                'url' => '/contact',
                'page_route' => 'contact',
                'order' => 5,
                'show_on_home' => true,
                'show_on_inner' => true,
                'is_active' => true,
            ]);
        }
    }
}

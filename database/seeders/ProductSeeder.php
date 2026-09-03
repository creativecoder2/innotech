<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Product;
use App\Models\NavMenu;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SEED COMPANIES
        $companiesData = [
            [
                'name' => 'Elektro-mag',
                'slug' => 'elektro-mag',
                'country' => 'Turkey',
                'logo' => 'assets/img/brand/brand-01.png',
                'description' => 'Elektro-mag is a premier Turkish manufacturer specializing in advanced neonatal intensive care equipment, infant radiant warmers, and hospital clinical devices.',
                'website' => 'https://www.elektro-mag.com',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'BNG Medical',
                'slug' => 'bng-medical',
                'country' => 'Germany',
                'logo' => 'assets/img/brand/bng-medical.svg',
                'description' => 'BNG Medical Germany delivers precision 4K laparoscopy towers, endoscopy cameras, and cutting-edge operating theater infrastructure.',
                'website' => 'https://www.bngmedical.de',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Meditech Global',
                'slug' => 'meditech-global',
                'country' => 'Japan',
                'logo' => 'assets/img/brand/meditech.svg',
                'description' => 'Meditech Global Japan produces hospital patient monitoring systems, ICU mechanical ventilators, and high-reliability biomedical devices.',
                'website' => 'https://www.meditech-global.jp',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Scitek Instruments',
                'slug' => 'scitek-instruments',
                'country' => 'United States',
                'logo' => 'assets/img/brand/scitek.svg',
                'description' => 'Scitek Instruments USA develops high-resolution mobile C-Arm fluoroscopy units, digital radiography systems, and diagnostic imaging suites.',
                'website' => 'https://www.scitekinstruments.com',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Lynmou Surgical',
                'slug' => 'lynmou-surgical',
                'country' => 'United Kingdom',
                'logo' => 'assets/img/brand/lynmou.svg',
                'description' => 'Lynmou Surgical UK is renowned for flexible endoscopic systems, bronchoscopes, gastroscopes, and ergonomic surgical instrumentation.',
                'website' => 'https://www.lynmou-surgical.co.uk',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Micare Healthcare',
                'slug' => 'micare-healthcare',
                'country' => 'South Korea',
                'logo' => 'assets/img/brand/micare.svg',
                'description' => 'Micare Healthcare South Korea manufactures microprocessor-controlled electrosurgical diathermy generators and surgical shadowless lighting.',
                'website' => 'https://www.micare-health.kr',
                'order' => 6,
                'is_active' => true,
            ],
        ];

        $companies = [];
        foreach ($companiesData as $cData) {
            $companies[$cData['slug']] = Company::updateOrCreate(
                ['slug' => $cData['slug']],
                $cData
            );
        }

        // 2. SEED PRODUCTS
        $productsData = [
            [
                'company_id' => $companies['elektro-mag']->id,
                'title' => 'Elektro-mag M 308 Infant Radiant Warmer Unit (TFT)',
                'slug' => 'elektro-mag-m-308-infant-radiant-warmer-unit-tft',
                'sku' => 'EM-M308-TFT',
                'image' => 'assets/img/shop/shop-01.jpg',
                'short_description' => 'The Elektro-mag M 308 Infant Radiant Warmer Unit is a state-of-the-art neonatal care solution from Turkey.',
                'description' => "The Elektro-mag M 308 Infant Radiant Warmer Unit is engineered to provide precise thermal support and critical intervention capabilities for neonates in intensive care units and delivery suites.\n\nFeaturing advanced ceramic heating technology and intelligent PID thermal algorithms, the unit ensures uniform heat distribution while minimizing insensible fluid loss. The intuitive 7\" full-color TFT touch screen provides instant telemetry readouts, visual alarms, and APGAR countdown timing for clinical teams.",
                'key_features' => json_encode([
                    '7" Colored TFT Touch Screen Display',
                    'PID Digital Temperature Control (±0.1ºC accuracy)',
                    'Three Modes: Preheat, Manual, Baby Servo Control',
                    'Ceramic Heater Technology',
                    'Integrated APGAR Timer and examination light',
                    'Built-in Digital Scale',
                    'Trendelenburg Positioning',
                ]),
                'order' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'company_id' => $companies['lynmou-surgical']->id,
                'title' => 'GREATER SCOOPING AT EASE Endoscopic System',
                'slug' => 'greater-scooping-at-ease-endoscopic-system',
                'sku' => 'LYN-ENDO-400',
                'image' => 'assets/img/shop/shop-02.jpg',
                'short_description' => 'High-resolution flexible video endoscope system engineered with exceptional scooping maneuverability and optical clarity.',
                'description' => "Engineered for superior endoscopic intervention, the GREATER SCOOPING AT EASE system allows gastrointestinal specialists to perform detailed mucosa inspections and delicate biopsic scoops with minimal patient discomfort.\n\nEquipped with dual LED illumination, CMOS high-fidelity imaging sensors, and ultra-flexible insertion catheters.",
                'key_features' => json_encode([
                    '140° Ultra-Wide Viewing Angle with zero optical distortion',
                    'Smooth 4-Way Articulation with high-torque locking mechanism',
                    'Water Jet High-Pressure Channel for quick lens cleansing',
                    'Integrated NBI (Narrow Band Imaging) for vascular contrast',
                    'Fully Submersible and Autoclavable design',
                ]),
                'order' => 2,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'company_id' => $companies['bng-medical']->id,
                'title' => 'Full HD 4K Laparoscopy Surgical Tower',
                'slug' => 'full-hd-4k-laparoscopy-surgical-tower',
                'sku' => 'BNG-LAP-4K',
                'image' => 'assets/img/shop/shop-03.jpg',
                'short_description' => 'Complete modular surgical video tower integrating 4K camera console, 300W cold LED light, and 45L high-flow CO2 insufflator.',
                'description' => "Designed for advanced laparoscopic and general surgery suites, this modular system provides crisp resolution and true color reproduction. Ergonomically housed in an anti-static medical trolley with isolated power transformers.",
                'key_features' => json_encode([
                    '4K UHD Sony CMOS 3-Chip Camera Head with optical zoom',
                    '32-inch Medical Anti-Glare IPS Monitor (3840x2160)',
                    '45 Litres/min Automatic Gas Insufflator with pre-heating',
                    '300W Medical Cold LED Illuminator (6000K daylight balance)',
                    'Synchronized Digital Video & Still Capture to USB 3.0',
                ]),
                'order' => 3,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'company_id' => $companies['meditech-global']->id,
                'title' => 'Modular Multi-Parameter Patient Monitor (12.1" TFT)',
                'slug' => 'modular-multi-parameter-patient-monitor',
                'sku' => 'MDT-MPM-1200',
                'image' => 'assets/img/shop/shop-04.jpg',
                'short_description' => 'Comprehensive bedside critical care monitor for real-time tracking of ECG, SpO2, NIBP, Respiration, and Temp.',
                'description' => "The MDT-MPM-1200 offers reliable physiological tracking across ICUs, OTs, and recovery wards. Built-in pacemaker detection and defibrillator synchronization safeguard patient security during high-risk cardiac interventions.",
                'key_features' => json_encode([
                    '12.1-inch High-Resolution Anti-Glare Color TFT Display',
                    '7-Lead ECG Waveform Synchronized Display with Arrhythmia analysis',
                    'Digital Masimo / Nellcor SpO2 sensor compatibility',
                    'High-Frequency Electrosurgery and Defibrillation protection',
                    'Built-in Rechargeable Lithium Battery (4+ hours continuous operation)',
                ]),
                'order' => 4,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'company_id' => $companies['micare-healthcare']->id,
                'title' => 'Microprocessor Electrosurgical Diathermy Generator (400W)',
                'slug' => 'microprocessor-electrosurgical-diathermy-generator',
                'sku' => 'MIC-ESU-400',
                'image' => 'assets/img/shop/shop-05.jpg',
                'short_description' => 'Precision monopolar and bipolar electrosurgical generator designed for minimal tissue carbonization and rapid hemostasis.',
                'description' => "With instant tissue impedance feedback, this unit dynamically modulates power output across micro-seconds, ensuring clean cut margins and effective coagulation without thermal spread.",
                'key_features' => json_encode([
                    '400 Watts Monopolar Output with Pure, Blend, and Spray modes',
                    'Bipolar Standard & Force Coagulation with Auto-Stop sensor',
                    'REM (Return Electrode Monitoring) contact security safety system',
                    '10 User-Customizable Specialty Programs memory',
                    'Waterproof Double-Foot Pedal with safety guard',
                ]),
                'order' => 5,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'company_id' => $companies['scitek-instruments']->id,
                'title' => 'Digital High-Resolution Mammography & Radiology Suite',
                'slug' => 'digital-high-resolution-mammography-suite',
                'sku' => 'SC-MAMMO-800',
                'image' => 'assets/img/shop/shop-06.jpg',
                'short_description' => 'Low-dose full-field digital mammography system with gentle motorized compression and dual focal spot clarity.',
                'description' => "Empowers diagnostic radiology centers with supreme spatial resolution for early-stage microcalcification detection. Ergonomic breast positioning gantry ensures patient relaxation throughout examinations.",
                'key_features' => json_encode([
                    'Amorphous Silicon Flat Panel Detector with 85µm pixel pitch',
                    'Automated Micro-Dose Exposure Control (AEC)',
                    'Smart Motorized Compression with soft-release technology',
                    'Dual High-Resolution 5MP Medical Diagnostic Review Screens',
                    'Full DICOM 3.0 PACS connectivity and worklist management',
                ]),
                'order' => 6,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'company_id' => $companies['meditech-global']->id,
                'title' => 'Critical Care Intensive ICU Mechanical Ventilator',
                'slug' => 'critical-care-intensive-icu-mechanical-ventilator',
                'sku' => 'MDT-VENT-ICU',
                'image' => 'assets/img/shop/shop-07.jpg',
                'short_description' => 'Adaptive lung-protective ICU ventilator accommodating adult, pediatric, and neonatal respiratory support.',
                'description' => "Engineered for complex respiratory failure cases with built-in high-performance ultra-quiet turbine technology, eliminating the requirement for external hospital wall compressed air lines.",
                'key_features' => json_encode([
                    '15-inch Touchscreen with configurable pressure/volume loops',
                    'Invasive (VCV, PCV, SIMV, PRVC) & Non-Invasive (NIV/CPAP) modes',
                    'Integrated High-Performance Silent Turbine drive',
                    'Lung Recruitment maneuvers and automated P/V Tool',
                    'Dual Oxygen Sensor with automated calibration',
                ]),
                'order' => 7,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'company_id' => $companies['scitek-instruments']->id,
                'title' => 'Digital Mobile Surgical C-Arm Fluoroscopy Imaging System',
                'slug' => 'digital-mobile-surgical-c-arm-fluoroscopy-system',
                'sku' => 'SC-CARM-900',
                'image' => 'assets/img/shop/shop-08.jpg',
                'short_description' => 'High-frequency mobile C-Arm fluoroscopy for orthopedic, vascular, and traumatology surgical theaters.',
                'description' => "Offers broad orbital rotation, generous free space, and low radiation scatter. Dual high-luminance anti-glare screens display live fluoroscopy and reference roadmaps simultaneously.",
                'key_features' => json_encode([
                    'High-Frequency 40kHz 5kW Rotating Anode X-ray generator',
                    '9-inch Triple-Field High DQE Image Intensifier',
                    'Pulsed Fluoroscopy mode reducing radiation exposure up to 70%',
                    'Laser Position Cross-Marker for accurate beam centering',
                    'Integrated Memory for 100,000+ DICOM frames with cine playback',
                ]),
                'order' => 8,
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($productsData as $pData) {
            Product::updateOrCreate(
                ['slug' => $pData['slug']],
                $pData
            );
        }

        // 3. UPDATE NAV MENUS: CHANGE 'SERVICES' TO 'PRODUCTS'
        $navService = NavMenu::where(function($q) {
            $q->where('url', '/services')
              ->orWhere('title', 'like', '%Service%');
        })->whereNull('parent_id')->first();

        if ($navService) {
            $navService->update([
                'title' => 'Products',
                'url' => '/products',
                'page_route' => 'products',
            ]);

            // Clear old static service children so that dynamic companies dropdown is rendered cleanly!
            NavMenu::where('parent_id', $navService->id)->delete();
        } else {
            NavMenu::create([
                'title' => 'Products',
                'url' => '/products',
                'page_route' => 'products',
                'order' => 3,
                'show_on_home' => true,
                'show_on_inner' => true,
                'is_active' => true,
            ]);
        }
    }
}

import mongoose from 'mongoose';

const ServiceSchema = new mongoose.Schema(
  {
    title: {
      type: String,
      required: [true, 'Service title is required'],
      trim: true,
    },
    slug: {
      type: String,
      required: true,
      unique: true,
      trim: true,
    },
    category: {
      type: String,
      default: 'Medical Equipment & Devices',
    },
    iconClass: {
      type: String,
      default: 'flaticon-hemoglobin-test-meter',
    },
    iconTheme: {
      type: String,
      default: 'blue', // blue, pink, green, sky
    },
    description: {
      type: String,
      required: true,
      default: '',
    },
    bannerImage: {
      type: String,
      default: '/assets/img/banner/breadcrumb-01.jpg',
    },
    bannerSubTitle: {
      type: String,
      default: 'Precision Medical Equipment & Healthcare Solutions',
    },
    image1: {
      type: String,
      default: '/assets/img/services/services-thumb-07.jpg',
    },
    image2: {
      type: String,
      default: '/assets/img/services/services-thumb-08.jpg',
    },
    showcaseBanner: {
      type: String,
      default: '/assets/img/services/services-thumb-09.jpg',
    },
    processTitle: {
      type: String,
      default: 'Technical Overview & Clinical Integration',
    },
    processText: {
      type: String,
      default:
        'Innotech Medical Pvt Ltd provides high-performance clinical technologies, precision diagnostic devices, and turnkey operating room equipment engineered to meet global healthcare benchmarks across Pakistan.',
    },
    processPoints: {
      type: [String],
      default: [
        'FDA, CE, and ISO certified medical equipment compliant with international standards.',
        'Seamless integration with hospital information systems and ICU workflows.',
        'Precision calibration, preventive maintenance, and genuine replacement components.',
        '24/7 dedicated biomedical engineering support and rapid technical dispatch.',
      ],
    },
    stepsTitle: {
      type: String,
      default: '4 Simple Deployment Steps',
    },
    stepsText: {
      type: String,
      default:
        'Our streamlined turnkey approach ensures minimal equipment downtime and rapid clinical handover from initial consultation to full hospital deployment.',
    },
    stepPoints1: {
      type: [String],
      default: ['Needs Assessment & Planning', 'Biomedical Specification Review', 'Regulatory Verification'],
    },
    stepPoints2: {
      type: [String],
      default: ['Turnkey Procurement', 'On-Site Mechanical & Electrical Setup', 'Precision Calibration'],
    },
    stepPoints3: {
      type: [String],
      default: ['Clinical Staff Training', '24/7 Emergency Support', 'Preventive Maintenance SLA'],
    },
    specsTable: [
      {
        feature: { type: String },
        spec: { type: String },
      },
    ],
    fullContent: {
      type: String,
      default: '',
    },
    faq: [
      {
        question: { type: String },
        answer: { type: String },
      },
    ],
    order: {
      type: Number,
      default: 0,
    },
    enabled: {
      type: Boolean,
      default: true,
    },
    showInHeader: {
      type: Boolean,
      default: true,
    },
  },
  { timestamps: true }
);

export default mongoose.models.Service || mongoose.model('Service', ServiceSchema);

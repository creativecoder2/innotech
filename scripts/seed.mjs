import mongoose from 'mongoose';
import * as dotenv from 'dotenv';
import { resolve } from 'path';

dotenv.config({ path: resolve(process.cwd(), '.env.local') });

const MONGODB_URI = process.env.MONGODB_URI || 'mongodb://127.0.0.1:27017/innotech_medical';

const ServiceSchema = new mongoose.Schema({
  title: String,
  slug: String,
  iconClass: String,
  iconTheme: String,
  description: String,
  order: Number,
  isActive: Boolean,
});

const BlogSchema = new mongoose.Schema({
  title: String,
  slug: String,
  category: String,
  dateDay: String,
  dateMonth: String,
  excerpt: String,
  image: String,
});

const TeamSchema = new mongoose.Schema({
  name: String,
  position: String,
  bio: String,
  image: String,
  socialLinks: Object,
});

const TestimonialSchema = new mongoose.Schema({
  name: String,
  position: String,
  review: String,
  avatar: String,
});

const Service = mongoose.models.Service || mongoose.model('Service', ServiceSchema);
const Blog = mongoose.models.Blog || mongoose.model('Blog', BlogSchema);
const Team = mongoose.models.Team || mongoose.model('Team', TeamSchema);
const Testimonial = mongoose.models.Testimonial || mongoose.model('Testimonial', TestimonialSchema);

const servicesData = [
  {
    title: 'HEMOGLOBIN TEST',
    slug: 'hemoglobin-test',
    iconClass: 'flaticon-hemoglobin-test-meter',
    iconTheme: 'blue',
    description: 'Nam eget dui vel quam sodales semper quis porttitor tortor. Vivamus quis ex nulla.Nam eget dui vel quam',
    order: 1,
    isActive: true,
  },
  {
    title: 'BLOOD TESTING',
    slug: 'blood-testing',
    iconClass: 'flaticon-blood-test',
    iconTheme: 'pink',
    description: 'Nam eget dui vel quam sodales semper quis porttitor tortor. Vivamus quis ex nulla.Nam eget dui vel quam',
    order: 2,
    isActive: true,
  },
  {
    title: 'BIOCHEMISTRY',
    slug: 'biochemistry',
    iconClass: 'flaticon-biochemistry',
    iconTheme: 'green',
    description: 'Nam eget dui vel quam sodales semper quis porttitor tortor. Vivamus quis ex nulla.Nam eget dui vel quam',
    order: 3,
    isActive: true,
  },
  {
    title: 'HISTOPATOLOGY',
    slug: 'histopatology',
    iconClass: 'flaticon-dna-1',
    iconTheme: 'sky',
    description: 'Nam eget dui vel quam sodales semper quis porttitor tortor. Vivamus quis ex nulla.Nam eget dui vel quam',
    order: 4,
    isActive: true,
  },
];

const teamData = [
  {
    name: 'Cameron Williamson',
    position: 'Genetic Specialist',
    bio: 'Providing insight-driven transformation to investment banks, wealth and asset mana, exchanges, Finance',
    image: '/assets/img/team/team-thumb-01.jpg',
    socialLinks: { youtube: '#', twitter: '#', facebook: '#', skype: '#' },
  },
  {
    name: 'Savannah Nguyen',
    position: 'Anaesthetist Specialist',
    bio: 'Providing insight-driven transformation to investment banks, wealth and asset mana, exchanges, Finance',
    image: '/assets/img/team/team-thumb-02.jpg',
    socialLinks: { youtube: '#', twitter: '#', facebook: '#', skype: '#' },
  },
  {
    name: 'Darlene Robertson',
    position: 'Gynaecologist Specialist',
    bio: 'Providing insight-driven transformation to investment banks, wealth and asset mana, exchanges, Finance',
    image: '/assets/img/team/team-thumb-03.jpg',
    socialLinks: { youtube: '#', twitter: '#', facebook: '#', skype: '#' },
  },
  {
    name: 'Jhon Methweu',
    position: 'Radiologist Specialist',
    bio: 'Providing insight-driven transformation to investment banks, wealth and asset mana, exchanges, Finance',
    image: '/assets/img/team/team-thumb-04.jpg',
    socialLinks: { youtube: '#', twitter: '#', facebook: '#', skype: '#' },
  },
];

const testimonialData = [
  {
    name: 'Darlene Robertson',
    position: 'Secretary of (FlaxStudio)',
    review: 'BIoxlab is another theme that is beautiful and professinally constructed by the Developers. The price for the template is checp but not qualityh of product.what a bargain , This theme works for many types of web sites and seems to be durble dows nt break and it.',
    avatar: '/assets/img/icon/testi-ava-01.jpg',
  },
  {
    name: 'Courtney Henry',
    position: 'CEO of (FlaxStudio)',
    review: 'BIoxlab is another theme that is beautiful and professinally constructed by the Developers. The price for the template is checp but not qualityh of product.what a bargain , This theme works for many types of web sites and seems to be durble dows nt break and it.',
    avatar: '/assets/img/icon/testi-ava-02.jpg',
  },
  {
    name: 'Kathryn Murphy',
    position: 'Manager of (FlaxStudio)',
    review: 'BIoxlab is another theme that is beautiful and professinally constructed by the Developers. The price for the template is checp but not qualityh of product.what a bargain , This theme works for many types of web sites and seems to be durble dows nt break and it.',
    avatar: '/assets/img/icon/testi-ava-03.jpg',
  },
  {
    name: 'Darlene Robertson',
    position: 'Programmer of (FlaxStudio)',
    review: 'BIoxlab is another theme that is beautiful and professinally constructed by the Developers. The price for the template is checp but not qualityh of product.what a bargain , This theme works for many types of web sites and seems to be durble dows nt break and it.',
    avatar: '/assets/img/icon/testi-ava-07.png',
  },
];

const blogData = [
  {
    title: 'Heart Diseases Tests Ordered By Doctors',
    slug: 'heart-diseases-tests-ordered-by-doctors',
    category: 'Medicine',
    dateDay: '26',
    dateMonth: 'Dec',
    excerpt: 'Nam eget dui vel quam sodales semper quis porttitor tortor. Vivamus quis ex nulla ...',
    image: '/assets/img/blog/blog-thumb-01.jpg',
  },
  {
    title: 'Heart Diseases Tests Ordered By Doctors',
    slug: 'heart-diseases-tests-ordered-by-doctors-2',
    category: 'Medicine',
    dateDay: '26',
    dateMonth: 'Dec',
    excerpt: 'Nam eget dui vel quam sodales semper quis porttitor tortor. Vivamus quis ex nulla ...',
    image: '/assets/img/blog/blog-thumb-02.jpg',
  },
  {
    title: 'Identifying bases of disease pathophysio',
    slug: 'identifying-bases-of-disease-pathophysio',
    category: 'Medicine',
    dateDay: '26',
    dateMonth: 'Dec',
    excerpt: 'Nam eget dui vel quam sodales semper quis porttitor tortor. Vivamus quis ex nulla ...',
    image: '/assets/img/blog/blog-thumb-03.jpg',
  },
  {
    title: 'Coronavirus global health emergency',
    slug: 'coronavirus-global-health-emergency',
    category: 'Medicine',
    dateDay: '26',
    dateMonth: 'Dec',
    excerpt: 'Nam eget dui vel quam sodales semper quis porttitor tortor. Vivamus quis ex nulla ...',
    image: '/assets/img/blog/blog-thumb-04.jpg',
  },
];

async function seed() {
  try {
    console.log('Connecting to MongoDB:', MONGODB_URI);
    await mongoose.connect(MONGODB_URI);
    console.log('Connected! Seeding data...');

    await Service.deleteMany({});
    await Service.insertMany(servicesData);
    console.log(`Inserted ${servicesData.length} Services.`);

    await Team.deleteMany({});
    await Team.insertMany(teamData);
    console.log(`Inserted ${teamData.length} Team Members.`);

    await Testimonial.deleteMany({});
    await Testimonial.insertMany(testimonialData);
    console.log(`Inserted ${testimonialData.length} Testimonials.`);

    await Blog.deleteMany({});
    await Blog.insertMany(blogData);
    console.log(`Inserted ${blogData.length} Blogs.`);

    console.log('Seeding completed successfully!');
    process.exit(0);
  } catch (err) {
    console.error('Seeding error:', err);
    process.exit(1);
  }
}

seed();

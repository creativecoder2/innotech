'use client';

import React, { useState, useEffect, useRef } from 'react';
import {
  Save,
  Plus,
  Trash2,
  Edit2,
  CheckCircle2,
  Sliders,
  Award,
  PhoneCall,
  Activity,
  Image as ImageIcon,
  CheckSquare,
  Users,
  MessageSquare,
  Building2,
  Megaphone,
  BookOpen,
  LayoutTemplate,
  ToggleLeft,
  ToggleRight,
  Eye,
  EyeOff,
  X,
  Sparkles,
  AlertTriangle,
  UploadCloud,
  Video,
  ChevronLeft,
  ChevronRight,
} from 'lucide-react';
import {
  fallbackSiteConfig,
  fallbackServices,
  fallbackGallery,
  fallbackTeam,
  fallbackTestimonials,
  fallbackBlogs,
  fallbackBrands,
} from '@/lib/data';

// Reusable Image & Video Upload Picker Component
function FileUploadField({
  label,
  value,
  onChange,
  accept = 'image/*',
  isVideo = false,
  placeholder = 'Select local file or enter path...',
}) {
  const [uploading, setUploading] = useState(false);
  const fileInputRef = useRef(null);

  const handleFileChange = async (e) => {
    const file = e.target.files?.[0];
    if (!file) return;

    setUploading(true);
    const formData = new FormData();
    formData.append('file', file);

    try {
      const res = await fetch('/api/upload', {
        method: 'POST',
        body: formData,
      });
      const data = await res.json();
      if (res.ok && data.url) {
        onChange(data.url);
      } else {
        alert(data.message || 'Upload failed');
      }
    } catch (err) {
      console.error(err);
      alert('Error uploading file');
    } finally {
      setUploading(false);
      if (fileInputRef.current) fileInputRef.current.value = '';
    }
  };

  return (
    <div>
      {label && (
        <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>
          {label}
        </label>
      )}
      <div style={{ display: 'flex', gap: '10px', alignItems: 'center', flexWrap: 'wrap' }}>
        {/* Preview Thumbnail */}
        {value && !isVideo && (
          <div
            style={{
              width: '46px',
              height: '46px',
              borderRadius: '8px',
              overflow: 'hidden',
              border: '1px solid #CBD5E1',
              backgroundColor: '#F8FAFC',
              flexShrink: 0,
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <img src={value} alt="Preview" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
          </div>
        )}

        {/* Text Input for direct path */}
        <input
          type="text"
          value={value || ''}
          onChange={(e) => onChange(e.target.value)}
          placeholder={placeholder}
          style={{
            flex: 1,
            minWidth: '180px',
            padding: '10px 14px',
            borderRadius: '8px',
            border: '1px solid #D1D6E0',
            fontSize: '13px',
            boxSizing: 'border-box',
          }}
        />

        {/* Hidden File Picker */}
        <input
          ref={fileInputRef}
          type="file"
          accept={accept}
          onChange={handleFileChange}
          style={{ display: 'none' }}
        />

        {/* Upload Trigger Button */}
        <button
          type="button"
          onClick={() => fileInputRef.current?.click()}
          disabled={uploading}
          style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '6px',
            padding: '10px 16px',
            backgroundColor: '#EFF6FF',
            color: '#0E63FF',
            border: '1px solid #BFDBFE',
            borderRadius: '8px',
            fontSize: '13px',
            fontWeight: '600',
            cursor: uploading ? 'not-allowed' : 'pointer',
            whiteSpace: 'nowrap',
            transition: 'all 0.2s',
          }}
        >
          {isVideo ? <Video size={16} /> : <UploadCloud size={16} />}
          <span>{uploading ? 'Uploading...' : isVideo ? 'Upload Video' : 'Upload Image'}</span>
        </button>
      </div>
    </div>
  );
}

export default function AdminHomePageManager() {
  const [activeTab, setActiveTab] = useState('hero');
  const [config, setConfig] = useState(fallbackSiteConfig);
  const [services, setServices] = useState(fallbackServices);
  const [gallery, setGallery] = useState(fallbackGallery);
  const [team, setTeam] = useState(fallbackTeam);
  const [testimonials, setTestimonials] = useState(fallbackTestimonials);
  const [blogs, setBlogs] = useState(fallbackBlogs);
  const [brands, setBrands] = useState(fallbackBrands);

  // Pagination states (set to 2-3 so pagination is immediately active and visible)
  const [servicesPage, setServicesPage] = useState(1);
  const servicesPageSize = 2;
  const [galleryPage, setGalleryPage] = useState(1);
  const galleryPageSize = 2;
  const [teamPage, setTeamPage] = useState(1);
  const teamPageSize = 2;
  const [testimonialsPage, setTestimonialsPage] = useState(1);
  const testimonialsPageSize = 2;
  const [brandsPage, setBrandsPage] = useState(1);
  const brandsPageSize = 3;
  const [blogsPage, setBlogsPage] = useState(1);
  const blogsPageSize = 2;

  // New Item form states
  const [newService, setNewService] = useState({
    title: '',
    description: '',
    iconClass: 'flaticon-hemoglobin-test-meter',
    iconTheme: 'blue',
    enabled: true,
  });

  const [newGalleryItem, setNewGalleryItem] = useState({
    title: '',
    tag: '',
    image: '/assets/img/gallery/gal-thum-01.jpg',
    link: '/services',
    enabled: true,
  });

  const [newTeamMember, setNewTeamMember] = useState({
    name: '',
    position: '',
    bio: '',
    image: '/assets/img/team/team-thumb-01.jpg',
    phone: '+92 331 6699992',
    email: 'info@innotecmedical.org',
    experience: '10+ Years in Clinical & Biomedical Engineering',
    biography: '',
    skills: '',
    socialLinks: {
      facebook: '',
      twitter: '',
      linkedin: '',
      instagram: '',
      youtube: '',
    },
    enabled: true,
  });

  const [newTestimonial, setNewTestimonial] = useState({
    name: '',
    position: '',
    review: '',
    avatar: '/assets/img/icon/testi-ava-01.jpg',
    enabled: true,
  });

  const [newBlog, setNewBlog] = useState({
    title: '',
    category: 'Biomedical Engineering',
    author: 'Innotech Editorial',
    slug: '',
    excerpt: '',
    content: '',
    dateDay: '26',
    dateMonth: 'Dec',
    dateYear: '2026',
    image: '/assets/img/blog/blog-thumb-01.jpg',
    socialLinks: {
      linkedin: '',
      twitter: '',
      facebook: '',
      instagram: '',
      youtube: '',
    },
    enabled: true,
  });

  const [newBrand, setNewBrand] = useState({
    image: '/assets/img/brand/brand-01.png',
    alt: 'Partner Brand',
    enabled: true,
  });

  // Universal Edit Modal State
  const [editModal, setEditModal] = useState({
    open: false,
    type: '',
    data: null,
  });

  // Custom Delete Confirmation Modal State
  const [deleteModal, setDeleteModal] = useState({
    open: false,
    type: '',
    id: null,
    title: '',
  });

  const [saving, setSaving] = useState(false);
  const [saveStatus, setSaveStatus] = useState({ type: '', msg: '' });
  const [gallerySaving, setGallerySaving] = useState(false);
  const [gallerySaved, setGallerySaved] = useState(false);
  const [teamBannerSaving, setTeamBannerSaving] = useState(false);
  const [teamBannerSaved, setTeamBannerSaved] = useState(false);

  useEffect(() => {
    fetchData();
  }, []);

  const showToast = (msg, type = 'success') => {
    setSaveStatus({ type, msg });
    setTimeout(() => {
      setSaveStatus((prev) => (prev.msg === msg ? { type: '', msg: '' } : prev));
    }, 4500);
  };

  const handleSaveGallerySettings = async () => {
    setGallerySaving(true);
    try {
      await syncPayload();
      setGallerySaved(true);
      showToast('✓ Work Gallery settings, headings & dedicated /gallery page banner saved successfully!');
      setTimeout(() => setGallerySaved(false), 3500);
    } catch (err) {
      showToast('Error saving gallery settings.', 'error');
    } finally {
      setGallerySaving(false);
    }
  };

  const handleSaveTeamBannerSettings = async () => {
    setTeamBannerSaving(true);
    try {
      await syncPayload();
      setTeamBannerSaved(true);
      showToast('✓ Team pages banner & heading settings saved successfully!');
      setTimeout(() => setTeamBannerSaved(false), 3500);
    } catch (err) {
      showToast('Error saving team banner settings.', 'error');
    } finally {
      setTeamBannerSaving(false);
    }
  };

  const generateSlug = (text) => {
    return (text || '')
      .toLowerCase()
      .trim()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-');
  };

  // Helper to keep /api/admin/blogs in 100% sync
  const syncBlogsToAdminBlogs = async (updatedBlogs) => {
    try {
      const res = await fetch('/api/admin/blogs');
      const json = await res.json();
      const currentBlogPage = json.data || fallbackBlogPage;

      await fetch('/api/admin/blogs', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          ...currentBlogPage,
          items: updatedBlogs,
        }),
      });
    } catch (err) {
      console.error('Error syncing blogs to admin blogs API:', err);
    }
  };

  const fetchData = async () => {
    try {
      const [configRes, servicesRes, blogsRes] = await Promise.all([
        fetch('/api/admin/site-config'),
        fetch('/api/admin/services'),
        fetch('/api/admin/blogs'),
      ]);

      const configData = await configRes.json();
      const servicesData = await servicesRes.json();
      const blogsData = await blogsRes.json();

      if (configData.data) {
        setConfig(configData.data);
        if (configData.data.gallerySection?.items?.length) {
          setGallery(configData.data.gallerySection.items);
        }
        if (configData.data.brandsSection?.items?.length) {
          setBrands(configData.data.brandsSection.items);
        }
      }
      if (servicesData.data && servicesData.data.length > 0) {
        setServices(servicesData.data);
      }
      if (blogsData.data?.items && blogsData.data.items.length > 0) {
        setBlogs(blogsData.data.items);
      } else if (Array.isArray(blogsData.data) && blogsData.data.length > 0) {
        setBlogs(blogsData.data);
      }
    } catch (e) {
      console.error('Error fetching admin data:', e);
    }
  };

  // Synchronize entire state to site-config API immediately
  const syncPayload = async (customConfig, customGallery, customBrands, customTeam, customTestimonials, customBlogs, customServices) => {
    const payload = {
      ...(customConfig || config),
      gallerySection: { ...(customConfig || config).gallerySection, items: customGallery || gallery },
      brandsSection: { ...(customConfig || config).brandsSection, items: customBrands || brands },
      teamSection: { ...(customConfig || config).teamSection, members: customTeam || team },
      testimonialSection: { ...(customConfig || config).testimonialSection, items: customTestimonials || testimonials },
      blogSection: { ...(customConfig || config).blogSection, items: customBlogs || blogs },
      services: customServices || services,
    };

    try {
      await fetch('/api/admin/site-config', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });
    } catch (err) {
      console.error('Auto sync error:', err);
    }
  };

  const handleConfigChange = (section, field, value) => {
    setConfig((prev) => {
      const updated = {
        ...prev,
        [section]: {
          ...prev[section],
          [field]: value,
        },
      };
      syncPayload(updated);
      return updated;
    });
  };

  const toggleSectionEnabled = (section) => {
    const newEnabled = config[section]?.enabled === false ? true : false;
    const updatedConfig = {
      ...config,
      [section]: {
        ...config[section],
        enabled: newEnabled,
      },
    };
    setConfig(updatedConfig);
    syncPayload(updatedConfig);
    showToast(`${section} is now ${newEnabled ? 'Enabled & Live' : 'Disabled & Hidden on Live Site'}!`);
  };

  const handlePointChange = (index, value) => {
    const updated = [...(config.about?.points || [])];
    updated[index] = value;
    const updatedConfig = { ...config, about: { ...config.about, points: updated } };
    setConfig(updatedConfig);
    syncPayload(updatedConfig);
  };

  const addAboutPoint = () => {
    const updated = [...(config.about?.points || []), 'New Quality Support Feature'];
    const updatedConfig = { ...config, about: { ...config.about, points: updated } };
    setConfig(updatedConfig);
    syncPayload(updatedConfig);
    showToast('New capability point added successfully!');
  };

  // Master Save Button
  const handleSaveAll = async (e) => {
    if (e) e.preventDefault();
    setSaving(true);

    const payload = {
      ...config,
      gallerySection: { ...config.gallerySection, items: gallery },
      brandsSection: { ...config.brandsSection, items: brands },
      teamSection: { ...config.teamSection, members: team },
      testimonialSection: { ...config.testimonialSection, items: testimonials },
      blogSection: { ...config.blogSection, items: blogs },
      services: services,
    };

    try {
      const res = await fetch('/api/admin/site-config', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      });
      const data = await res.json();
      if (res.ok) {
        showToast('All homepage data and sections successfully saved to database & live site!');
      } else {
        showToast(data.message || 'Error saving changes', 'error');
      }
    } catch (err) {
      showToast('Network error saving configuration.', 'error');
    } finally {
      setSaving(false);
    }
  };

  // ------------------ Service CRUD ------------------
  const handleAddService = async (e) => {
    e.preventDefault();
    if (!newService.title || !newService.description) return;
    try {
      const res = await fetch('/api/admin/services', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(newService),
      });
      const data = await res.json();
      const addedItem = data.data || { ...newService, _id: Date.now().toString() };
      const updatedServices = [...services, addedItem];
      setServices(updatedServices);
      syncPayload(undefined, undefined, undefined, undefined, undefined, undefined, updatedServices);
      setNewService({
        title: '',
        description: '',
        iconClass: 'flaticon-hemoglobin-test-meter',
        iconTheme: 'blue',
        enabled: true,
      });
      showToast(`Service "${addedItem.title}" added successfully!`);
    } catch (err) {
      console.error(err);
    }
  };

  const toggleServiceEnabled = async (id) => {
    const updatedServices = services.map((s) => {
      if (s._id === id) {
        const nextState = s.enabled === false ? true : false;
        return { ...s, enabled: nextState, isActive: nextState };
      }
      return s;
    });
    setServices(updatedServices);
    const target = updatedServices.find((s) => s._id === id);
    if (target) {
      try {
        await fetch('/api/admin/services', {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(target),
        });
      } catch (e) {}
    }
    syncPayload(undefined, undefined, undefined, undefined, undefined, undefined, updatedServices);
    showToast(`Service "${target?.title || ''}" is now ${target?.enabled ? 'Active on Live Site' : 'Disabled / Hidden'}!`);
  };

  // ------------------ Gallery CRUD ------------------
  const handleAddGalleryItem = (e) => {
    e.preventDefault();
    if (!newGalleryItem.title) return;
    const item = { ...newGalleryItem, id: Date.now() };
    const updatedGallery = [...gallery, item];
    setGallery(updatedGallery);
    syncPayload(undefined, updatedGallery);
    setNewGalleryItem({
      title: '',
      tag: '',
      image: '/assets/img/gallery/gal-thum-01.jpg',
      link: '/services',
      enabled: true,
    });
    showToast(`Gallery item "${item.title}" added successfully!`);
  };

  const toggleGalleryEnabled = (id) => {
    const updatedGallery = gallery.map((g) =>
      g.id === id ? { ...g, enabled: g.enabled === false ? true : false } : g
    );
    setGallery(updatedGallery);
    syncPayload(undefined, updatedGallery);
    const target = updatedGallery.find((g) => g.id === id);
    showToast(`Gallery item "${target?.title || ''}" is now ${target?.enabled ? 'Active on Live Site' : 'Disabled / Hidden'}!`);
  };

  // ------------------ Team CRUD ------------------
  const handleAddTeam = (e) => {
    e.preventDefault();
    if (!newTeamMember.name) return;
    const skillsArray =
      typeof newTeamMember.skills === 'string'
        ? newTeamMember.skills
            .split('\n')
            .map((s) => s.trim())
            .filter(Boolean)
        : newTeamMember.skills || [];

    const item = {
      ...newTeamMember,
      _id: Date.now().toString(),
      skills: skillsArray,
    };
    const updatedTeam = [...team, item];
    setTeam(updatedTeam);
    syncPayload(undefined, undefined, undefined, updatedTeam);
    setNewTeamMember({
      name: '',
      position: '',
      bio: '',
      image: '/assets/img/team/team-thumb-01.jpg',
      phone: '+92 331 6699992',
      email: 'info@innotecmedical.org',
      experience: '10+ Years in Clinical & Biomedical Engineering',
      biography: '',
      skills: '',
      socialLinks: {
        facebook: '',
        twitter: '',
        linkedin: '',
        instagram: '',
        youtube: '',
      },
      enabled: true,
    });
    showToast(`Specialist "${item.name}" added successfully!`);
  };

  const toggleTeamEnabled = (id) => {
    const updatedTeam = team.map((t) =>
      (t._id || t.name) === id ? { ...t, enabled: t.enabled === false ? true : false } : t
    );
    setTeam(updatedTeam);
    syncPayload(undefined, undefined, undefined, updatedTeam);
    const target = updatedTeam.find((t) => (t._id || t.name) === id);
    showToast(`Specialist "${target?.name || ''}" is now ${target?.enabled ? 'Active on Live Site' : 'Disabled / Hidden'}!`);
  };

  // ------------------ Testimonial CRUD ------------------
  const handleAddTestimonial = (e) => {
    e.preventDefault();
    if (!newTestimonial.name || !newTestimonial.review) return;
    const item = { ...newTestimonial, _id: Date.now().toString() };
    const updatedTestimonials = [...testimonials, item];
    setTestimonials(updatedTestimonials);
    syncPayload(undefined, undefined, undefined, undefined, updatedTestimonials);
    setNewTestimonial({
      name: '',
      position: '',
      review: '',
      avatar: '/assets/img/icon/testi-ava-01.jpg',
      enabled: true,
    });
    showToast(`Client testimonial from "${item.name}" added successfully!`);
  };

  const toggleTestimonialEnabled = (id) => {
    const updatedTestimonials = testimonials.map((t) =>
      (t._id || t.name) === id ? { ...t, enabled: t.enabled === false ? true : false } : t
    );
    setTestimonials(updatedTestimonials);
    syncPayload(undefined, undefined, undefined, undefined, updatedTestimonials);
    const target = updatedTestimonials.find((t) => (t._id || t.name) === id);
    showToast(`Review from "${target?.name || ''}" is now ${target?.enabled ? 'Active on Live Site' : 'Disabled / Hidden'}!`);
  };

  // ------------------ Brands CRUD ------------------
  const handleAddBrand = (e) => {
    e.preventDefault();
    const item = { ...newBrand, id: Date.now() };
    const updatedBrands = [...brands, item];
    setBrands(updatedBrands);
    syncPayload(undefined, undefined, updatedBrands);
    setNewBrand({ image: '/assets/img/brand/brand-01.png', alt: 'Partner Brand', enabled: true });
    showToast('Brand partner logo added successfully!');
  };

  const toggleBrandEnabled = (id) => {
    const updatedBrands = brands.map((b) =>
      b.id === id ? { ...b, enabled: b.enabled === false ? true : false } : b
    );
    setBrands(updatedBrands);
    syncPayload(undefined, undefined, updatedBrands);
    const target = updatedBrands.find((b) => b.id === id);
    showToast(`Brand logo is now ${target?.enabled ? 'Active on Live Site' : 'Disabled / Hidden'}!`);
  };

  // ------------------ Blog CRUD ------------------
  const handleAddBlog = (e) => {
    e.preventDefault();
    if (!newBlog.title || !newBlog.excerpt) return;
    const slug = newBlog.slug ? generateSlug(newBlog.slug) : generateSlug(newBlog.title);
    const item = {
      ...newBlog,
      _id: Date.now().toString(),
      slug: slug,
      author: newBlog.author || 'Innotech Editorial',
      dateDay: newBlog.dateDay || '26',
      dateMonth: newBlog.dateMonth || 'Dec',
      dateYear: newBlog.dateYear || '2026',
      tags: ['Biomedical', 'Engineering', 'Medical Equipment'],
      enabled: true,
    };
    const updatedBlogs = [item, ...blogs];
    setBlogs(updatedBlogs);
    syncPayload(undefined, undefined, undefined, undefined, undefined, updatedBlogs);
    syncBlogsToAdminBlogs(updatedBlogs);
    setNewBlog({
      title: '',
      category: 'Biomedical Engineering',
      author: 'Innotech Editorial',
      slug: '',
      excerpt: '',
      content: '',
      dateDay: '26',
      dateMonth: 'Dec',
      dateYear: '2026',
      image: '/assets/img/blog/blog-thumb-01.jpg',
      enabled: true,
    });
    showToast(`Article "${item.title}" added live across Blog & Home Page!`);
  };

  const toggleBlogEnabled = (id) => {
    const updatedBlogs = blogs.map((b) =>
      (b._id || b.slug || b.title) === id ? { ...b, enabled: b.enabled === false ? true : false } : b
    );
    setBlogs(updatedBlogs);
    syncPayload(undefined, undefined, undefined, undefined, undefined, updatedBlogs);
    syncBlogsToAdminBlogs(updatedBlogs);
    const target = updatedBlogs.find((b) => (b._id || b.slug || b.title) === id);
    showToast(`Article "${target?.title || ''}" is now ${target?.enabled ? 'Active on Live Site' : 'Disabled / Hidden'}!`);
  };

  // ------------------ Save Modal Changes with Success Message ------------------
  const handleSaveModalEdit = async (e) => {
    e.preventDefault();
    const { type, data } = editModal;
    if (!data) return;

    const itemName = data.title || data.name || data.alt || 'Item';

    if (type === 'service') {
      try {
        await fetch('/api/admin/services', {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data),
        });
      } catch (err) {
        console.error(err);
      }
      const updatedServices = services.map((s) => (s._id === data._id ? data : s));
      setServices(updatedServices);
      syncPayload(undefined, undefined, undefined, undefined, undefined, undefined, updatedServices);
    } else if (type === 'gallery') {
      const updatedGallery = gallery.map((g) => (g.id === data.id ? data : g));
      setGallery(updatedGallery);
      syncPayload(undefined, updatedGallery);
    } else if (type === 'team') {
      const skillsArray =
        typeof data.skills === 'string'
          ? data.skills
              .split('\n')
              .map((s) => s.trim())
              .filter(Boolean)
          : data.skills || [];
      const updatedMember = {
        ...data,
        skills: skillsArray,
      };
      const updatedTeam = team.map((t) =>
        (t._id || t.name) === (data._id || data.name) ? updatedMember : t
      );
      setTeam(updatedTeam);
      syncPayload(undefined, undefined, undefined, updatedTeam);
    } else if (type === 'testimonial') {
      const updatedTestimonials = testimonials.map((t) =>
        (t._id || t.name) === (data._id || data.name) ? data : t
      );
      setTestimonials(updatedTestimonials);
      syncPayload(undefined, undefined, undefined, undefined, updatedTestimonials);
    } else if (type === 'brand') {
      const updatedBrands = brands.map((b) => (b.id === data.id ? data : b));
      setBrands(updatedBrands);
      syncPayload(undefined, undefined, updatedBrands);
    } else if (type === 'blog') {
      const updatedBlog = {
        ...data,
        slug: data.slug ? generateSlug(data.slug) : generateSlug(data.title),
        author: data.author || 'Innotech Editorial',
        dateDay: data.dateDay || '26',
        dateMonth: data.dateMonth || 'Dec',
        dateYear: data.dateYear || '2026',
      };
      const updatedBlogs = blogs.map((b) =>
        (b._id || b.slug || b.title) === (data._id || data.slug || data.title) ? updatedBlog : b
      );
      setBlogs(updatedBlogs);
      syncPayload(undefined, undefined, undefined, undefined, undefined, updatedBlogs);
      syncBlogsToAdminBlogs(updatedBlogs);
    }

    setEditModal({ open: false, type: '', data: null });
    showToast(`Changes for "${itemName}" saved successfully!`);
  };

  // ------------------ Confirm Delete Execution Popup ------------------
  const handleConfirmDelete = async () => {
    const { type, id, title } = deleteModal;
    if (!id && id !== 0) return;

    if (type === 'service') {
      try {
        await fetch(`/api/admin/services?id=${id}`, { method: 'DELETE' });
      } catch (err) {}
      const updatedServices = services.filter((s) => s._id !== id);
      setServices(updatedServices);
      syncPayload(undefined, undefined, undefined, undefined, undefined, undefined, updatedServices);
    } else if (type === 'gallery') {
      const updatedGallery = gallery.filter((g) => g.id !== id);
      setGallery(updatedGallery);
      syncPayload(undefined, updatedGallery);
    } else if (type === 'team') {
      const updatedTeam = team.filter((t) => (t._id || t.name) !== id);
      setTeam(updatedTeam);
      syncPayload(undefined, undefined, undefined, updatedTeam);
    } else if (type === 'testimonial') {
      const updatedTestimonials = testimonials.filter((t) => (t._id || t.name) !== id);
      setTestimonials(updatedTestimonials);
      syncPayload(undefined, undefined, undefined, undefined, updatedTestimonials);
    } else if (type === 'brand') {
      const updatedBrands = brands.filter((b) => b.id !== id);
      setBrands(updatedBrands);
      syncPayload(undefined, undefined, updatedBrands);
    } else if (type === 'blog') {
      const updatedBlogs = blogs.filter((b) => (b._id || b.slug || b.title) !== id);
      setBlogs(updatedBlogs);
      syncPayload(undefined, undefined, undefined, undefined, undefined, updatedBlogs);
      syncBlogsToAdminBlogs(updatedBlogs);
    } else if (type === 'aboutPoint') {
      const updatedPoints = (config.about?.points || []).filter((_, i) => i !== id);
      const updatedConfig = { ...config, about: { ...config.about, points: updatedPoints } };
      setConfig(updatedConfig);
      syncPayload(updatedConfig);
    }

    setDeleteModal({ open: false, type: '', id: null, title: '' });
    showToast(`"${title || 'Item'}" deleted successfully!`);
  };

  const tabs = [
    { id: 'hero', label: '1. Hero Banner', icon: Sliders, sectionKey: 'hero' },
    { id: 'services', label: '2. Services Area', icon: Activity, sectionKey: 'servicesSection' },
    { id: 'about', label: '3. About Us', icon: Award, sectionKey: 'about' },
    { id: 'counters', label: '4. Counters', icon: CheckSquare, sectionKey: 'counters' },
    { id: 'gallery', label: '5. Work Gallery', icon: ImageIcon, sectionKey: 'gallerySection' },
    { id: 'why', label: '6. Why Choose Us', icon: LayoutTemplate, sectionKey: 'whyChooseUs' },
    { id: 'appointment', label: '7. Appointment Form', icon: PhoneCall, sectionKey: 'appointment' },
    { id: 'team', label: '8. Specialists Team', icon: Users, sectionKey: 'teamSection' },
    { id: 'testimonials', label: '9. Testimonials', icon: MessageSquare, sectionKey: 'testimonialSection' },
    { id: 'brands', label: '10. Brand Partners', icon: Building2, sectionKey: 'brandsSection' },
    { id: 'cta', label: '11. CTA Banner', icon: Megaphone, sectionKey: 'ctaSection' },
    { id: 'blog', label: '12. Blog & News', icon: BookOpen, sectionKey: 'blogSection' },
    { id: 'footer', label: '13. Footer & Contact', icon: LayoutTemplate, sectionKey: 'footer' },
  ];

  return (
    <div style={{ maxWidth: '1400px', margin: '0 auto', paddingBottom: '60px' }}>
      {/* Header Bar */}
      <div
        style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          flexWrap: 'wrap',
          gap: '15px',
          marginBottom: '20px',
          backgroundColor: '#ffffff',
          padding: '20px 24px',
          borderRadius: '12px',
          border: '1px solid #ECEEF3',
          boxShadow: '0 2px 8px rgba(0,0,0,0.04)',
        }}
      >
        <div>
          <h1 style={{ fontSize: '24px', fontWeight: '800', color: '#171151', margin: '0 0 6px', display: 'flex', alignItems: 'center', gap: '10px' }}>
            <span>Home Page Section Manager</span>
            <span style={{ fontSize: '12px', fontWeight: '600', backgroundColor: '#EFF6FF', color: '#0E63FF', padding: '4px 10px', borderRadius: '20px' }}>
              Upload Enabled & Live
            </span>
          </h1>
          <p style={{ fontSize: '14px', color: '#6b6b6b', margin: 0 }}>
            Manage, upload local images/videos, edit, and toggle every item on the live website.
          </p>
        </div>

        <button
          onClick={handleSaveAll}
          disabled={saving}
          style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '8px',
            padding: '12px 24px',
            backgroundColor: '#0b9748',
            color: '#ffffff',
            borderRadius: '8px',
            border: 'none',
            fontWeight: '600',
            fontSize: '14px',
            cursor: 'pointer',
            boxShadow: '0 4px 12px rgba(11, 151, 72, 0.25)',
            transition: 'all 0.2s',
          }}
        >
          <Save size={18} />
          <span>{saving ? 'Saving to Database...' : 'Save All Changes to MongoDB'}</span>
        </button>
      </div>

      {/* Prominent Toast Notification Banner */}
      {saveStatus.msg && (
        <div
          style={{
            padding: '14px 20px',
            marginBottom: '20px',
            borderRadius: '10px',
            display: 'flex',
            alignItems: 'center',
            gap: '12px',
            fontSize: '14px',
            fontWeight: '700',
            backgroundColor: saveStatus.type === 'error' ? '#FEEAF1' : '#E7FAF6',
            color: saveStatus.type === 'error' ? '#F72A75' : '#0b9748',
            border: `1.5px solid ${saveStatus.type === 'error' ? '#FDCAD9' : '#A3EAD8'}`,
            boxShadow: '0 4px 14px rgba(0,0,0,0.06)',
            animation: 'fadeIn 0.25s ease',
          }}
        >
          {saveStatus.type === 'error' ? <AlertTriangle size={20} /> : <CheckCircle2 size={20} />}
          <span>{saveStatus.msg}</span>
        </div>
      )}

      {/* Tabs Navigation */}
      <div
        style={{
          display: 'flex',
          gap: '8px',
          overflowX: 'auto',
          paddingBottom: '12px',
          marginBottom: '20px',
          borderBottom: '1px solid #ECEEF3',
        }}
      >
        {tabs.map((t) => {
          const Icon = t.icon;
          const isEnabled = config[t.sectionKey]?.enabled !== false;
          const isActive = activeTab === t.id;

          return (
            <button
              key={t.id}
              onClick={() => setActiveTab(t.id)}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '8px',
                padding: '10px 16px',
                borderRadius: '8px',
                border: 'none',
                fontSize: '13px',
                fontWeight: '600',
                cursor: 'pointer',
                whiteSpace: 'nowrap',
                transition: 'all 0.2s ease',
                backgroundColor: isActive ? '#0E63FF' : '#ffffff',
                color: isActive ? '#ffffff' : '#475569',
                boxShadow: isActive ? '0 4px 12px rgba(14, 99, 255, 0.2)' : 'none',
              }}
            >
              <Icon size={16} />
              <span>{t.label}</span>
              <span
                style={{
                  width: '8px',
                  height: '8px',
                  borderRadius: '50%',
                  backgroundColor: isEnabled ? (isActive ? '#ffffff' : '#0b9748') : '#F72A75',
                }}
              ></span>
            </button>
          );
        })}
      </div>

      {/* TAB 1: HERO BANNER */}
      {activeTab === 'hero' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px', paddingBottom: '16px', borderBottom: '1px solid #ECEEF3' }}>
            <div>
              <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: '0 0 4px' }}>
                Hero Banner Content & Video
              </h3>
              <p style={{ fontSize: '13px', color: '#6b6b6b', margin: 0 }}>Configure top landing screen texts, video player, and buttons</p>
            </div>
            <button
              onClick={() => toggleSectionEnabled('hero')}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '8px 16px',
                borderRadius: '20px',
                border: 'none',
                fontWeight: '600',
                fontSize: '13px',
                cursor: 'pointer',
                backgroundColor: config.hero?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: config.hero?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {config.hero?.enabled !== false ? <Eye size={16} /> : <EyeOff size={16} />}
              <span>{config.hero?.enabled !== false ? 'Section Visible' : 'Section Disabled'}</span>
            </button>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Hero Subtitle</label>
              <input
                type="text"
                value={config.hero?.subTitle || ''}
                onChange={(e) => handleConfigChange('hero', 'subTitle', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Hero Main Headline</label>
              <input
                type="text"
                value={config.hero?.title || ''}
                onChange={(e) => handleConfigChange('hero', 'title', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div style={{ gridColumn: '1 / -1' }}>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Hero Description</label>
              <textarea
                rows={3}
                value={config.hero?.description || ''}
                onChange={(e) => handleConfigChange('hero', 'description', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              ></textarea>
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Primary Button Text</label>
              <input
                type="text"
                value={config.hero?.btn1Text || ''}
                onChange={(e) => handleConfigChange('hero', 'btn1Text', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Primary Button Link</label>
              <input
                type="text"
                value={config.hero?.btn1Link || ''}
                onChange={(e) => handleConfigChange('hero', 'btn1Link', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>

            {/* Hero Banner Graphic / Illustration Image Upload */}
            <div style={{ gridColumn: '1 / -1', border: '1px solid #ECEEF3', padding: '18px', borderRadius: '10px', backgroundColor: '#F8FAFC' }}>
              <FileUploadField
                label="Hero Banner Image (Main Graphic / Illustration on Right Side)"
                value={config.hero?.image || config.hero?.bannerImage || '/assets/img/banner/banner-01.png'}
                onChange={(url) => {
                  handleConfigChange('hero', 'image', url);
                  handleConfigChange('hero', 'bannerImage', url);
                }}
                accept="image/*"
                placeholder="Enter image URL or click Upload Image (e.g. /assets/img/banner/banner-01.png)"
              />
              <span style={{ display: 'block', marginTop: '6px', fontSize: '12px', color: '#64748B' }}>
                💡 Tip: Upload high-resolution PNG or JPG image for the hero section right side illustration.
              </span>
            </div>

            {/* Video File / YouTube Picker for Hero Banner */}
            <div style={{ gridColumn: '1 / -1', border: '1px solid #ECEEF3', padding: '18px', borderRadius: '10px', backgroundColor: '#F8FAFC' }}>
              <FileUploadField
                label="Presentation Video (YouTube URL or Upload Local Video File)"
                value={config.hero?.videoUrl || ''}
                onChange={(url) => handleConfigChange('hero', 'videoUrl', url)}
                accept="video/*"
                isVideo={true}
                placeholder="Paste YouTube URL (e.g. https://www.youtube.com/watch?v=...) or click Upload Video"
              />
              <span style={{ display: 'block', marginTop: '6px', fontSize: '12px', color: '#64748B' }}>
                💡 Tip: You can paste a YouTube link OR upload an MP4/WEBM video file directly from your local computer.
              </span>
            </div>
          </div>
        </div>
      )}

      {/* TAB 2: SERVICES */}
      {activeTab === 'services' && (
        <div>
          {/* Section Header Controls */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '16px' }}>
              <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', margin: 0 }}>
                Services Area Header Settings
              </h3>
              <button
                onClick={() => toggleSectionEnabled('servicesSection')}
                style={{
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '6px',
                  padding: '6px 14px',
                  borderRadius: '20px',
                  border: 'none',
                  fontWeight: '600',
                  fontSize: '12px',
                  cursor: 'pointer',
                  backgroundColor: config.servicesSection?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                  color: config.servicesSection?.enabled !== false ? '#0b9748' : '#F72A75',
                }}
              >
                {config.servicesSection?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
                <span>{config.servicesSection?.enabled !== false ? 'Section Visible' : 'Section Disabled'}</span>
              </button>
            </div>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '16px' }}>
              <div>
                <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Section Subtitle</label>
                <input
                  type="text"
                  value={config.servicesSection?.subTitle || ''}
                  onChange={(e) => handleConfigChange('servicesSection', 'subTitle', e.target.value)}
                  style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Section Title</label>
                <input
                  type="text"
                  value={config.servicesSection?.title || ''}
                  onChange={(e) => handleConfigChange('servicesSection', 'title', e.target.value)}
                  style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Search Box Placeholder</label>
                <input
                  type="text"
                  value={config.servicesSection?.searchPlaceholder || ''}
                  onChange={(e) => handleConfigChange('servicesSection', 'searchPlaceholder', e.target.value)}
                  style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                />
              </div>
            </div>
          </div>

          {/* Add Service Form */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Plus size={18} color="#0E63FF" /> Add New Medical Service
            </h3>
            <form onSubmit={handleAddService}>
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '16px' }}>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Service Title</label>
                  <input
                    type="text"
                    placeholder="e.g. ULTRASOUND & DOPPLER"
                    value={newService.title}
                    onChange={(e) => setNewService({ ...newService, title: e.target.value })}
                    required
                    style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Color Theme</label>
                  <select
                    value={newService.iconTheme}
                    onChange={(e) => setNewService({ ...newService, iconTheme: e.target.value })}
                    style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                  >
                    <option value="blue">Blue Theme</option>
                    <option value="pink">Pink Theme</option>
                    <option value="green">Green Theme</option>
                    <option value="sky">Sky Blue Theme</option>
                  </select>
                </div>
                <div style={{ gridColumn: '1 / -1' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Description</label>
                  <textarea
                    rows={2}
                    placeholder="Describe this medical service..."
                    value={newService.description}
                    onChange={(e) => setNewService({ ...newService, description: e.target.value })}
                    required
                    style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                  ></textarea>
                </div>
              </div>
              <button
                type="submit"
                style={{ marginTop: '16px', display: 'inline-flex', alignItems: 'center', gap: '8px', padding: '10px 20px', backgroundColor: '#0E63FF', color: '#fff', borderRadius: '8px', border: 'none', fontWeight: '600', fontSize: '13px', cursor: 'pointer' }}
              >
                <Plus size={16} /> Add Service
              </button>
            </form>
          </div>

          {/* Services Table with Edit, Enable/Disable, Delete */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px' }}>
              Current Services ({services.length})
            </h3>
            <div style={{ overflowX: 'auto' }}>
              <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '13px' }}>
                <thead>
                  <tr style={{ color: '#8A879F', borderBottom: '1px solid #ECEEF3', textAlign: 'left' }}>
                    <th style={{ padding: '12px 10px' }}>Title</th>
                    <th style={{ padding: '12px 10px' }}>Description</th>
                    <th style={{ padding: '12px 10px' }}>Theme</th>
                    <th style={{ padding: '12px 10px' }}>Status</th>
                    <th style={{ padding: '12px 10px', textAlign: 'right' }}>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {services.slice((servicesPage - 1) * servicesPageSize, servicesPage * servicesPageSize).map((item) => (
                    <tr key={item._id} style={{ borderBottom: '1px solid #F2F5FA' }}>
                      <td style={{ padding: '12px 10px', fontWeight: '700', color: '#171151' }}>{item.title}</td>
                      <td style={{ padding: '12px 10px', color: '#6b6b6b', maxWidth: '320px' }}>{item.description}</td>
                      <td style={{ padding: '12px 10px', textTransform: 'capitalize' }}>
                        <span style={{ padding: '3px 8px', borderRadius: '4px', fontSize: '11px', fontWeight: '600', backgroundColor: item.iconTheme === 'pink' ? '#FEEAF1' : item.iconTheme === 'green' ? '#E7FAF6' : '#EFF6FF', color: item.iconTheme === 'pink' ? '#F72A75' : item.iconTheme === 'green' ? '#0b9748' : '#0E63FF' }}>
                          {item.iconTheme}
                        </span>
                      </td>
                      <td style={{ padding: '12px 10px' }}>
                        <button
                          type="button"
                          onClick={() => toggleServiceEnabled(item._id)}
                          style={{
                            display: 'inline-flex',
                            alignItems: 'center',
                            gap: '5px',
                            padding: '4px 10px',
                            borderRadius: '20px',
                            border: 'none',
                            fontSize: '11px',
                            fontWeight: '600',
                            cursor: 'pointer',
                            backgroundColor: item.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                            color: item.enabled !== false ? '#0b9748' : '#F72A75',
                          }}
                        >
                          {item.enabled !== false ? <Eye size={12} /> : <EyeOff size={12} />}
                          <span>{item.enabled !== false ? 'Active' : 'Disabled'}</span>
                        </button>
                      </td>
                      <td style={{ padding: '12px 10px', textAlign: 'right' }}>
                        <div style={{ display: 'flex', gap: '8px', justifyContent: 'flex-end' }}>
                          <button
                            type="button"
                            onClick={() => setEditModal({ open: true, type: 'service', data: { ...item } })}
                            title="Edit Service"
                            style={{ display: 'inline-flex', alignItems: 'center', gap: '4px', padding: '6px 12px', backgroundColor: '#EFF6FF', color: '#0E63FF', border: '1px solid #BFDBFE', borderRadius: '6px', fontSize: '12px', fontWeight: '600', cursor: 'pointer' }}
                          >
                            <Edit2 size={13} /> Edit
                          </button>
                          <button
                            type="button"
                            onClick={() => setDeleteModal({ open: true, type: 'service', id: item._id, title: item.title })}
                            title="Delete Service"
                            style={{ padding: '6px 10px', backgroundColor: '#feeaf1', color: '#F72A75', border: '1px solid #FECDD3', borderRadius: '6px', cursor: 'pointer' }}
                          >
                            <Trash2 size={14} />
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>

            {/* Services Pagination Bar */}
            {services.length > 0 && (
              <div
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'space-between',
                  paddingTop: '16px',
                  marginTop: '16px',
                  borderTop: '1px solid #F1F5F9',
                  flexWrap: 'wrap',
                  gap: '12px',
                }}
              >
                <span style={{ fontSize: '13px', color: '#64748B' }}>
                  Showing {(servicesPage - 1) * servicesPageSize + 1} to {Math.min(servicesPage * servicesPageSize, services.length)} of {services.length} services
                </span>
                <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                  <button
                    type="button"
                    disabled={servicesPage === 1}
                    onClick={() => setServicesPage((p) => Math.max(p - 1, 1))}
                    style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      border: '1px solid #D1D6E0',
                      backgroundColor: servicesPage === 1 ? '#F8FAFC' : '#ffffff',
                      color: servicesPage === 1 ? '#CBD5E1' : '#171151',
                      cursor: servicesPage === 1 ? 'not-allowed' : 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '4px',
                      fontSize: '12px',
                      fontWeight: '600',
                    }}
                  >
                    <ChevronLeft size={14} />
                    <span>Previous</span>
                  </button>
                  <span style={{ fontSize: '13px', fontWeight: '700', padding: '0 8px', color: '#171151' }}>
                    Page {servicesPage} of {Math.ceil(services.length / servicesPageSize) || 1}
                  </span>
                  <button
                    type="button"
                    disabled={servicesPage >= Math.ceil(services.length / servicesPageSize)}
                    onClick={() => setServicesPage((p) => Math.min(p + 1, Math.ceil(services.length / servicesPageSize)))}
                    style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      border: '1px solid #D1D6E0',
                      backgroundColor: servicesPage >= Math.ceil(services.length / servicesPageSize) ? '#F8FAFC' : '#ffffff',
                      color: servicesPage >= Math.ceil(services.length / servicesPageSize) ? '#CBD5E1' : '#171151',
                      cursor: servicesPage >= Math.ceil(services.length / servicesPageSize) ? 'not-allowed' : 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '4px',
                      fontSize: '12px',
                      fontWeight: '600',
                    }}
                  >
                    <span>Next</span>
                    <ChevronRight size={14} />
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      )}

      {/* TAB 3: ABOUT */}
      {activeTab === 'about' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              About Company & Feature Points
            </h3>
            <button
              onClick={() => toggleSectionEnabled('about')}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '6px 14px',
                borderRadius: '20px',
                border: 'none',
                fontWeight: '600',
                fontSize: '12px',
                cursor: 'pointer',
                backgroundColor: config.about?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: config.about?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {config.about?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{config.about?.enabled !== false ? 'Section Visible' : 'Section Disabled'}</span>
            </button>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Experience Years</label>
              <input
                type="text"
                value={config.about?.experienceYears || ''}
                onChange={(e) => handleConfigChange('about', 'experienceYears', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>About Section Title</label>
              <input
                type="text"
                value={config.about?.title || ''}
                onChange={(e) => handleConfigChange('about', 'title', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div style={{ gridColumn: '1 / -1' }}>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Tagline Quote</label>
              <input
                type="text"
                value={config.about?.tagline || ''}
                onChange={(e) => handleConfigChange('about', 'tagline', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div style={{ gridColumn: '1 / -1' }}>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Description</label>
              <textarea
                rows={3}
                value={config.about?.description || ''}
                onChange={(e) => handleConfigChange('about', 'description', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              ></textarea>
            </div>
            <div style={{ gridColumn: '1 / -1' }}>
              <FileUploadField
                label="About Section Showcase Image (Doctor / Scientist Image)"
                value={config.about?.imageMain || config.about?.image || '/assets/img/about/about-bg-01.png'}
                onChange={(val) => {
                  handleConfigChange('about', 'imageMain', val);
                  handleConfigChange('about', 'image', val);
                }}
                placeholder="Upload local image or enter image path..."
              />
            </div>
          </div>

          {/* Bullet Points */}
          <div style={{ marginTop: '24px', paddingTop: '20px', borderTop: '1px solid #ECEEF3' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '14px' }}>
              <h4 style={{ fontSize: '15px', fontWeight: '700', color: '#171151', margin: 0 }}>
                Checkmark Key Capabilities
              </h4>
              <button
                type="button"
                onClick={addAboutPoint}
                style={{ display: 'inline-flex', alignItems: 'center', gap: '4px', padding: '6px 12px', backgroundColor: '#EFF6FF', color: '#0E63FF', border: 'none', borderRadius: '6px', fontSize: '12px', fontWeight: '600', cursor: 'pointer' }}
              >
                <Plus size={14} /> Add Point
              </button>
            </div>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '12px' }}>
              {(config.about?.points || []).map((point, index) => (
                <div key={index} style={{ display: 'flex', gap: '8px', alignItems: 'center' }}>
                  <input
                    type="text"
                    value={point}
                    onChange={(e) => handlePointChange(index, e.target.value)}
                    style={{ flex: 1, padding: '8px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px' }}
                  />
                  <button
                    type="button"
                    onClick={() => setDeleteModal({ open: true, type: 'aboutPoint', id: index, title: point })}
                    style={{ padding: '8px', backgroundColor: '#FEEAF1', color: '#F72A75', border: 'none', borderRadius: '6px', cursor: 'pointer' }}
                  >
                    <Trash2 size={14} />
                  </button>
                </div>
              ))}
            </div>
          </div>
        </div>
      )}

      {/* TAB 4: COUNTERS */}
      {activeTab === 'counters' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Statistics Counter Numbers
            </h3>
            <button
              onClick={() => toggleSectionEnabled('counters')}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '6px 14px',
                borderRadius: '20px',
                border: 'none',
                fontWeight: '600',
                fontSize: '12px',
                cursor: 'pointer',
                backgroundColor: config.counters?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: config.counters?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {config.counters?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{config.counters?.enabled !== false ? 'Section Visible' : 'Section Disabled'}</span>
            </button>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '20px' }}>
            {[1, 2, 3, 4].map((num) => (
              <div key={num} style={{ border: '1px solid #ECEEF3', padding: '16px', borderRadius: '8px', backgroundColor: '#F9FAFC' }}>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                  Counter {num} Number
                </label>
                <input
                  type="text"
                  value={config.counters?.[`item${num}Number`] || ''}
                  onChange={(e) => handleConfigChange('counters', `item${num}Number`, e.target.value)}
                  style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '14px', marginBottom: '8px', boxSizing: 'border-box' }}
                />
                <label style={{ display: 'block', fontSize: '12px', color: '#64748B', marginBottom: '4px' }}>
                  Counter {num} Title Label
                </label>
                <input
                  type="text"
                  value={config.counters?.[`item${num}Title`] || ''}
                  onChange={(e) => handleConfigChange('counters', `item${num}Title`, e.target.value)}
                  style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                />
              </div>
            ))}
          </div>
        </div>
      )}

      {/* TAB 5: GALLERY */}
      {activeTab === 'gallery' && (
        <div>
          {/* 1. Work Gallery Section & Dedicated Page Settings */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px', flexWrap: 'wrap', gap: '10px' }}>
              <div>
                <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0, display: 'flex', alignItems: 'center', gap: '8px' }}>
                  <ImageIcon size={20} color="#0E63FF" /> Work Gallery & Gallery Page Controls
                </h3>
                <p style={{ margin: '4px 0 0 0', fontSize: '13px', color: '#64748B' }}>
                  Manage the homepage gallery section titles and the dedicated <strong>/gallery</strong> page banner, breadcrumb, and headings.
                </p>
              </div>
              <button
                onClick={() => toggleSectionEnabled('gallerySection')}
                style={{
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '6px',
                  padding: '6px 14px',
                  borderRadius: '20px',
                  border: 'none',
                  fontWeight: '600',
                  fontSize: '12px',
                  cursor: 'pointer',
                  backgroundColor: config.gallerySection?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                  color: config.gallerySection?.enabled !== false ? '#0b9748' : '#F72A75',
                }}
              >
                {config.gallerySection?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
                <span>{config.gallerySection?.enabled !== false ? 'Section Visible' : 'Section Disabled'}</span>
              </button>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px', marginBottom: '20px' }}>
              {/* Home Section Controls */}
              <div style={{ background: '#F8FAFC', borderRadius: '10px', padding: '18px', border: '1px solid #E2E8F0' }}>
                <h4 style={{ fontSize: '13px', fontWeight: '700', color: '#0E63FF', marginBottom: '14px', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                  🏠 Homepage Section Titles
                </h4>
                <div style={{ display: 'grid', gap: '12px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Section Sub-Title</label>
                    <input
                      type="text"
                      value={config.gallerySection?.subTitle || ''}
                      onChange={(e) => handleConfigChange('gallerySection', 'subTitle', e.target.value)}
                      placeholder="e.g. WORK GALLERY"
                      style={{ width: '100%', padding: '9px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Main Section Title</label>
                    <input
                      type="text"
                      value={config.gallerySection?.title || ''}
                      onChange={(e) => handleConfigChange('gallerySection', 'title', e.target.value)}
                      placeholder="e.g. INNOTECH Gallery"
                      style={{ width: '100%', padding: '9px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Explore Button Text</label>
                    <input
                      type="text"
                      value={config.gallerySection?.btnText || 'Explore More'}
                      onChange={(e) => handleConfigChange('gallerySection', 'btnText', e.target.value)}
                      style={{ width: '100%', padding: '9px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Explore Button Link Target</label>
                    <input
                      type="text"
                      value={config.gallerySection?.btnLink || '/gallery'}
                      onChange={(e) => handleConfigChange('gallerySection', 'btnLink', e.target.value)}
                      placeholder="/gallery"
                      style={{ width: '100%', padding: '9px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                </div>
              </div>

              {/* Dedicated Gallery Page Controls */}
              <div style={{ background: '#F8FAFC', borderRadius: '10px', padding: '18px', border: '1px solid #E2E8F0' }}>
                <h4 style={{ fontSize: '13px', fontWeight: '700', color: '#10B981', marginBottom: '14px', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                  🖼️ Dedicated Gallery Page (/gallery) Banner & Text
                </h4>
                <div style={{ display: 'grid', gap: '12px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Banner Title</label>
                    <input
                      type="text"
                      value={config.gallerySection?.bannerTitle || ''}
                      onChange={(e) => handleConfigChange('gallerySection', 'bannerTitle', e.target.value)}
                      placeholder="e.g. INNOTECH Gallery"
                      style={{ width: '100%', padding: '9px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Banner Subtitle / Description</label>
                    <input
                      type="text"
                      value={config.gallerySection?.bannerSubTitle || ''}
                      onChange={(e) => handleConfigChange('gallerySection', 'bannerSubTitle', e.target.value)}
                      placeholder="Explore our advanced medical equipment installations..."
                      style={{ width: '100%', padding: '9px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Breadcrumb Label</label>
                    <input
                      type="text"
                      value={config.gallerySection?.bannerBreadcrumb || 'Gallery'}
                      onChange={(e) => handleConfigChange('gallerySection', 'bannerBreadcrumb', e.target.value)}
                      placeholder="Gallery"
                      style={{ width: '100%', padding: '9px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Page Grid Section Title</label>
                    <input
                      type="text"
                      value={config.gallerySection?.pageSectionTitle || ''}
                      onChange={(e) => handleConfigChange('gallerySection', 'pageSectionTitle', e.target.value)}
                      placeholder="e.g. Precision Medical & Laboratory Works"
                      style={{ width: '100%', padding: '9px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <FileUploadField
                      label="Banner Background Image"
                      value={config.gallerySection?.bannerImage || ''}
                      onChange={(url) => handleConfigChange('gallerySection', 'bannerImage', url)}
                      placeholder="/assets/img/banner/breadcrumb-01.jpg"
                    />
                  </div>
                </div>
              </div>
            </div>

            {/* Save Button for Gallery Settings */}
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', paddingTop: '14px', borderTop: '1px solid #ECEEF3', flexWrap: 'wrap', gap: '10px' }}>
              {gallerySaved ? (
                <div style={{ display: 'inline-flex', alignItems: 'center', gap: '6px', color: '#0b9748', fontSize: '13px', fontWeight: '700', backgroundColor: '#E7FAF6', padding: '6px 14px', borderRadius: '6px' }}>
                  <CheckCircle2 size={16} /> Gallery & Page Settings Saved Successfully!
                </div>
              ) : (
                <span style={{ fontSize: '12px', color: '#64748B' }}>
                  💡 Changes will update both Homepage gallery titles and the /gallery page banner.
                </span>
              )}
              <button
                type="button"
                onClick={handleSaveGallerySettings}
                disabled={gallerySaving}
                style={{
                  backgroundColor: gallerySaved ? '#0b9748' : '#0E63FF',
                  color: '#ffffff',
                  border: 'none',
                  borderRadius: '8px',
                  padding: '11px 26px',
                  fontSize: '13px',
                  fontWeight: '700',
                  cursor: gallerySaving ? 'not-allowed' : 'pointer',
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '8px',
                  boxShadow: gallerySaved ? '0 4px 14px rgba(11, 151, 72, 0.28)' : '0 4px 14px rgba(14, 99, 255, 0.28)',
                  transition: 'all 0.25s ease',
                }}
              >
                {gallerySaved ? <CheckCircle2 size={16} /> : <Save size={16} />}
                <span>{gallerySaving ? 'Saving...' : gallerySaved ? '✓ Saved Successfully!' : 'Save Gallery & Page Settings'}</span>
              </button>
            </div>
          </div>


          {/* Add Gallery Item Form with Image Upload Picker */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Plus size={18} color="#0E63FF" /> Add New Gallery Photo
            </h3>

            <form onSubmit={handleAddGalleryItem}>
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '16px' }}>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Item Title</label>
                  <input
                    type="text"
                    placeholder="e.g. MRI SCANS & DIAGNOSIS"
                    value={newGalleryItem.title}
                    onChange={(e) => setNewGalleryItem({ ...newGalleryItem, title: e.target.value })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Category Tag</label>
                  <input
                    type="text"
                    placeholder="e.g. Radiologist"
                    value={newGalleryItem.tag}
                    onChange={(e) => setNewGalleryItem({ ...newGalleryItem, tag: e.target.value })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>
                <div style={{ gridColumn: '1 / -1' }}>
                  <FileUploadField
                    label="Gallery Image (Upload from Local System or Enter Path)"
                    value={newGalleryItem.image}
                    onChange={(url) => setNewGalleryItem({ ...newGalleryItem, image: url })}
                    placeholder="/assets/img/gallery/gal-thum-01.jpg"
                  />
                </div>
              </div>
              <button type="submit" style={{ marginTop: '16px', padding: '10px 18px', backgroundColor: '#0E63FF', color: '#fff', borderRadius: '6px', border: 'none', fontWeight: '600', cursor: 'pointer' }}>
                + Add to Gallery
              </button>
            </form>
          </div>

          {/* Gallery Table with Edit, Toggle, Delete */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px' }}>
              Gallery Photos ({gallery.length})
            </h3>
            <div style={{ overflowX: 'auto' }}>
              <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '13px' }}>
                <thead>
                  <tr style={{ color: '#8A879F', borderBottom: '1px solid #ECEEF3', textAlign: 'left' }}>
                    <th style={{ padding: '12px 10px', width: '80px' }}>Preview</th>
                    <th style={{ padding: '12px 10px' }}>Title</th>
                    <th style={{ padding: '12px 10px' }}>Tag</th>
                    <th style={{ padding: '12px 10px' }}>Status</th>
                    <th style={{ padding: '12px 10px', textAlign: 'right' }}>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {gallery.slice((galleryPage - 1) * galleryPageSize, galleryPage * galleryPageSize).map((item) => (
                    <tr key={item.id} style={{ borderBottom: '1px solid #F2F5FA' }}>
                      <td style={{ padding: '10px' }}>
                        <img src={item.image} alt={item.title} style={{ width: '60px', height: '45px', objectFit: 'cover', borderRadius: '4px' }} />
                      </td>
                      <td style={{ padding: '12px 10px', fontWeight: '700', color: '#171151' }}>{item.title}</td>
                      <td style={{ padding: '12px 10px', color: '#0E63FF', fontWeight: '600' }}>{item.tag}</td>
                      <td style={{ padding: '12px 10px' }}>
                        <button
                          type="button"
                          onClick={() => toggleGalleryEnabled(item.id)}
                          style={{
                            display: 'inline-flex',
                            alignItems: 'center',
                            gap: '5px',
                            padding: '4px 10px',
                            borderRadius: '20px',
                            border: 'none',
                            fontSize: '11px',
                            fontWeight: '600',
                            backgroundColor: item.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                            color: item.enabled !== false ? '#0b9748' : '#F72A75',
                            cursor: 'pointer',
                          }}
                        >
                          {item.enabled !== false ? <Eye size={12} /> : <EyeOff size={12} />}
                          <span>{item.enabled !== false ? 'Active' : 'Disabled'}</span>
                        </button>
                      </td>
                      <td style={{ padding: '12px 10px', textAlign: 'right' }}>
                        <div style={{ display: 'flex', gap: '8px', justifyContent: 'flex-end' }}>
                          <button
                            type="button"
                            onClick={() => setEditModal({ open: true, type: 'gallery', data: { ...item } })}
                            title="Edit Gallery Item"
                            style={{ display: 'inline-flex', alignItems: 'center', gap: '4px', padding: '6px 12px', backgroundColor: '#EFF6FF', color: '#0E63FF', border: '1px solid #BFDBFE', borderRadius: '6px', fontSize: '12px', fontWeight: '600', cursor: 'pointer' }}
                          >
                            <Edit2 size={13} /> Edit
                          </button>
                          <button
                            type="button"
                            onClick={() => setDeleteModal({ open: true, type: 'gallery', id: item.id, title: item.title })}
                            title="Delete Gallery Item"
                            style={{ padding: '6px 10px', backgroundColor: '#feeaf1', color: '#F72A75', border: '1px solid #FECDD3', borderRadius: '6px', cursor: 'pointer' }}
                          >
                            <Trash2 size={14} />
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>

            {/* Gallery Pagination Bar */}
            {gallery.length > 0 && (
              <div
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'space-between',
                  paddingTop: '16px',
                  marginTop: '16px',
                  borderTop: '1px solid #F1F5F9',
                  flexWrap: 'wrap',
                  gap: '12px',
                }}
              >
                <span style={{ fontSize: '13px', color: '#64748B' }}>
                  Showing {(galleryPage - 1) * galleryPageSize + 1} to {Math.min(galleryPage * galleryPageSize, gallery.length)} of {gallery.length} items
                </span>
                <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                  <button
                    type="button"
                    disabled={galleryPage === 1}
                    onClick={() => setGalleryPage((p) => Math.max(p - 1, 1))}
                    style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      border: '1px solid #D1D6E0',
                      backgroundColor: galleryPage === 1 ? '#F8FAFC' : '#ffffff',
                      color: galleryPage === 1 ? '#CBD5E1' : '#171151',
                      cursor: galleryPage === 1 ? 'not-allowed' : 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '4px',
                      fontSize: '12px',
                      fontWeight: '600',
                    }}
                  >
                    <ChevronLeft size={14} />
                    <span>Previous</span>
                  </button>
                  <span style={{ fontSize: '13px', fontWeight: '700', padding: '0 8px', color: '#171151' }}>
                    Page {galleryPage} of {Math.ceil(gallery.length / galleryPageSize) || 1}
                  </span>
                  <button
                    type="button"
                    disabled={galleryPage >= Math.ceil(gallery.length / galleryPageSize)}
                    onClick={() => setGalleryPage((p) => Math.min(p + 1, Math.ceil(gallery.length / galleryPageSize)))}
                    style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      border: '1px solid #D1D6E0',
                      backgroundColor: galleryPage >= Math.ceil(gallery.length / galleryPageSize) ? '#F8FAFC' : '#ffffff',
                      color: galleryPage >= Math.ceil(gallery.length / galleryPageSize) ? '#CBD5E1' : '#171151',
                      cursor: galleryPage >= Math.ceil(gallery.length / galleryPageSize) ? 'not-allowed' : 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '4px',
                      fontSize: '12px',
                      fontWeight: '600',
                    }}
                  >
                    <span>Next</span>
                    <ChevronRight size={14} />
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      )}

      {/* TAB 6: WHY CHOOSE US */}
      {activeTab === 'why' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Why Choose Us Section Settings
            </h3>
            <button
              onClick={() => toggleSectionEnabled('whyChooseUs')}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '6px 14px',
                borderRadius: '20px',
                border: 'none',
                fontWeight: '600',
                fontSize: '12px',
                cursor: 'pointer',
                backgroundColor: config.whyChooseUs?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: config.whyChooseUs?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {config.whyChooseUs?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{config.whyChooseUs?.enabled !== false ? 'Section Visible' : 'Section Disabled'}</span>
            </button>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Subtitle</label>
              <input
                type="text"
                value={config.whyChooseUs?.subTitle || ''}
                onChange={(e) => handleConfigChange('whyChooseUs', 'subTitle', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Title</label>
              <input
                type="text"
                value={config.whyChooseUs?.title || ''}
                onChange={(e) => handleConfigChange('whyChooseUs', 'title', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Bottom Bar Text</label>
              <input
                type="text"
                value={config.whyChooseUs?.bottomCtaText || ''}
                onChange={(e) => handleConfigChange('whyChooseUs', 'bottomCtaText', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
          </div>
        </div>
      )}

      {/* TAB 7: APPOINTMENT */}
      {activeTab === 'appointment' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Appointment & Inquiry Form Settings
            </h3>
            <button
              onClick={() => toggleSectionEnabled('appointment')}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '6px 14px',
                borderRadius: '20px',
                border: 'none',
                fontWeight: '600',
                fontSize: '12px',
                cursor: 'pointer',
                backgroundColor: config.appointment?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: config.appointment?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {config.appointment?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{config.appointment?.enabled !== false ? 'Section Visible' : 'Section Disabled'}</span>
            </button>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Form Heading Title</label>
              <input
                type="text"
                value={config.appointment?.title || ''}
                onChange={(e) => handleConfigChange('appointment', 'title', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>24/7 Helpline Phone</label>
              <input
                type="text"
                value={config.appointment?.emergencyPhone || ''}
                onChange={(e) => handleConfigChange('appointment', 'emergencyPhone', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
          </div>
        </div>
      )}

      {/* TAB 8: TEAM */}
      {activeTab === 'team' && (
        <div>
          {/* ── Team Pages Banner Settings ── */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '20px', display: 'flex', alignItems: 'center', gap: '8px' }}>
              <span style={{ fontSize: '18px' }}>🖼️</span> Team Pages — Banner & Heading Settings
            </h3>

            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '24px' }}>

              {/* All Teams Page */}
              <div style={{ background: '#F8FAFC', borderRadius: '10px', padding: '20px', border: '1px solid #E2E8F0' }}>
                <h4 style={{ fontSize: '14px', fontWeight: '700', color: '#0E63FF', marginBottom: '16px', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                  📋 All Specialists Page (/allteams)
                </h4>
                <div style={{ display: 'grid', gap: '12px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Banner Title</label>
                    <input
                      type="text"
                      value={config.teamPages?.allTeams?.bannerTitle || 'Meet Specialist'}
                      onChange={(e) => setConfig({ ...config, teamPages: { ...config.teamPages, allTeams: { ...config.teamPages?.allTeams, bannerTitle: e.target.value } } })}
                      style={{ width: '100%', padding: '9px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Breadcrumb Text</label>
                    <input
                      type="text"
                      value={config.teamPages?.allTeams?.bannerBreadcrumb || 'Our Team'}
                      onChange={(e) => setConfig({ ...config, teamPages: { ...config.teamPages, allTeams: { ...config.teamPages?.allTeams, bannerBreadcrumb: e.target.value } } })}
                      style={{ width: '100%', padding: '9px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Section Sub-Title</label>
                    <input
                      type="text"
                      value={config.teamPages?.allTeams?.sectionSubTitle || 'OUR TEAM'}
                      onChange={(e) => setConfig({ ...config, teamPages: { ...config.teamPages, allTeams: { ...config.teamPages?.allTeams, sectionSubTitle: e.target.value } } })}
                      style={{ width: '100%', padding: '9px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Section Title</label>
                    <input
                      type="text"
                      value={config.teamPages?.allTeams?.sectionTitle || 'Our Specialist'}
                      onChange={(e) => setConfig({ ...config, teamPages: { ...config.teamPages, allTeams: { ...config.teamPages?.allTeams, sectionTitle: e.target.value } } })}
                      style={{ width: '100%', padding: '9px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <FileUploadField
                      label="Banner Background Image"
                      accept="image/*"
                      value={config.teamPages?.allTeams?.bannerImage || ''}
                      onChange={(url) => setConfig({ ...config, teamPages: { ...config.teamPages, allTeams: { ...config.teamPages?.allTeams, bannerImage: url } } })}
                    />
                  </div>
                </div>
              </div>

              {/* Team Details Page */}
              <div style={{ background: '#F8FAFC', borderRadius: '10px', padding: '20px', border: '1px solid #E2E8F0' }}>
                <h4 style={{ fontSize: '14px', fontWeight: '700', color: '#10B981', marginBottom: '16px', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                  👤 Specialist Detail Page (/team/[id])
                </h4>
                <div style={{ display: 'grid', gap: '12px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Banner Title</label>
                    <input
                      type="text"
                      value={config.teamPages?.teamDetails?.bannerTitle || 'Team Details'}
                      onChange={(e) => setConfig({ ...config, teamPages: { ...config.teamPages, teamDetails: { ...config.teamPages?.teamDetails, bannerTitle: e.target.value } } })}
                      style={{ width: '100%', padding: '9px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Breadcrumb Text</label>
                    <input
                      type="text"
                      value={config.teamPages?.teamDetails?.bannerBreadcrumb || 'Our Team'}
                      onChange={(e) => setConfig({ ...config, teamPages: { ...config.teamPages, teamDetails: { ...config.teamPages?.teamDetails, bannerBreadcrumb: e.target.value } } })}
                      style={{ width: '100%', padding: '9px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <FileUploadField
                      label="Banner Background Image"
                      accept="image/*"
                      value={config.teamPages?.teamDetails?.bannerImage || ''}
                      onChange={(url) => setConfig({ ...config, teamPages: { ...config.teamPages, teamDetails: { ...config.teamPages?.teamDetails, bannerImage: url } } })}
                    />
                  </div>
                </div>
              </div>
            </div>

            {/* Save button */}
            <div style={{ marginTop: '16px', display: 'flex', justifyContent: 'space-between', alignItems: 'center', paddingTop: '14px', borderTop: '1px solid #ECEEF3', flexWrap: 'wrap', gap: '10px' }}>
              {teamBannerSaved ? (
                <div style={{ display: 'inline-flex', alignItems: 'center', gap: '6px', color: '#0b9748', fontSize: '13px', fontWeight: '700', backgroundColor: '#E7FAF6', padding: '6px 14px', borderRadius: '6px' }}>
                  <CheckCircle2 size={16} /> Team Pages Banner Settings Saved Successfully!
                </div>
              ) : (
                <span style={{ fontSize: '12px', color: '#64748B' }}>
                  💡 Changes will update /allteams and /team/[id] page banners & headings.
                </span>
              )}
              <button
                type="button"
                onClick={handleSaveTeamBannerSettings}
                disabled={teamBannerSaving}
                style={{
                  backgroundColor: teamBannerSaved ? '#0b9748' : '#0E63FF',
                  color: '#ffffff',
                  border: 'none',
                  borderRadius: '8px',
                  padding: '10px 24px',
                  fontSize: '13px',
                  fontWeight: '700',
                  cursor: teamBannerSaving ? 'not-allowed' : 'pointer',
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '8px',
                  boxShadow: teamBannerSaved ? '0 4px 14px rgba(11, 151, 72, 0.28)' : '0 4px 14px rgba(14, 99, 255, 0.28)',
                  transition: 'all 0.25s ease',
                }}
              >
                {teamBannerSaved ? <CheckCircle2 size={16} /> : <Save size={16} />}
                <span>{teamBannerSaving ? 'Saving...' : teamBannerSaved ? '✓ Saved Successfully!' : '💾 Save Banner Settings'}</span>
              </button>
            </div>
          </div>


          {/* Add Team Specialist with Photo Upload */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Plus size={18} color="#0E63FF" /> Add Specialist / Medical Engineer
            </h3>
            <form onSubmit={handleAddTeam}>

              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '16px' }}>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Doctor / Specialist Name</label>
                  <input
                    type="text"
                    placeholder="e.g. Dr. Ahmed Hassan"
                    value={newTeamMember.name}
                    onChange={(e) => setNewTeamMember({ ...newTeamMember, name: e.target.value })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Role / Specialization</label>
                  <input
                    type="text"
                    placeholder="e.g. Lead Biomedical Engineer"
                    value={newTeamMember.position}
                    onChange={(e) => setNewTeamMember({ ...newTeamMember, position: e.target.value })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Phone / Direct Line</label>
                  <input
                    type="text"
                    placeholder="e.g. +92 331 6699992"
                    value={newTeamMember.phone}
                    onChange={(e) => setNewTeamMember({ ...newTeamMember, phone: e.target.value })}
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Official Email</label>
                  <input
                    type="email"
                    placeholder="e.g. specialist@innotecmedical.org"
                    value={newTeamMember.email}
                    onChange={(e) => setNewTeamMember({ ...newTeamMember, email: e.target.value })}
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Clinical Experience</label>
                  <input
                    type="text"
                    placeholder="e.g. 12+ Years in Biomedical Diagnostics"
                    value={newTeamMember.experience}
                    onChange={(e) => setNewTeamMember({ ...newTeamMember, experience: e.target.value })}
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>
                <div style={{ gridColumn: '1 / -1' }}>
                  <FileUploadField
                    label="Specialist Photo (Upload Local Image or Enter Path)"
                    value={newTeamMember.image}
                    onChange={(url) => setNewTeamMember({ ...newTeamMember, image: url })}
                    placeholder="/assets/img/team/team-thumb-01.jpg"
                  />
                </div>
                <div style={{ gridColumn: '1 / -1' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Short Summary Bio (Visible on Cards)</label>
                  <input
                    type="text"
                    placeholder="Brief highlights visible on Meet Specialist slider..."
                    value={newTeamMember.bio}
                    onChange={(e) => setNewTeamMember({ ...newTeamMember, bio: e.target.value })}
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>
                <div style={{ gridColumn: '1 / -1' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Full Professional Biography (Team Details Page)</label>
                  <textarea
                    rows={3}
                    placeholder="Detailed professional experience, certifications, and clinical expertise..."
                    value={newTeamMember.biography}
                    onChange={(e) => setNewTeamMember({ ...newTeamMember, biography: e.target.value })}
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  ></textarea>
                </div>
                <div style={{ gridColumn: '1 / -1' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>
                    Core Competencies Checklist (Team Details Page - One capability per line)
                  </label>
                  <textarea
                    rows={3}
                    placeholder={`Biomedical Device Calibration & Safety Testing\nTurnkey Operating Room & ICU Integration\nComprehensive Healthcare Provider Clinical Training\n24/7 Rapid Emergency Technical Support & Diagnostics`}
                    value={newTeamMember.skills}
                    onChange={(e) => setNewTeamMember({ ...newTeamMember, skills: e.target.value })}
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  ></textarea>
                </div>

                {/* Social Media Links for Specialist */}
                <div style={{ gridColumn: '1 / -1', marginTop: '6px', padding: '14px', backgroundColor: '#F8FAFC', borderRadius: '8px', border: '1px solid #E2E8F0' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '10px' }}>
                    🔗 Specialist Social Media Links (Optional)
                  </label>
                  <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '10px' }}>
                    <div>
                      <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#1877F2', marginBottom: '3px' }}>Facebook URL</span>
                      <input
                        type="url"
                        placeholder="https://facebook.com/..."
                        value={newTeamMember.socialLinks?.facebook || ''}
                        onChange={(e) => setNewTeamMember({ ...newTeamMember, socialLinks: { ...newTeamMember.socialLinks, facebook: e.target.value } })}
                        style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                    <div>
                      <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#1DA1F2', marginBottom: '3px' }}>Twitter / X URL</span>
                      <input
                        type="url"
                        placeholder="https://twitter.com/..."
                        value={newTeamMember.socialLinks?.twitter || ''}
                        onChange={(e) => setNewTeamMember({ ...newTeamMember, socialLinks: { ...newTeamMember.socialLinks, twitter: e.target.value } })}
                        style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                    <div>
                      <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#0A66C2', marginBottom: '3px' }}>LinkedIn URL</span>
                      <input
                        type="url"
                        placeholder="https://linkedin.com/in/..."
                        value={newTeamMember.socialLinks?.linkedin || ''}
                        onChange={(e) => setNewTeamMember({ ...newTeamMember, socialLinks: { ...newTeamMember.socialLinks, linkedin: e.target.value } })}
                        style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                    <div>
                      <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#E4405F', marginBottom: '3px' }}>Instagram URL</span>
                      <input
                        type="url"
                        placeholder="https://instagram.com/..."
                        value={newTeamMember.socialLinks?.instagram || ''}
                        onChange={(e) => setNewTeamMember({ ...newTeamMember, socialLinks: { ...newTeamMember.socialLinks, instagram: e.target.value } })}
                        style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                    <div>
                      <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#FF0000', marginBottom: '3px' }}>YouTube URL</span>
                      <input
                        type="url"
                        placeholder="https://youtube.com/..."
                        value={newTeamMember.socialLinks?.youtube || ''}
                        onChange={(e) => setNewTeamMember({ ...newTeamMember, socialLinks: { ...newTeamMember.socialLinks, youtube: e.target.value } })}
                        style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                  </div>
                </div>
              </div>
              <button type="submit" style={{ marginTop: '16px', padding: '10px 18px', backgroundColor: '#0E63FF', color: '#fff', borderRadius: '6px', border: 'none', fontWeight: '600', cursor: 'pointer' }}>
                + Add Specialist
              </button>
            </form>
          </div>

          {/* Team Table with Edit, Toggle, Delete */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px' }}>
              Specialists Team ({team.length})
            </h3>
            <div style={{ overflowX: 'auto' }}>
              <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '13px' }}>
                <thead>
                  <tr style={{ color: '#8A879F', borderBottom: '1px solid #ECEEF3', textAlign: 'left' }}>
                    <th style={{ padding: '12px 10px', width: '70px' }}>Photo</th>
                    <th style={{ padding: '12px 10px' }}>Name</th>
                    <th style={{ padding: '12px 10px' }}>Role</th>
                    <th style={{ padding: '12px 10px' }}>Bio</th>
                    <th style={{ padding: '12px 10px' }}>Status</th>
                    <th style={{ padding: '12px 10px', textAlign: 'right' }}>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {team.slice((teamPage - 1) * teamPageSize, teamPage * teamPageSize).map((m) => (
                    <tr key={m._id || m.name} style={{ borderBottom: '1px solid #F2F5FA' }}>
                      <td style={{ padding: '10px' }}>
                        <img src={m.image} alt={m.name} style={{ width: '45px', height: '45px', objectFit: 'cover', borderRadius: '50%' }} />
                      </td>
                      <td style={{ padding: '12px 10px', fontWeight: '700', color: '#171151' }}>{m.name}</td>
                      <td style={{ padding: '12px 10px', color: '#0E63FF', fontWeight: '600' }}>{m.position}</td>
                      <td style={{ padding: '12px 10px', color: '#6b6b6b', maxWidth: '280px' }}>{m.bio}</td>
                      <td style={{ padding: '12px 10px' }}>
                        <button
                          type="button"
                          onClick={() => toggleTeamEnabled(m._id || m.name)}
                          style={{
                            display: 'inline-flex',
                            alignItems: 'center',
                            gap: '5px',
                            padding: '4px 10px',
                            borderRadius: '20px',
                            border: 'none',
                            fontSize: '11px',
                            fontWeight: '600',
                            backgroundColor: m.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                            color: m.enabled !== false ? '#0b9748' : '#F72A75',
                            cursor: 'pointer',
                          }}
                        >
                          {m.enabled !== false ? <Eye size={12} /> : <EyeOff size={12} />}
                          <span>{m.enabled !== false ? 'Active' : 'Disabled'}</span>
                        </button>
                      </td>
                      <td style={{ padding: '12px 10px', textAlign: 'right' }}>
                        <div style={{ display: 'flex', gap: '8px', justifyContent: 'flex-end' }}>
                          <button
                            type="button"
                            onClick={() => setEditModal({ open: true, type: 'team', data: { ...m } })}
                            title="Edit Specialist"
                            style={{ display: 'inline-flex', alignItems: 'center', gap: '4px', padding: '6px 12px', backgroundColor: '#EFF6FF', color: '#0E63FF', border: '1px solid #BFDBFE', borderRadius: '6px', fontSize: '12px', fontWeight: '600', cursor: 'pointer' }}
                          >
                            <Edit2 size={13} /> Edit
                          </button>
                          <button
                            type="button"
                            onClick={() => setDeleteModal({ open: true, type: 'team', id: m._id || m.name, title: m.name })}
                            title="Delete Specialist"
                            style={{ padding: '6px 10px', backgroundColor: '#feeaf1', color: '#F72A75', border: '1px solid #FECDD3', borderRadius: '6px', cursor: 'pointer' }}
                          >
                            <Trash2 size={14} />
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>

            {/* Team Pagination Bar */}
            {team.length > 0 && (
              <div
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'space-between',
                  paddingTop: '16px',
                  marginTop: '16px',
                  borderTop: '1px solid #F1F5F9',
                  flexWrap: 'wrap',
                  gap: '12px',
                }}
              >
                <span style={{ fontSize: '13px', color: '#64748B' }}>
                  Showing {(teamPage - 1) * teamPageSize + 1} to {Math.min(teamPage * teamPageSize, team.length)} of {team.length} specialists
                </span>
                <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                  <button
                    type="button"
                    disabled={teamPage === 1}
                    onClick={() => setTeamPage((p) => Math.max(p - 1, 1))}
                    style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      border: '1px solid #D1D6E0',
                      backgroundColor: teamPage === 1 ? '#F8FAFC' : '#ffffff',
                      color: teamPage === 1 ? '#CBD5E1' : '#171151',
                      cursor: teamPage === 1 ? 'not-allowed' : 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '4px',
                      fontSize: '12px',
                      fontWeight: '600',
                    }}
                  >
                    <ChevronLeft size={14} />
                    <span>Previous</span>
                  </button>
                  <span style={{ fontSize: '13px', fontWeight: '700', padding: '0 8px', color: '#171151' }}>
                    Page {teamPage} of {Math.ceil(team.length / teamPageSize) || 1}
                  </span>
                  <button
                    type="button"
                    disabled={teamPage >= Math.ceil(team.length / teamPageSize)}
                    onClick={() => setTeamPage((p) => Math.min(p + 1, Math.ceil(team.length / teamPageSize)))}
                    style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      border: '1px solid #D1D6E0',
                      backgroundColor: teamPage >= Math.ceil(team.length / teamPageSize) ? '#F8FAFC' : '#ffffff',
                      color: teamPage >= Math.ceil(team.length / teamPageSize) ? '#CBD5E1' : '#171151',
                      cursor: teamPage >= Math.ceil(team.length / teamPageSize) ? 'not-allowed' : 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '4px',
                      fontSize: '12px',
                      fontWeight: '600',
                    }}
                  >
                    <span>Next</span>
                    <ChevronRight size={14} />
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      )}

      {/* TAB 9: TESTIMONIALS */}
      {activeTab === 'testimonials' && (
        <div>
          {/* Add Testimonial with Avatar Upload */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Plus size={18} color="#0E63FF" /> Add Client Feedback / Review
            </h3>
            <form onSubmit={handleAddTestimonial}>
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '16px' }}>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Client Name</label>
                  <input
                    type="text"
                    placeholder="e.g. Dr. Salman Qureshi"
                    value={newTestimonial.name}
                    onChange={(e) => setNewTestimonial({ ...newTestimonial, name: e.target.value })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Designation / Hospital</label>
                  <input
                    type="text"
                    placeholder="e.g. Head of Surgery (Civil Hospital)"
                    value={newTestimonial.position}
                    onChange={(e) => setNewTestimonial({ ...newTestimonial, position: e.target.value })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>
                <div style={{ gridColumn: '1 / -1' }}>
                  <FileUploadField
                    label="Client Avatar Image (Upload Local Photo or Enter Path)"
                    value={newTestimonial.avatar}
                    onChange={(url) => setNewTestimonial({ ...newTestimonial, avatar: url })}
                    placeholder="/assets/img/icon/testi-ava-01.jpg"
                  />
                </div>
                <div style={{ gridColumn: '1 / -1' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Review Quote</label>
                  <textarea
                    rows={3}
                    placeholder="Write client testimonial quote..."
                    value={newTestimonial.review}
                    onChange={(e) => setNewTestimonial({ ...newTestimonial, review: e.target.value })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  ></textarea>
                </div>
              </div>
              <button type="submit" style={{ marginTop: '16px', padding: '10px 18px', backgroundColor: '#0E63FF', color: '#fff', borderRadius: '6px', border: 'none', fontWeight: '600', cursor: 'pointer' }}>
                + Add Review
              </button>
            </form>
          </div>

          {/* Testimonials Table with Edit, Toggle, Delete */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px' }}>
              Customer Reviews ({testimonials.length})
            </h3>
            <div style={{ overflowX: 'auto' }}>
              <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '13px' }}>
                <thead>
                  <tr style={{ color: '#8A879F', borderBottom: '1px solid #ECEEF3', textAlign: 'left' }}>
                    <th style={{ padding: '12px 10px', width: '60px' }}>Avatar</th>
                    <th style={{ padding: '12px 10px' }}>Client</th>
                    <th style={{ padding: '12px 10px' }}>Review</th>
                    <th style={{ padding: '12px 10px' }}>Status</th>
                    <th style={{ padding: '12px 10px', textAlign: 'right' }}>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {testimonials.slice((testimonialsPage - 1) * testimonialsPageSize, testimonialsPage * testimonialsPageSize).map((t) => (
                    <tr key={t._id || t.name} style={{ borderBottom: '1px solid #F2F5FA' }}>
                      <td style={{ padding: '10px' }}>
                        <img src={t.avatar} alt={t.name} style={{ width: '40px', height: '40px', objectFit: 'cover', borderRadius: '50%' }} />
                      </td>
                      <td style={{ padding: '12px 10px', fontWeight: '700', color: '#171151' }}>
                        {t.name}
                        <div style={{ fontSize: '12px', color: '#8A879F', fontWeight: 'normal' }}>{t.position}</div>
                      </td>
                      <td style={{ padding: '12px 10px', color: '#6b6b6b', maxWidth: '350px' }}>{t.review}</td>
                      <td style={{ padding: '12px 10px' }}>
                        <button
                          type="button"
                          onClick={() => toggleTestimonialEnabled(t._id || t.name)}
                          style={{
                            display: 'inline-flex',
                            alignItems: 'center',
                            gap: '5px',
                            padding: '4px 10px',
                            borderRadius: '20px',
                            border: 'none',
                            fontSize: '11px',
                            fontWeight: '600',
                            backgroundColor: t.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                            color: t.enabled !== false ? '#0b9748' : '#F72A75',
                            cursor: 'pointer',
                          }}
                        >
                          {t.enabled !== false ? <Eye size={12} /> : <EyeOff size={12} />}
                          <span>{t.enabled !== false ? 'Active' : 'Disabled'}</span>
                        </button>
                      </td>
                      <td style={{ padding: '12px 10px', textAlign: 'right' }}>
                        <div style={{ display: 'flex', gap: '8px', justifyContent: 'flex-end' }}>
                          <button
                            type="button"
                            onClick={() => setEditModal({ open: true, type: 'testimonial', data: { ...t } })}
                            title="Edit Review"
                            style={{ display: 'inline-flex', alignItems: 'center', gap: '4px', padding: '6px 12px', backgroundColor: '#EFF6FF', color: '#0E63FF', border: '1px solid #BFDBFE', borderRadius: '6px', fontSize: '12px', fontWeight: '600', cursor: 'pointer' }}
                          >
                            <Edit2 size={13} /> Edit
                          </button>
                          <button
                            type="button"
                            onClick={() => setDeleteModal({ open: true, type: 'testimonial', id: t._id || t.name, title: t.name })}
                            title="Delete Review"
                            style={{ padding: '6px 10px', backgroundColor: '#feeaf1', color: '#F72A75', border: '1px solid #FECDD3', borderRadius: '6px', cursor: 'pointer' }}
                          >
                            <Trash2 size={14} />
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>

            {/* Testimonials Pagination Bar */}
            {testimonials.length > 0 && (
              <div
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'space-between',
                  paddingTop: '16px',
                  marginTop: '16px',
                  borderTop: '1px solid #F1F5F9',
                  flexWrap: 'wrap',
                  gap: '12px',
                }}
              >
                <span style={{ fontSize: '13px', color: '#64748B' }}>
                  Showing {(testimonialsPage - 1) * testimonialsPageSize + 1} to {Math.min(testimonialsPage * testimonialsPageSize, testimonials.length)} of {testimonials.length} reviews
                </span>
                <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                  <button
                    type="button"
                    disabled={testimonialsPage === 1}
                    onClick={() => setTestimonialsPage((p) => Math.max(p - 1, 1))}
                    style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      border: '1px solid #D1D6E0',
                      backgroundColor: testimonialsPage === 1 ? '#F8FAFC' : '#ffffff',
                      color: testimonialsPage === 1 ? '#CBD5E1' : '#171151',
                      cursor: testimonialsPage === 1 ? 'not-allowed' : 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '4px',
                      fontSize: '12px',
                      fontWeight: '600',
                    }}
                  >
                    <ChevronLeft size={14} />
                    <span>Previous</span>
                  </button>
                  <span style={{ fontSize: '13px', fontWeight: '700', padding: '0 8px', color: '#171151' }}>
                    Page {testimonialsPage} of {Math.ceil(testimonials.length / testimonialsPageSize) || 1}
                  </span>
                  <button
                    type="button"
                    disabled={testimonialsPage >= Math.ceil(testimonials.length / testimonialsPageSize)}
                    onClick={() => setTestimonialsPage((p) => Math.min(p + 1, Math.ceil(testimonials.length / testimonialsPageSize)))}
                    style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      border: '1px solid #D1D6E0',
                      backgroundColor: testimonialsPage >= Math.ceil(testimonials.length / testimonialsPageSize) ? '#F8FAFC' : '#ffffff',
                      color: testimonialsPage >= Math.ceil(testimonials.length / testimonialsPageSize) ? '#CBD5E1' : '#171151',
                      cursor: testimonialsPage >= Math.ceil(testimonials.length / testimonialsPageSize) ? 'not-allowed' : 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '4px',
                      fontSize: '12px',
                      fontWeight: '600',
                    }}
                  >
                    <span>Next</span>
                    <ChevronRight size={14} />
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      )}

      {/* TAB 10: BRANDS */}
      {activeTab === 'brands' && (
        <div>
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Plus size={18} color="#0E63FF" /> Add Partner Brand Logo
            </h3>
            <form onSubmit={handleAddBrand} style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '16px' }}>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Brand Name / Alt Text</label>
                  <input
                    type="text"
                    placeholder="Brand Name e.g. Siemens, Philips"
                    value={newBrand.alt}
                    onChange={(e) => setNewBrand({ ...newBrand, alt: e.target.value })}
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>
                <div style={{ gridColumn: '1 / -1' }}>
                  <FileUploadField
                    label="Partner Brand Logo (Upload PNG/SVG/JPG or Enter Path)"
                    value={newBrand.image}
                    onChange={(url) => setNewBrand({ ...newBrand, image: url })}
                    placeholder="/assets/img/brand/brand-01.png"
                  />
                </div>
              </div>
              <button type="submit" style={{ width: 'fit-content', padding: '10px 20px', backgroundColor: '#0E63FF', color: '#fff', borderRadius: '6px', border: 'none', fontWeight: '600', cursor: 'pointer' }}>
                + Add Logo
              </button>
            </form>
          </div>

          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px' }}>
              Partner Logos ({brands.length})
            </h3>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '16px' }}>
              {brands.slice((brandsPage - 1) * brandsPageSize, brandsPage * brandsPageSize).map((b) => (
                <div key={b.id} style={{ border: '1px solid #ECEEF3', borderRadius: '8px', padding: '16px', textAlign: 'center', backgroundColor: '#F9FAFC' }}>
                  <div style={{ height: '60px', display: 'flex', alignItems: 'center', justifyContent: 'center', marginBottom: '10px' }}>
                    <img src={b.image} alt={b.alt} style={{ maxHeight: '45px', maxWidth: '100%', objectFit: 'contain' }} />
                  </div>
                  <div style={{ fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '12px' }}>{b.alt || 'Brand Logo'}</div>
                  <div style={{ display: 'flex', gap: '6px', justifyContent: 'center', alignItems: 'center' }}>
                    <button
                      type="button"
                      onClick={() => setEditModal({ open: true, type: 'brand', data: { ...b } })}
                      style={{ padding: '4px 10px', backgroundColor: '#EFF6FF', color: '#0E63FF', border: '1px solid #BFDBFE', borderRadius: '4px', fontSize: '11px', fontWeight: '600', cursor: 'pointer' }}
                    >
                      <Edit2 size={12} /> Edit
                    </button>
                    <button
                      type="button"
                      onClick={() => toggleBrandEnabled(b.id)}
                      style={{ padding: '4px 8px', borderRadius: '4px', border: 'none', fontSize: '11px', fontWeight: '600', backgroundColor: b.enabled !== false ? '#E7FAF6' : '#FEEAF1', color: b.enabled !== false ? '#0b9748' : '#F72A75', cursor: 'pointer' }}
                    >
                      {b.enabled !== false ? 'Active' : 'Off'}
                    </button>
                    <button
                      type="button"
                      onClick={() => setDeleteModal({ open: true, type: 'brand', id: b.id, title: b.alt || 'Brand Logo' })}
                      style={{ padding: '4px 8px', backgroundColor: '#feeaf1', color: '#F72A75', border: '1px solid #FECDD3', borderRadius: '4px', cursor: 'pointer' }}
                    >
                      <Trash2 size={12} />
                    </button>
                  </div>
                </div>
              ))}
            </div>

            {/* Brands Pagination Bar */}
            {brands.length > 0 && (
              <div
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'space-between',
                  paddingTop: '16px',
                  marginTop: '16px',
                  borderTop: '1px solid #F1F5F9',
                  flexWrap: 'wrap',
                  gap: '12px',
                }}
              >
                <span style={{ fontSize: '13px', color: '#64748B' }}>
                  Showing {(brandsPage - 1) * brandsPageSize + 1} to {Math.min(brandsPage * brandsPageSize, brands.length)} of {brands.length} logos
                </span>
                <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                  <button
                    type="button"
                    disabled={brandsPage === 1}
                    onClick={() => setBrandsPage((p) => Math.max(p - 1, 1))}
                    style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      border: '1px solid #D1D6E0',
                      backgroundColor: brandsPage === 1 ? '#F8FAFC' : '#ffffff',
                      color: brandsPage === 1 ? '#CBD5E1' : '#171151',
                      cursor: brandsPage === 1 ? 'not-allowed' : 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '4px',
                      fontSize: '12px',
                      fontWeight: '600',
                    }}
                  >
                    <ChevronLeft size={14} />
                    <span>Previous</span>
                  </button>
                  <span style={{ fontSize: '13px', fontWeight: '700', padding: '0 8px', color: '#171151' }}>
                    Page {brandsPage} of {Math.ceil(brands.length / brandsPageSize) || 1}
                  </span>
                  <button
                    type="button"
                    disabled={brandsPage >= Math.ceil(brands.length / brandsPageSize)}
                    onClick={() => setBrandsPage((p) => Math.min(p + 1, Math.ceil(brands.length / brandsPageSize)))}
                    style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      border: '1px solid #D1D6E0',
                      backgroundColor: brandsPage >= Math.ceil(brands.length / brandsPageSize) ? '#F8FAFC' : '#ffffff',
                      color: brandsPage >= Math.ceil(brands.length / brandsPageSize) ? '#CBD5E1' : '#171151',
                      cursor: brandsPage >= Math.ceil(brands.length / brandsPageSize) ? 'not-allowed' : 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '4px',
                      fontSize: '12px',
                      fontWeight: '600',
                    }}
                  >
                    <span>Next</span>
                    <ChevronRight size={14} />
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      )}

      {/* TAB 11: CTA */}
      {activeTab === 'cta' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Quick Call CTA Banner
            </h3>
            <button
              onClick={() => toggleSectionEnabled('ctaSection')}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '6px 14px',
                borderRadius: '20px',
                border: 'none',
                fontWeight: '600',
                fontSize: '12px',
                cursor: 'pointer',
                backgroundColor: config.ctaSection?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: config.ctaSection?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {config.ctaSection?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{config.ctaSection?.enabled !== false ? 'Section Visible' : 'Section Disabled'}</span>
            </button>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Banner Heading Title</label>
              <input
                type="text"
                value={config.ctaSection?.title || ''}
                onChange={(e) => handleConfigChange('ctaSection', 'title', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Call Button Phone Number</label>
              <input
                type="text"
                value={config.ctaSection?.phone || ''}
                onChange={(e) => handleConfigChange('ctaSection', 'phone', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
            <div style={{ gridColumn: '1 / -1' }}>
              <FileUploadField
                label="CTA Banner Main Background Image (Scientist / Blue Banner Graphic)"
                value={config.ctaSection?.bgImage || config.ctaSection?.image || '/assets/img/shape/shape-bg-03.png'}
                onChange={(val) => {
                  handleConfigChange('ctaSection', 'bgImage', val);
                  handleConfigChange('ctaSection', 'image', val);
                }}
                placeholder="Upload banner image or enter path..."
              />
            </div>
            <div style={{ gridColumn: '1 / -1' }}>
              <FileUploadField
                label="CTA Right Side Feature Image (Optional Overlay Image)"
                value={config.ctaSection?.thumbImage || ''}
                onChange={(val) => {
                  handleConfigChange('ctaSection', 'thumbImage', val);
                }}
                placeholder="Upload optional right side scientist / lab image overlay..."
              />
            </div>
          </div>
        </div>
      )}

      {/* TAB 12: BLOG & NEWS */}
      {activeTab === 'blog' && (
        <div>
          {/* Header Settings */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '16px' }}>
              <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', margin: 0 }}>
                Blog & News Section Settings
              </h3>
              <button
                onClick={() => toggleSectionEnabled('blogSection')}
                style={{
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '6px',
                  padding: '6px 14px',
                  borderRadius: '20px',
                  border: 'none',
                  fontWeight: '600',
                  fontSize: '12px',
                  cursor: 'pointer',
                  backgroundColor: config.blogSection?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                  color: config.blogSection?.enabled !== false ? '#0b9748' : '#F72A75',
                }}
              >
                {config.blogSection?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
                <span>{config.blogSection?.enabled !== false ? 'Section Visible' : 'Section Disabled'}</span>
              </button>
            </div>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Subtitle</label>
                <input
                  type="text"
                  value={config.blogSection?.subTitle || ''}
                  onChange={(e) => handleConfigChange('blogSection', 'subTitle', e.target.value)}
                  style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Title</label>
                <input
                  type="text"
                  value={config.blogSection?.title || ''}
                  onChange={(e) => handleConfigChange('blogSection', 'title', e.target.value)}
                  style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                />
              </div>
            </div>
          </div>

          {/* Add Article Form with Cover Image Upload */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Plus size={18} color="#0E63FF" /> Add Medical Article / News
            </h3>
            <form onSubmit={handleAddBlog}>
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '16px' }}>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Article Title</label>
                  <input
                    type="text"
                    placeholder="e.g. Innovations in Clinical Diagnostics"
                    value={newBlog.title}
                    onChange={(e) => setNewBlog({ ...newBlog, title: e.target.value })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Category</label>
                  <input
                    type="text"
                    placeholder="e.g. Biomedical Engineering"
                    value={newBlog.category}
                    onChange={(e) => setNewBlog({ ...newBlog, category: e.target.value })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Author Name</label>
                  <input
                    type="text"
                    placeholder="e.g. Engr. Faisal Malik"
                    value={newBlog.author}
                    onChange={(e) => setNewBlog({ ...newBlog, author: e.target.value })}
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Publication Month & Day</label>
                  <div style={{ display: 'flex', gap: '10px' }}>
                    <input
                      type="text"
                      placeholder="Aug"
                      value={newBlog.dateMonth}
                      onChange={(e) => setNewBlog({ ...newBlog, dateMonth: e.target.value })}
                      style={{ width: '50%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                    />
                    <input
                      type="text"
                      placeholder="14"
                      value={newBlog.dateDay}
                      onChange={(e) => setNewBlog({ ...newBlog, dateDay: e.target.value })}
                      style={{ width: '50%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                    />
                  </div>
                </div>
                <div style={{ gridColumn: '1 / -1' }}>
                  <FileUploadField
                    label="Article Cover Image (Upload Local Image or Enter Path)"
                    value={newBlog.image}
                    onChange={(url) => setNewBlog({ ...newBlog, image: url })}
                    placeholder="/assets/img/blog/blog-thumb-01.jpg"
                  />
                </div>
                <div style={{ gridColumn: '1 / -1' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Short Summary / Excerpt</label>
                  <textarea
                    rows={2}
                    placeholder="Write short article excerpt..."
                    value={newBlog.excerpt}
                    onChange={(e) => setNewBlog({ ...newBlog, excerpt: e.target.value })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  ></textarea>
                </div>

                {/* Social Media Links in Home Blogs Tab */}
                <div style={{ gridColumn: '1 / -1', marginTop: '6px', padding: '14px', backgroundColor: '#F8FAFC', borderRadius: '8px', border: '1px solid #E2E8F0' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '10px' }}>
                    🔗 Social Media Icons & Share Links (Optional)
                  </label>
                  <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '10px' }}>
                    <div>
                      <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#0A66C2', marginBottom: '3px' }}>LinkedIn URL</span>
                      <input
                        type="url"
                        placeholder="https://linkedin.com/..."
                        value={newBlog.socialLinks?.linkedin || ''}
                        onChange={(e) => setNewBlog({ ...newBlog, socialLinks: { ...newBlog.socialLinks, linkedin: e.target.value } })}
                        style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                    <div>
                      <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#1DA1F2', marginBottom: '3px' }}>Twitter / X URL</span>
                      <input
                        type="url"
                        placeholder="https://twitter.com/..."
                        value={newBlog.socialLinks?.twitter || ''}
                        onChange={(e) => setNewBlog({ ...newBlog, socialLinks: { ...newBlog.socialLinks, twitter: e.target.value } })}
                        style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                    <div>
                      <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#1877F2', marginBottom: '3px' }}>Facebook URL</span>
                      <input
                        type="url"
                        placeholder="https://facebook.com/..."
                        value={newBlog.socialLinks?.facebook || ''}
                        onChange={(e) => setNewBlog({ ...newBlog, socialLinks: { ...newBlog.socialLinks, facebook: e.target.value } })}
                        style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                    <div>
                      <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#E4405F', marginBottom: '3px' }}>Instagram URL</span>
                      <input
                        type="url"
                        placeholder="https://instagram.com/..."
                        value={newBlog.socialLinks?.instagram || ''}
                        onChange={(e) => setNewBlog({ ...newBlog, socialLinks: { ...newBlog.socialLinks, instagram: e.target.value } })}
                        style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                    <div>
                      <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#FF0000', marginBottom: '3px' }}>YouTube URL</span>
                      <input
                        type="url"
                        placeholder="https://youtube.com/..."
                        value={newBlog.socialLinks?.youtube || ''}
                        onChange={(e) => setNewBlog({ ...newBlog, socialLinks: { ...newBlog.socialLinks, youtube: e.target.value } })}
                        style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                  </div>
                </div>
              </div>
              <button type="submit" style={{ marginTop: '16px', padding: '10px 18px', backgroundColor: '#0E63FF', color: '#fff', borderRadius: '6px', border: 'none', fontWeight: '600', cursor: 'pointer' }}>
                + Add Article
              </button>
            </form>
          </div>

          {/* Blogs Table with Edit, Toggle, Delete */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px' }}>
              Articles & News ({blogs.length})
            </h3>
            <div style={{ overflowX: 'auto' }}>
              <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '13px' }}>
                <thead>
                  <tr style={{ color: '#8A879F', borderBottom: '1px solid #ECEEF3', textAlign: 'left' }}>
                    <th style={{ padding: '12px 10px', width: '70px' }}>Image</th>
                    <th style={{ padding: '12px 10px' }}>Title</th>
                    <th style={{ padding: '12px 10px' }}>Category</th>
                    <th style={{ padding: '12px 10px' }}>Summary</th>
                    <th style={{ padding: '12px 10px' }}>Status</th>
                    <th style={{ padding: '12px 10px', textAlign: 'right' }}>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {blogs.slice((blogsPage - 1) * blogsPageSize, blogsPage * blogsPageSize).map((b) => (
                    <tr key={b._id || b.title} style={{ borderBottom: '1px solid #F2F5FA' }}>
                      <td style={{ padding: '10px' }}>
                        <img src={b.image} alt={b.title} style={{ width: '55px', height: '40px', objectFit: 'cover', borderRadius: '4px' }} />
                      </td>
                      <td style={{ padding: '12px 10px', fontWeight: '700', color: '#171151' }}>{b.title}</td>
                      <td style={{ padding: '12px 10px', color: '#0E63FF', fontWeight: '600' }}>{b.category}</td>
                      <td style={{ padding: '12px 10px', color: '#6b6b6b', maxWidth: '300px' }}>{b.excerpt}</td>
                      <td style={{ padding: '12px 10px' }}>
                        <button
                          type="button"
                          onClick={() => toggleBlogEnabled(b._id || b.title)}
                          style={{
                            display: 'inline-flex',
                            alignItems: 'center',
                            gap: '5px',
                            padding: '4px 10px',
                            borderRadius: '20px',
                            border: 'none',
                            fontSize: '11px',
                            fontWeight: '600',
                            backgroundColor: b.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                            color: b.enabled !== false ? '#0b9748' : '#F72A75',
                            cursor: 'pointer',
                          }}
                        >
                          {b.enabled !== false ? <Eye size={12} /> : <EyeOff size={12} />}
                          <span>{b.enabled !== false ? 'Active' : 'Disabled'}</span>
                        </button>
                      </td>
                      <td style={{ padding: '12px 10px', textAlign: 'right' }}>
                        <div style={{ display: 'flex', gap: '8px', justifyContent: 'flex-end' }}>
                          <button
                            type="button"
                            onClick={() => setEditModal({ open: true, type: 'blog', data: { ...b } })}
                            title="Edit Article"
                            style={{ display: 'inline-flex', alignItems: 'center', gap: '4px', padding: '6px 12px', backgroundColor: '#EFF6FF', color: '#0E63FF', border: '1px solid #BFDBFE', borderRadius: '6px', fontSize: '12px', fontWeight: '600', cursor: 'pointer' }}
                          >
                            <Edit2 size={13} /> Edit
                          </button>
                          <button
                            type="button"
                            onClick={() => setDeleteModal({ open: true, type: 'blog', id: b._id || b.title, title: b.title })}
                            title="Delete Article"
                            style={{ padding: '6px 10px', backgroundColor: '#feeaf1', color: '#F72A75', border: '1px solid #FECDD3', borderRadius: '6px', cursor: 'pointer' }}
                          >
                            <Trash2 size={14} />
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>

            {/* Blogs Pagination Bar */}
            {blogs.length > 0 && (
              <div
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'space-between',
                  paddingTop: '16px',
                  marginTop: '16px',
                  borderTop: '1px solid #F1F5F9',
                  flexWrap: 'wrap',
                  gap: '12px',
                }}
              >
                <span style={{ fontSize: '13px', color: '#64748B' }}>
                  Showing {(blogsPage - 1) * blogsPageSize + 1} to {Math.min(blogsPage * blogsPageSize, blogs.length)} of {blogs.length} articles
                </span>
                <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                  <button
                    type="button"
                    disabled={blogsPage === 1}
                    onClick={() => setBlogsPage((p) => Math.max(p - 1, 1))}
                    style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      border: '1px solid #D1D6E0',
                      backgroundColor: blogsPage === 1 ? '#F8FAFC' : '#ffffff',
                      color: blogsPage === 1 ? '#CBD5E1' : '#171151',
                      cursor: blogsPage === 1 ? 'not-allowed' : 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '4px',
                      fontSize: '12px',
                      fontWeight: '600',
                    }}
                  >
                    <ChevronLeft size={14} />
                    <span>Previous</span>
                  </button>
                  <span style={{ fontSize: '13px', fontWeight: '700', padding: '0 8px', color: '#171151' }}>
                    Page {blogsPage} of {Math.ceil(blogs.length / blogsPageSize) || 1}
                  </span>
                  <button
                    type="button"
                    disabled={blogsPage >= Math.ceil(blogs.length / blogsPageSize)}
                    onClick={() => setBlogsPage((p) => Math.min(p + 1, Math.ceil(blogs.length / blogsPageSize)))}
                    style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      border: '1px solid #D1D6E0',
                      backgroundColor: blogsPage >= Math.ceil(blogs.length / blogsPageSize) ? '#F8FAFC' : '#ffffff',
                      color: blogsPage >= Math.ceil(blogs.length / blogsPageSize) ? '#CBD5E1' : '#171151',
                      cursor: blogsPage >= Math.ceil(blogs.length / blogsPageSize) ? 'not-allowed' : 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '4px',
                      fontSize: '12px',
                      fontWeight: '600',
                    }}
                  >
                    <span>Next</span>
                    <ChevronRight size={14} />
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      )}

      {/* TAB 13: FOOTER */}
      {activeTab === 'footer' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', marginBottom: '20px' }}>
            Footer & Official Contact Details
          </h3>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Primary Phone Number</label>
              <input
                type="text"
                value={config.footer?.phone || ''}
                onChange={(e) => handleConfigChange('footer', 'phone', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Official Email</label>
              <input
                type="email"
                value={config.footer?.email || ''}
                onChange={(e) => handleConfigChange('footer', 'email', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
            <div style={{ gridColumn: '1 / -1' }}>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Head Office Address</label>
              <textarea
                rows={2}
                value={config.footer?.address || ''}
                onChange={(e) => handleConfigChange('footer', 'address', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              ></textarea>
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Working Hours</label>
              <input
                type="text"
                value={config.footer?.officeHours || ''}
                onChange={(e) => handleConfigChange('footer', 'officeHours', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Copyright Line</label>
              <input
                type="text"
                value={config.footer?.copyrightText || ''}
                onChange={(e) => handleConfigChange('footer', 'copyrightText', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>

            {/* Social Media Links for Footer & Side Menu */}
            <div style={{ gridColumn: '1 / -1', marginTop: '10px', padding: '18px', backgroundColor: '#F8FAFC', borderRadius: '10px', border: '1px solid #E2E8F0' }}>
              <h4 style={{ fontSize: '14px', fontWeight: '800', color: '#171151', marginBottom: '14px' }}>
                🔗 Official Social Media Links (Footer & Side Menu)
              </h4>
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '14px' }}>
                <div>
                  <span style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#1877F2', marginBottom: '4px' }}>Facebook URL</span>
                  <input
                    type="url"
                    placeholder="https://facebook.com/innotechmedical"
                    value={config.footer?.socialLinks?.facebook || ''}
                    onChange={(e) =>
                      handleConfigChange('footer', 'socialLinks', {
                        ...(config.footer?.socialLinks || {}),
                        facebook: e.target.value,
                      })
                    }
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <span style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#1DA1F2', marginBottom: '4px' }}>Twitter / X URL</span>
                  <input
                    type="url"
                    placeholder="https://twitter.com/innotechmedical"
                    value={config.footer?.socialLinks?.twitter || ''}
                    onChange={(e) =>
                      handleConfigChange('footer', 'socialLinks', {
                        ...(config.footer?.socialLinks || {}),
                        twitter: e.target.value,
                      })
                    }
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <span style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#0A66C2', marginBottom: '4px' }}>LinkedIn URL</span>
                  <input
                    type="url"
                    placeholder="https://linkedin.com/company/innotech-medical"
                    value={config.footer?.socialLinks?.linkedin || ''}
                    onChange={(e) =>
                      handleConfigChange('footer', 'socialLinks', {
                        ...(config.footer?.socialLinks || {}),
                        linkedin: e.target.value,
                      })
                    }
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <span style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#E4405F', marginBottom: '4px' }}>Instagram URL</span>
                  <input
                    type="url"
                    placeholder="https://instagram.com/innotechmedical"
                    value={config.footer?.socialLinks?.instagram || ''}
                    onChange={(e) =>
                      handleConfigChange('footer', 'socialLinks', {
                        ...(config.footer?.socialLinks || {}),
                        instagram: e.target.value,
                      })
                    }
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <span style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#FF0000', marginBottom: '4px' }}>YouTube URL</span>
                  <input
                    type="url"
                    placeholder="https://youtube.com/..."
                    value={config.footer?.socialLinks?.youtube || ''}
                    onChange={(e) =>
                      handleConfigChange('footer', 'socialLinks', {
                        ...(config.footer?.socialLinks || {}),
                        youtube: e.target.value,
                      })
                    }
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <span style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#00AFF0', marginBottom: '4px' }}>Skype / WhatsApp URL</span>
                  <input
                    type="url"
                    placeholder="https://wa.me/923316699992"
                    value={config.footer?.socialLinks?.skype || ''}
                    onChange={(e) =>
                      handleConfigChange('footer', 'socialLinks', {
                        ...(config.footer?.socialLinks || {}),
                        skype: e.target.value,
                      })
                    }
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* UNIVERSAL EDIT MODAL POPUP WITH FILE UPLOAD PICKERS */}
      {editModal.open && editModal.data && (
        <div
          style={{
            position: 'fixed',
            inset: 0,
            zIndex: 999999,
            backgroundColor: 'rgba(15, 23, 42, 0.65)',
            backdropFilter: 'blur(4px)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '20px',
            animation: 'fadeIn 0.2s ease',
          }}
          onClick={() => setEditModal({ open: false, type: '', data: null })}
        >
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '16px',
              maxWidth: '620px',
              width: '100%',
              maxHeight: '90vh',
              overflowY: 'auto',
              padding: '28px',
              boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
              position: 'relative',
            }}
            onClick={(e) => e.stopPropagation()}
          >
            {/* Modal Header */}
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px', paddingBottom: '16px', borderBottom: '1px solid #ECEEF3' }}>
              <div>
                <h3 style={{ fontSize: '18px', fontWeight: '800', color: '#171151', margin: '0 0 4px', textTransform: 'capitalize' }}>
                  Edit {editModal.type} Item
                </h3>
                <p style={{ fontSize: '13px', color: '#64748B', margin: 0 }}>
                  Update details, upload new images, and click Save Changes
                </p>
              </div>
              <button
                type="button"
                onClick={() => setEditModal({ open: false, type: '', data: null })}
                style={{
                  background: '#F1F5F9',
                  border: 'none',
                  borderRadius: '50%',
                  width: '36px',
                  height: '36px',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  cursor: 'pointer',
                  color: '#64748B',
                }}
              >
                <X size={18} />
              </button>
            </div>

            {/* Modal Form */}
            <form onSubmit={handleSaveModalEdit}>
              {/* Type: Service */}
              {editModal.type === 'service' && (
                <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Service Title</label>
                    <input
                      type="text"
                      value={editModal.data.title || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, title: e.target.value } })}
                      required
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Color Theme</label>
                    <select
                      value={editModal.data.iconTheme || 'blue'}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, iconTheme: e.target.value } })}
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    >
                      <option value="blue">Blue Theme</option>
                      <option value="pink">Pink Theme</option>
                      <option value="green">Green Theme</option>
                      <option value="sky">Sky Blue Theme</option>
                    </select>
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Description</label>
                    <textarea
                      rows={3}
                      value={editModal.data.description || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, description: e.target.value } })}
                      required
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    ></textarea>
                  </div>
                </div>
              )}

              {/* Type: Gallery */}
              {editModal.type === 'gallery' && (
                <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Gallery Title</label>
                    <input
                      type="text"
                      value={editModal.data.title || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, title: e.target.value } })}
                      required
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Category Tag</label>
                    <input
                      type="text"
                      value={editModal.data.tag || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, tag: e.target.value } })}
                      required
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <FileUploadField
                    label="Gallery Image (Upload Local Image or Enter Path)"
                    value={editModal.data.image || ''}
                    onChange={(url) => setEditModal({ ...editModal, data: { ...editModal.data, image: url } })}
                  />
                </div>
              )}

              {/* Type: Team */}
              {editModal.type === 'team' && (
                <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Doctor / Specialist Name</label>
                    <input
                      type="text"
                      value={editModal.data.name || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, name: e.target.value } })}
                      required
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Role / Designation</label>
                    <input
                      type="text"
                      value={editModal.data.position || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, position: e.target.value } })}
                      required
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Phone / Direct Line</label>
                    <input
                      type="text"
                      value={editModal.data.phone || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, phone: e.target.value } })}
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Official Email</label>
                    <input
                      type="email"
                      value={editModal.data.email || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, email: e.target.value } })}
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Clinical Experience</label>
                    <input
                      type="text"
                      value={editModal.data.experience || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, experience: e.target.value } })}
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <FileUploadField
                    label="Specialist Photo (Upload Local Image or Enter Path)"
                    value={editModal.data.image || ''}
                    onChange={(url) => setEditModal({ ...editModal, data: { ...editModal.data, image: url } })}
                  />
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Short Biography (Visible on Cards)</label>
                    <textarea
                      rows={2}
                      value={editModal.data.bio || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, bio: e.target.value } })}
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    ></textarea>
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Full Biography (Team Details Page)</label>
                    <textarea
                      rows={4}
                      value={editModal.data.biography || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, biography: e.target.value } })}
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    ></textarea>
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>
                      Core Competencies Checklist (Team Details Page - One capability per line)
                    </label>
                    <textarea
                      rows={4}
                      placeholder={`Biomedical Device Calibration & Safety Testing\nTurnkey Operating Room & ICU Integration\nComprehensive Healthcare Provider Clinical Training\n24/7 Rapid Emergency Technical Support & Diagnostics`}
                      value={typeof editModal.data.skills === 'string' ? editModal.data.skills : (editModal.data.skills || []).join('\n')}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, skills: e.target.value } })}
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    ></textarea>
                  </div>

                  {/* Social Media Links in Edit Specialist Modal */}
                  <div style={{ marginTop: '8px', padding: '14px', backgroundColor: '#F8FAFC', borderRadius: '8px', border: '1px solid #E2E8F0' }}>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '10px' }}>
                      🔗 Specialist Social Media Links (Optional)
                    </label>
                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '10px' }}>
                      <div>
                        <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#1877F2', marginBottom: '3px' }}>Facebook URL</span>
                        <input
                          type="url"
                          placeholder="https://facebook.com/..."
                          value={editModal.data.socialLinks?.facebook || ''}
                          onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, socialLinks: { ...(editModal.data.socialLinks || {}), facebook: e.target.value } } })}
                          style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                        />
                      </div>
                      <div>
                        <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#1DA1F2', marginBottom: '3px' }}>Twitter / X URL</span>
                        <input
                          type="url"
                          placeholder="https://twitter.com/..."
                          value={editModal.data.socialLinks?.twitter || ''}
                          onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, socialLinks: { ...(editModal.data.socialLinks || {}), twitter: e.target.value } } })}
                          style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                        />
                      </div>
                      <div>
                        <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#0A66C2', marginBottom: '3px' }}>LinkedIn URL</span>
                        <input
                          type="url"
                          placeholder="https://linkedin.com/in/..."
                          value={editModal.data.socialLinks?.linkedin || ''}
                          onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, socialLinks: { ...(editModal.data.socialLinks || {}), linkedin: e.target.value } } })}
                          style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                        />
                      </div>
                      <div>
                        <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#E4405F', marginBottom: '3px' }}>Instagram URL</span>
                        <input
                          type="url"
                          placeholder="https://instagram.com/..."
                          value={editModal.data.socialLinks?.instagram || ''}
                          onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, socialLinks: { ...(editModal.data.socialLinks || {}), instagram: e.target.value } } })}
                          style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                        />
                      </div>
                      <div>
                        <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#FF0000', marginBottom: '3px' }}>YouTube URL</span>
                        <input
                          type="url"
                          placeholder="https://youtube.com/..."
                          value={editModal.data.socialLinks?.youtube || ''}
                          onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, socialLinks: { ...(editModal.data.socialLinks || {}), youtube: e.target.value } } })}
                          style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                        />
                      </div>
                    </div>
                  </div>
                </div>
              )}

              {/* Type: Testimonial */}
              {editModal.type === 'testimonial' && (
                <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Client Name</label>
                    <input
                      type="text"
                      value={editModal.data.name || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, name: e.target.value } })}
                      required
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Designation / Hospital</label>
                    <input
                      type="text"
                      value={editModal.data.position || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, position: e.target.value } })}
                      required
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <FileUploadField
                    label="Client Avatar Photo (Upload Local Image or Enter Path)"
                    value={editModal.data.avatar || ''}
                    onChange={(url) => setEditModal({ ...editModal, data: { ...editModal.data, avatar: url } })}
                  />
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Review Quote</label>
                    <textarea
                      rows={3}
                      value={editModal.data.review || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, review: e.target.value } })}
                      required
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    ></textarea>
                  </div>
                </div>
              )}

              {/* Type: Brand */}
              {editModal.type === 'brand' && (
                <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Brand Name / Alt Text</label>
                    <input
                      type="text"
                      value={editModal.data.alt || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, alt: e.target.value } })}
                      required
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <FileUploadField
                    label="Brand Logo Image (Upload Local Image or Enter Path)"
                    value={editModal.data.image || ''}
                    onChange={(url) => setEditModal({ ...editModal, data: { ...editModal.data, image: url } })}
                  />
                </div>
              )}

              {/* Type: Blog */}
              {editModal.type === 'blog' && (
                <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Article Title</label>
                    <input
                      type="text"
                      value={editModal.data.title || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, title: e.target.value } })}
                      required
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Category</label>
                    <input
                      type="text"
                      value={editModal.data.category || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, category: e.target.value } })}
                      required
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>

                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Author Name</label>
                    <input
                      type="text"
                      placeholder="e.g. Engr. Faisal Malik"
                      value={editModal.data.author || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, author: e.target.value } })}
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>

                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Publication Month & Day</label>
                    <div style={{ display: 'flex', gap: '10px' }}>
                      <input
                        type="text"
                        placeholder="Month (e.g. Aug)"
                        value={editModal.data.dateMonth || ''}
                        onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, dateMonth: e.target.value } })}
                        style={{ width: '50%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                      />
                      <input
                        type="text"
                        placeholder="Day (e.g. 14)"
                        value={editModal.data.dateDay || ''}
                        onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, dateDay: e.target.value } })}
                        style={{ width: '50%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                      />
                    </div>
                  </div>

                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>URL Slug</label>
                    <input
                      type="text"
                      value={editModal.data.slug || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, slug: e.target.value } })}
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>

                  <FileUploadField
                    label="Article Cover Image (Upload Local Image or Enter Path)"
                    value={editModal.data.image || ''}
                    onChange={(url) => setEditModal({ ...editModal, data: { ...editModal.data, image: url } })}
                  />
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Summary / Excerpt</label>
                    <textarea
                      rows={3}
                      value={editModal.data.excerpt || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, excerpt: e.target.value } })}
                      required
                      style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
                    ></textarea>
                  </div>

                  {/* Social Media Links in Edit Blog Modal */}
                  <div style={{ marginTop: '8px', padding: '14px', backgroundColor: '#F8FAFC', borderRadius: '8px', border: '1px solid #E2E8F0' }}>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '10px' }}>
                      🔗 Social Media Icons & Share Links (Optional)
                    </label>
                    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '10px' }}>
                      <div>
                        <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#0A66C2', marginBottom: '3px' }}>LinkedIn URL</span>
                        <input
                          type="url"
                          placeholder="https://linkedin.com/..."
                          value={editModal.data.socialLinks?.linkedin || ''}
                          onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, socialLinks: { ...(editModal.data.socialLinks || {}), linkedin: e.target.value } } })}
                          style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                        />
                      </div>
                      <div>
                        <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#1DA1F2', marginBottom: '3px' }}>Twitter / X URL</span>
                        <input
                          type="url"
                          placeholder="https://twitter.com/..."
                          value={editModal.data.socialLinks?.twitter || ''}
                          onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, socialLinks: { ...(editModal.data.socialLinks || {}), twitter: e.target.value } } })}
                          style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                        />
                      </div>
                      <div>
                        <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#1877F2', marginBottom: '3px' }}>Facebook URL</span>
                        <input
                          type="url"
                          placeholder="https://facebook.com/..."
                          value={editModal.data.socialLinks?.facebook || ''}
                          onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, socialLinks: { ...(editModal.data.socialLinks || {}), facebook: e.target.value } } })}
                          style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                        />
                      </div>
                      <div>
                        <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#E4405F', marginBottom: '3px' }}>Instagram URL</span>
                        <input
                          type="url"
                          placeholder="https://instagram.com/..."
                          value={editModal.data.socialLinks?.instagram || ''}
                          onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, socialLinks: { ...(editModal.data.socialLinks || {}), instagram: e.target.value } } })}
                          style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                        />
                      </div>
                      <div>
                        <span style={{ display: 'block', fontSize: '11.5px', fontWeight: '600', color: '#FF0000', marginBottom: '3px' }}>YouTube URL</span>
                        <input
                          type="url"
                          placeholder="https://youtube.com/..."
                          value={editModal.data.socialLinks?.youtube || ''}
                          onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, socialLinks: { ...(editModal.data.socialLinks || {}), youtube: e.target.value } } })}
                          style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                        />
                      </div>
                    </div>
                  </div>
                </div>
              )}

              {/* Status Switch in Modal */}
              <div style={{ marginTop: '18px', paddingTop: '16px', borderTop: '1px solid #ECEEF3', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                <span style={{ fontSize: '13px', fontWeight: '600', color: '#171151' }}>Item Display Status:</span>
                <button
                  type="button"
                  onClick={() => setEditModal({ ...editModal, data: { ...editModal.data, enabled: editModal.data.enabled === false ? true : false, isActive: editModal.data.isActive === false ? true : false } })}
                  style={{
                    display: 'inline-flex',
                    alignItems: 'center',
                    gap: '6px',
                    padding: '6px 14px',
                    borderRadius: '20px',
                    border: 'none',
                    fontSize: '12px',
                    fontWeight: '600',
                    cursor: 'pointer',
                    backgroundColor: editModal.data.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                    color: editModal.data.enabled !== false ? '#0b9748' : '#F72A75',
                  }}
                >
                  {editModal.data.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
                  <span>{editModal.data.enabled !== false ? 'Active on Live Site' : 'Hidden / Disabled'}</span>
                </button>
              </div>

              {/* Modal Buttons */}
              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '10px', marginTop: '24px' }}>
                <button
                  type="button"
                  onClick={() => setEditModal({ open: false, type: '', data: null })}
                  style={{
                    padding: '10px 18px',
                    backgroundColor: '#F1F5F9',
                    color: '#475569',
                    border: 'none',
                    borderRadius: '8px',
                    fontWeight: '600',
                    fontSize: '13px',
                    cursor: 'pointer',
                  }}
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  style={{
                    padding: '10px 22px',
                    backgroundColor: '#0E63FF',
                    color: '#ffffff',
                    border: 'none',
                    borderRadius: '8px',
                    fontWeight: '600',
                    fontSize: '13px',
                    cursor: 'pointer',
                    boxShadow: '0 4px 12px rgba(14, 99, 255, 0.25)',
                  }}
                >
                  Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* CUSTOM DELETE CONFIRMATION POPUP MODAL */}
      {deleteModal.open && (
        <div
          style={{
            position: 'fixed',
            inset: 0,
            zIndex: 999999,
            backgroundColor: 'rgba(15, 23, 42, 0.65)',
            backdropFilter: 'blur(4px)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '20px',
            animation: 'fadeIn 0.2s ease',
          }}
          onClick={() => setDeleteModal({ open: false, type: '', id: null, title: '' })}
        >
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '16px',
              maxWidth: '460px',
              width: '100%',
              padding: '28px',
              boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
              textAlign: 'center',
            }}
            onClick={(e) => e.stopPropagation()}
          >
            <div
              style={{
                width: '56px',
                height: '56px',
                borderRadius: '50%',
                backgroundColor: '#FEEAF1',
                color: '#F72A75',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                margin: '0 auto 16px',
              }}
            >
              <AlertTriangle size={28} />
            </div>

            <h3 style={{ fontSize: '19px', fontWeight: '800', color: '#171151', margin: '0 0 8px' }}>
              Confirm Deletion
            </h3>

            <p style={{ fontSize: '14px', color: '#64748B', lineHeight: '1.5', margin: '0 0 24px' }}>
              Are you sure you want to delete <strong style={{ color: '#171151' }}>&ldquo;{deleteModal.title}&rdquo;</strong>?
              This item will be permanently removed from your live website.
            </p>

            <div style={{ display: 'flex', gap: '12px', justifyContent: 'center' }}>
              <button
                type="button"
                onClick={() => setDeleteModal({ open: false, type: '', id: null, title: '' })}
                style={{
                  flex: 1,
                  padding: '11px 18px',
                  backgroundColor: '#F1F5F9',
                  color: '#475569',
                  border: 'none',
                  borderRadius: '8px',
                  fontWeight: '600',
                  fontSize: '14px',
                  cursor: 'pointer',
                }}
              >
                Cancel
              </button>
              <button
                type="button"
                onClick={handleConfirmDelete}
                style={{
                  flex: 1,
                  padding: '11px 18px',
                  backgroundColor: '#F72A75',
                  color: '#ffffff',
                  border: 'none',
                  borderRadius: '8px',
                  fontWeight: '600',
                  fontSize: '14px',
                  cursor: 'pointer',
                  boxShadow: '0 4px 12px rgba(247, 42, 117, 0.3)',
                }}
              >
                Yes, Delete
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

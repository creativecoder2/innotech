'use client';

import React, { useState, useEffect, useRef } from 'react';
import {
  Save,
  Plus,
  Trash2,
  Edit2,
  CheckCircle2,
  BookOpen,
  Eye,
  EyeOff,
  X,
  AlertTriangle,
  UploadCloud,
  LayoutTemplate,
  ExternalLink,
  MessageSquare,
  Search,
  Filter,
  Clock,
  User,
  Mail,
  Check,
  XCircle,
  RefreshCw,
  ChevronLeft,
  ChevronRight,
} from 'lucide-react';
import { fallbackBlogPage } from '@/lib/data';

function FileUploadField({
  label,
  value,
  onChange,
  accept = 'image/*',
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
      const res = await fetch('/api/admin/upload', {
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
      alert('Error uploading file');
    } finally {
      setUploading(false);
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
        {value && (
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

        <input
          ref={fileInputRef}
          type="file"
          accept={accept}
          onChange={handleFileChange}
          style={{ display: 'none' }}
        />

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
          }}
        >
          <UploadCloud size={16} />
          <span>{uploading ? 'Uploading...' : 'Upload Image'}</span>
        </button>
      </div>
    </div>
  );
}

export default function AdminBlogsPage() {
  const [activeTab, setActiveTab] = useState('articles');
  const [blogPageData, setBlogPageData] = useState(fallbackBlogPage);

  // Pagination states
  const [articlesPage, setArticlesPage] = useState(1);
  const articlesPageSize = 6;
  const [commentsPage, setCommentsPage] = useState(1);
  const commentsPageSize = 8;
  const [newBlog, setNewBlog] = useState({
    title: '',
    slug: '',
    category: 'Biomedical Engineering',
    author: 'Innotech Editorial',
    dateDay: '12',
    dateMonth: 'Aug',
    dateYear: '2026',
    image: '/assets/img/blog/blog-in-01.jpg',
    excerpt: '',
    content: '',
    tags: 'Biomedical, Diagnostics, Technology',
    socialLinks: {
      linkedin: '',
      twitter: '',
      facebook: '',
      instagram: '',
      youtube: '',
    },
    enabled: true,
  });

  const [editModal, setEditModal] = useState({
    open: false,
    data: null,
  });

  const [deleteModal, setDeleteModal] = useState({
    open: false,
    id: null,
    title: '',
  });

  const [saving, setSaving] = useState(false);
  const [saveStatus, setSaveStatus] = useState({ type: '', msg: '' });

  // Comments Moderation State
  const [comments, setComments] = useState([]);
  const [loadingComments, setLoadingComments] = useState(false);
  const [commentCounts, setCommentCounts] = useState({ total: 0, pending: 0, approved: 0, rejected: 0 });
  const [commentFilter, setCommentFilter] = useState('all');
  const [commentSearch, setCommentSearch] = useState('');

  useEffect(() => {
    fetchData();
    fetchComments();
  }, []);

  const showToast = (msg, type = 'success') => {
    setSaveStatus({ type, msg });
    setTimeout(() => {
      setSaveStatus((prev) => (prev.msg === msg ? { type: '', msg: '' } : prev));
    }, 4500);
  };

  const fetchData = async () => {
    try {
      const res = await fetch('/api/admin/blogs');
      const data = await res.json();
      if (data.data) {
        setBlogPageData(data.data);
      }
    } catch (e) {
      console.error('Error fetching blogs:', e);
    }
  };

  const fetchComments = async () => {
    setLoadingComments(true);
    try {
      const res = await fetch('/api/admin/blogs/comments');
      const data = await res.json();
      if (data.success && Array.isArray(data.data)) {
        setComments(data.data);
        if (data.counts) {
          setCommentCounts(data.counts);
        }
      }
    } catch (e) {
      console.error('Error fetching comments:', e);
    } finally {
      setLoadingComments(false);
    }
  };

  const handleModerateComment = async (id, status) => {
    try {
      const res = await fetch('/api/admin/blogs/comments', {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id, status }),
      });
      const data = await res.json();
      if (res.ok && data.success) {
        showToast(`Comment marked as ${status.toUpperCase()}!`);
        if (typeof window !== 'undefined') {
          window.dispatchEvent(new Event('innotech_notifications_updated'));
        }
        fetchComments();
      } else {
        showToast(data.message || 'Error updating comment', 'error');
      }
    } catch (err) {
      showToast('Network error updating comment status', 'error');
    }
  };

  const handleDeleteComment = async (id) => {
    if (!confirm('Are you sure you want to delete this comment permanently?')) return;
    try {
      const res = await fetch('/api/admin/blogs/comments', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id }),
      });
      const data = await res.json();
      if (res.ok && data.success) {
        showToast('Comment deleted successfully!');
        if (typeof window !== 'undefined') {
          window.dispatchEvent(new Event('innotech_notifications_updated'));
        }
        fetchComments();
      } else {
        showToast(data.message || 'Error deleting comment', 'error');
      }
    } catch (err) {
      showToast('Network error deleting comment', 'error');
    }
  };

  const syncData = async (updated) => {
    try {
      await fetch('/api/admin/blogs', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updated || blogPageData),
      });
    } catch (err) {
      console.error(err);
    }
  };

  const generateSlug = (text) => {
    return text
      .toLowerCase()
      .trim()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-');
  };

  const handleBannerChange = (field, value) => {
    setBlogPageData((prev) => {
      const updated = {
        ...prev,
        banner: {
          ...prev.banner,
          [field]: value,
        },
      };
      syncData(updated);
      return updated;
    });
  };

  const handleAddBlog = (e) => {
    e.preventDefault();
    if (!newBlog.title || !newBlog.excerpt) return;

    const slug = newBlog.slug ? generateSlug(newBlog.slug) : generateSlug(newBlog.title);
    const tagsArray = typeof newBlog.tags === 'string' ? newBlog.tags.split(',').map((t) => t.trim()) : newBlog.tags;

    const item = {
      ...newBlog,
      _id: `blog-${Date.now()}`,
      slug,
      tags: tagsArray,
      content: newBlog.content || newBlog.excerpt,
    };

    const updated = {
      ...blogPageData,
      items: [item, ...(blogPageData.items || [])],
    };
    setBlogPageData(updated);
    syncData(updated);
    setNewBlog({
      title: '',
      slug: '',
      category: 'Biomedical Engineering',
      author: 'Innotech Editorial',
      dateDay: '12',
      dateMonth: 'Aug',
      dateYear: '2026',
      image: '/assets/img/blog/blog-in-01.jpg',
      excerpt: '',
      content: '',
      tags: 'Biomedical, Diagnostics, Technology',
      enabled: true,
    });
    showToast(`Article "${item.title}" published successfully!`);
  };

  const toggleBlogEnabled = (id) => {
    const updatedItems = (blogPageData.items || []).map((b) =>
      (b._id || b.slug) === id ? { ...b, enabled: b.enabled === false ? true : false } : b
    );
    const updated = { ...blogPageData, items: updatedItems };
    setBlogPageData(updated);
    syncData(updated);
    const target = updatedItems.find((b) => (b._id || b.slug) === id);
    showToast(`Article "${target?.title}" is now ${target?.enabled ? 'Active on Live Site' : 'Disabled'}!`);
  };

  const handleSaveModalEdit = (e) => {
    e.preventDefault();
    if (!editModal.data) return;

    const tagsArray =
      typeof editModal.data.tags === 'string'
        ? editModal.data.tags.split(',').map((t) => t.trim())
        : editModal.data.tags;

    const updatedBlog = {
      ...editModal.data,
      slug: editModal.data.slug ? generateSlug(editModal.data.slug) : generateSlug(editModal.data.title),
      tags: tagsArray,
    };

    const updatedItems = (blogPageData.items || []).map((b) =>
      (b._id || b.slug) === (updatedBlog._id || updatedBlog.slug) ? updatedBlog : b
    );
    const updated = { ...blogPageData, items: updatedItems };
    setBlogPageData(updated);
    syncData(updated);
    showToast(`Changes for "${updatedBlog.title}" saved successfully!`);
    setEditModal({ open: false, data: null });
  };

  const handleConfirmDelete = () => {
    const { id, title } = deleteModal;
    const updatedItems = (blogPageData.items || []).filter((b) => (b._id || b.slug) !== id);
    const updated = { ...blogPageData, items: updatedItems };
    setBlogPageData(updated);
    syncData(updated);
    showToast(`Article "${title}" deleted successfully!`);
    setDeleteModal({ open: false, id: null, title: '' });
  };

  const handleSaveAll = async (e) => {
    if (e) e.preventDefault();
    setSaving(true);
    try {
      const res = await fetch('/api/admin/blogs', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(blogPageData),
      });
      const data = await res.json();
      if (res.ok) {
        showToast('All blog articles published live!');
      } else {
        showToast(data.message || 'Error saving changes', 'error');
      }
    } catch (err) {
      showToast('Network error saving configuration.', 'error');
    } finally {
      setSaving(false);
    }
  };

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
            <span>Blog & Clinical Articles Manager</span>
            <span style={{ fontSize: '12px', fontWeight: '600', backgroundColor: '#EFF6FF', color: '#0E63FF', padding: '4px 10px', borderRadius: '20px' }}>
              Dynamic & Live
            </span>
          </h1>
          <p style={{ fontSize: '14px', color: '#6b6b6b', margin: 0 }}>
            Manage blog feeds, article content, authors, tags, cover photos, and dynamic detail pages for /blog.
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
          }}
        >
          <Save size={18} />
          <span>{saving ? 'Saving...' : 'Save & Publish All Articles'}</span>
        </button>
      </div>

      {/* Toast Notification */}
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
          }}
        >
          {saveStatus.type === 'error' ? <AlertTriangle size={20} /> : <CheckCircle2 size={20} />}
          <span>{saveStatus.msg}</span>
        </div>
      )}

      {/* Navigation Tabs */}
      <div
        style={{
          display: 'flex',
          gap: '10px',
          marginBottom: '22px',
          borderBottom: '1px solid #ECEEF3',
          paddingBottom: '12px',
          flexWrap: 'wrap',
        }}
      >
        <button
          onClick={() => setActiveTab('articles')}
          style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '8px',
            padding: '10px 18px',
            borderRadius: '8px',
            border: 'none',
            fontSize: '14px',
            fontWeight: '700',
            cursor: 'pointer',
            backgroundColor: activeTab === 'articles' ? '#0E63FF' : '#ffffff',
            color: activeTab === 'articles' ? '#ffffff' : '#64748B',
            boxShadow: activeTab === 'articles' ? '0 4px 12px rgba(14, 99, 255, 0.2)' : 'none',
            transition: 'all 0.2s ease',
          }}
        >
          <BookOpen size={16} />
          <span>1. All Articles ({(blogPageData.items || []).length})</span>
        </button>

        <button
          onClick={() => setActiveTab('comments')}
          style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '8px',
            padding: '10px 18px',
            borderRadius: '8px',
            border: 'none',
            fontSize: '14px',
            fontWeight: '700',
            cursor: 'pointer',
            backgroundColor: activeTab === 'comments' ? '#0E63FF' : '#ffffff',
            color: activeTab === 'comments' ? '#ffffff' : '#64748B',
            boxShadow: activeTab === 'comments' ? '0 4px 12px rgba(14, 99, 255, 0.2)' : 'none',
            transition: 'all 0.2s ease',
          }}
        >
          <MessageSquare size={16} />
          <span>2. Comments Moderation</span>
          <span
            style={{
              backgroundColor: activeTab === 'comments' ? '#ffffff' : commentCounts.pending > 0 ? '#F72A75' : '#E2E8F0',
              color: activeTab === 'comments' ? '#0E63FF' : commentCounts.pending > 0 ? '#ffffff' : '#475569',
              padding: '2px 8px',
              borderRadius: '10px',
              fontSize: '11px',
              fontWeight: '800',
            }}
          >
            {commentCounts.pending > 0 ? `${commentCounts.pending} Pending` : `${commentCounts.total} Total`}
          </span>
        </button>
      </div>

      {/* TAB 1: ALL ARTICLES */}
      {activeTab === 'articles' && (
        <>
          {/* Banner Settings */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
              <LayoutTemplate size={18} color="#0E63FF" /> Blog Page Top Banner
            </h3>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Main Title</label>
                <input
                  type="text"
                  value={blogPageData.banner?.title || ''}
                  onChange={(e) => handleBannerChange('title', e.target.value)}
                  style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Subtitle</label>
                <input
                  type="text"
                  value={blogPageData.banner?.subTitle || ''}
                  onChange={(e) => handleBannerChange('subTitle', e.target.value)}
                  style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                />
              </div>
              <div style={{ gridColumn: '1 / -1' }}>
                <FileUploadField
                  label="Banner Background Image"
                  value={blogPageData.banner?.bgImage || ''}
                  onChange={(url) => handleBannerChange('bgImage', url)}
                  placeholder="/assets/img/banner/breadcrumb-01.jpg"
                />
              </div>
            </div>
          </div>

          {/* Add Blog Form */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Plus size={18} color="#0E63FF" /> Write & Publish New Medical Article
            </h3>
            <form onSubmit={handleAddBlog}>
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '16px' }}>
                <div style={{ gridColumn: '1 / -1' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Article Title</label>
                  <input
                    type="text"
                    required
                    placeholder="e.g. Next-Generation ICU Ventilators & Critical Patient Monitoring"
                    value={newBlog.title}
                    onChange={(e) => setNewBlog({ ...newBlog, title: e.target.value })}
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Category</label>
                  <input
                    type="text"
                    placeholder="Biomedical Engineering"
                    value={newBlog.category}
                    onChange={(e) => setNewBlog({ ...newBlog, category: e.target.value })}
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Author Name</label>
                  <input
                    type="text"
                    placeholder="Engr. Faisal Malik"
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
                    label="Article Cover Image"
                    value={newBlog.image}
                    onChange={(url) => setNewBlog({ ...newBlog, image: url })}
                    placeholder="/assets/img/blog/blog-in-01.jpg"
                  />
                </div>

                <div style={{ gridColumn: '1 / -1' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Short Summary / Excerpt</label>
                  <textarea
                    rows={2}
                    placeholder="Brief highlights visible on blog listings..."
                    value={newBlog.excerpt}
                    onChange={(e) => setNewBlog({ ...newBlog, excerpt: e.target.value })}
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  ></textarea>
                </div>

                <div style={{ gridColumn: '1 / -1' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Full Article Body</label>
                  <textarea
                    rows={6}
                    placeholder="Full article content..."
                    value={newBlog.content}
                    onChange={(e) => setNewBlog({ ...newBlog, content: e.target.value })}
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  ></textarea>
                </div>

                <div style={{ gridColumn: '1 / -1' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Tags (Comma separated)</label>
                  <input
                    type="text"
                    placeholder="Biomedical, Ultrasound, Hospital"
                    value={newBlog.tags}
                    onChange={(e) => setNewBlog({ ...newBlog, tags: e.target.value })}
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
                  />
                </div>

                {/* Social Media Links & Share Icons */}
                <div style={{ gridColumn: '1 / -1', marginTop: '10px', padding: '16px', backgroundColor: '#F8FAFC', borderRadius: '8px', border: '1px solid #E2E8F0' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '12px' }}>
                    🔗 Social Media Icons & Share Links (Optional)
                  </label>
                  <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))', gap: '12px' }}>
                    <div>
                      <span style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#0A66C2', marginBottom: '4px' }}>LinkedIn URL</span>
                      <input
                        type="url"
                        placeholder="https://linkedin.com/share..."
                        value={newBlog.socialLinks?.linkedin || ''}
                        onChange={(e) => setNewBlog({ ...newBlog, socialLinks: { ...newBlog.socialLinks, linkedin: e.target.value } })}
                        style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                    <div>
                      <span style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#1DA1F2', marginBottom: '4px' }}>Twitter / X URL</span>
                      <input
                        type="url"
                        placeholder="https://twitter.com/..."
                        value={newBlog.socialLinks?.twitter || ''}
                        onChange={(e) => setNewBlog({ ...newBlog, socialLinks: { ...newBlog.socialLinks, twitter: e.target.value } })}
                        style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                    <div>
                      <span style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#1877F2', marginBottom: '4px' }}>Facebook URL</span>
                      <input
                        type="url"
                        placeholder="https://facebook.com/..."
                        value={newBlog.socialLinks?.facebook || ''}
                        onChange={(e) => setNewBlog({ ...newBlog, socialLinks: { ...newBlog.socialLinks, facebook: e.target.value } })}
                        style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                    <div>
                      <span style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#E4405F', marginBottom: '4px' }}>Instagram URL</span>
                      <input
                        type="url"
                        placeholder="https://instagram.com/..."
                        value={newBlog.socialLinks?.instagram || ''}
                        onChange={(e) => setNewBlog({ ...newBlog, socialLinks: { ...newBlog.socialLinks, instagram: e.target.value } })}
                        style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                    <div>
                      <span style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#FF0000', marginBottom: '4px' }}>YouTube URL</span>
                      <input
                        type="url"
                        placeholder="https://youtube.com/..."
                        value={newBlog.socialLinks?.youtube || ''}
                        onChange={(e) => setNewBlog({ ...newBlog, socialLinks: { ...newBlog.socialLinks, youtube: e.target.value } })}
                        style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                      />
                    </div>
                  </div>
                </div>
              </div>

              <div style={{ marginTop: '20px', textAlign: 'right' }}>
                <button
                  type="submit"
                  style={{
                    display: 'inline-flex',
                    alignItems: 'center',
                    gap: '6px',
                    padding: '10px 22px',
                    backgroundColor: '#0E63FF',
                    color: '#ffffff',
                    borderRadius: '8px',
                    border: 'none',
                    fontWeight: '600',
                    cursor: 'pointer',
                  }}
                >
                  <Plus size={16} />
                  <span>Publish Article</span>
                </button>
              </div>
            </form>
          </div>

          {/* Articles Table Grid */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
            <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px' }}>
              Published Medical Articles ({(blogPageData.items || []).length})
            </h3>
            <div style={{ overflowX: 'auto' }}>
              <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '13px' }}>
                <thead>
                  <tr style={{ color: '#8A879F', borderBottom: '1px solid #ECEEF3', textAlign: 'left' }}>
                    <th style={{ padding: '10px 8px' }}>Thumbnail</th>
                    <th style={{ padding: '10px 8px' }}>Article Details</th>
                    <th style={{ padding: '10px 8px' }}>Category</th>
                    <th style={{ padding: '10px 8px' }}>Author</th>
                    <th style={{ padding: '10px 8px' }}>Views (Unique)</th>
                    <th style={{ padding: '10px 8px' }}>Live View</th>
                    <th style={{ padding: '10px 8px', textAlign: 'right' }}>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {(blogPageData.items || []).slice((articlesPage - 1) * articlesPageSize, articlesPage * articlesPageSize).map((item, idx) => {
                    const globalIdx = (articlesPage - 1) * articlesPageSize + idx;
                    return (
                      <tr key={item._id || item.slug || globalIdx} style={{ borderBottom: '1px solid #F2F5FA' }}>
                        <td style={{ padding: '10px 8px' }}>
                          <div style={{ width: '56px', height: '44px', borderRadius: '6px', overflow: 'hidden', backgroundColor: '#f2f2f2' }}>
                            <img src={item.image || '/assets/img/blog/blog-in-01.jpg'} alt="Blog cover" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
                          </div>
                        </td>
                        <td style={{ padding: '10px 8px' }}>
                          <strong style={{ color: '#171151', display: 'block', fontSize: '14px', marginBottom: '2px' }}>{item.title}</strong>
                          <span style={{ fontSize: '11px', color: '#8A879F' }}>
                            Slug: /blog/{item.slug} • {item.dateMonth} {item.dateDay}, {item.dateYear || '2026'}
                          </span>
                        </td>
                        <td style={{ padding: '10px 8px', color: '#6b6b6b' }}>{item.category}</td>
                        <td style={{ padding: '10px 8px', color: '#171151', fontWeight: '600' }}>{item.author || 'Innotech Editorial'}</td>
                        <td style={{ padding: '10px 8px' }}>
                          <span
                            style={{
                              display: 'inline-flex',
                              alignItems: 'center',
                              gap: '4px',
                              padding: '3px 8px',
                              borderRadius: '6px',
                              backgroundColor: '#EFF6FF',
                              color: '#0E63FF',
                              fontWeight: '700',
                              fontSize: '12px',
                            }}
                          >
                            <Eye size={12} />
                            {typeof item.views === 'number' ? item.views : parseInt(item.views) || 0}
                          </span>
                        </td>
                        <td style={{ padding: '10px 8px' }}>
                          <a
                            href={`/blog/${item.slug}`}
                            target="_blank"
                            rel="noreferrer"
                            style={{ display: 'inline-flex', alignItems: 'center', gap: '4px', color: '#0E63FF', textDecoration: 'none', fontWeight: '600', fontSize: '12px' }}
                          >
                            <span>Preview</span>
                            <ExternalLink size={12} />
                          </a>
                        </td>
                        <td style={{ padding: '10px 8px', textAlign: 'right' }}>
                          <div style={{ display: 'inline-flex', gap: '6px' }}>
                            <button
                              type="button"
                              onClick={() => toggleBlogEnabled(item._id || item.slug)}
                              style={{
                                padding: '6px 10px',
                                backgroundColor: item.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                                color: item.enabled !== false ? '#0b9748' : '#F72A75',
                                border: 'none',
                                borderRadius: '6px',
                                cursor: 'pointer',
                                display: 'inline-flex',
                                alignItems: 'center',
                                gap: '4px',
                                fontSize: '12px',
                                fontWeight: '600',
                              }}
                            >
                              {item.enabled !== false ? <Eye size={13} /> : <EyeOff size={13} />}
                              <span>{item.enabled !== false ? 'Live' : 'Hidden'}</span>
                            </button>
                            <button
                              type="button"
                              onClick={() =>
                                setEditModal({
                                  open: true,
                                  data: {
                                    ...item,
                                    tags: Array.isArray(item.tags) ? item.tags.join(', ') : item.tags,
                                  },
                                })
                              }
                              style={{ display: 'inline-flex', alignItems: 'center', gap: '4px', padding: '6px 12px', backgroundColor: '#EFF6FF', color: '#0E63FF', border: '1px solid #BFDBFE', borderRadius: '6px', fontSize: '12px', fontWeight: '600', cursor: 'pointer' }}
                            >
                              <Edit2 size={13} /> Edit
                            </button>
                            <button
                              type="button"
                              onClick={() => setDeleteModal({ open: true, id: item._id || item.slug, title: item.title })}
                              style={{ padding: '6px 10px', backgroundColor: '#feeaf1', color: '#F72A75', border: '1px solid #FECDD3', borderRadius: '6px', cursor: 'pointer' }}
                            >
                              <Trash2 size={14} />
                            </button>
                          </div>
                        </td>
                      </tr>
                    );
                  })}
                </tbody>
              </table>
            </div>

            {/* Articles Pagination Bar */}
            {(blogPageData.items || []).length > articlesPageSize && (
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
                  Showing {(articlesPage - 1) * articlesPageSize + 1} to {Math.min(articlesPage * articlesPageSize, (blogPageData.items || []).length)} of {(blogPageData.items || []).length} articles
                </span>
                <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                  <button
                    type="button"
                    disabled={articlesPage === 1}
                    onClick={() => setArticlesPage((p) => Math.max(p - 1, 1))}
                    style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      border: '1px solid #D1D6E0',
                      backgroundColor: articlesPage === 1 ? '#F8FAFC' : '#ffffff',
                      color: articlesPage === 1 ? '#CBD5E1' : '#171151',
                      cursor: articlesPage === 1 ? 'not-allowed' : 'pointer',
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
                    Page {articlesPage} of {Math.ceil((blogPageData.items || []).length / articlesPageSize) || 1}
                  </span>
                  <button
                    type="button"
                    disabled={articlesPage >= Math.ceil((blogPageData.items || []).length / articlesPageSize)}
                    onClick={() => setArticlesPage((p) => Math.min(p + 1, Math.ceil((blogPageData.items || []).length / articlesPageSize)))}
                    style={{
                      padding: '6px 12px',
                      borderRadius: '6px',
                      border: '1px solid #D1D6E0',
                      backgroundColor: articlesPage >= Math.ceil((blogPageData.items || []).length / articlesPageSize) ? '#F8FAFC' : '#ffffff',
                      color: articlesPage >= Math.ceil((blogPageData.items || []).length / articlesPageSize) ? '#CBD5E1' : '#171151',
                      cursor: articlesPage >= Math.ceil((blogPageData.items || []).length / articlesPageSize) ? 'not-allowed' : 'pointer',
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
        </>
      )}

      {/* TAB 2: COMMENTS MODERATION */}
      {activeTab === 'comments' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          {/* Header */}
          <div
            style={{
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              flexWrap: 'wrap',
              gap: '15px',
              marginBottom: '24px',
              paddingBottom: '16px',
              borderBottom: '1px solid #ECEEF3',
            }}
          >
            <div>
              <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: '0 0 4px', display: 'flex', alignItems: 'center', gap: '8px' }}>
                <MessageSquare size={20} color="#0E63FF" />
                <span>Blog Reader Comments Moderation</span>
              </h3>
              <p style={{ fontSize: '13px', color: '#6b6b6b', margin: 0 }}>
                Review incoming reader comments. Only comments marked as <strong>Approved</strong> are visible publicly on blog articles.
              </p>
            </div>

            <button
              onClick={fetchComments}
              disabled={loadingComments}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '8px 16px',
                borderRadius: '8px',
                backgroundColor: '#EFF6FF',
                color: '#0E63FF',
                border: '1px solid #BFDBFE',
                fontWeight: '600',
                fontSize: '13px',
                cursor: 'pointer',
              }}
            >
              <RefreshCw size={14} className={loadingComments ? 'animate-spin' : ''} />
              <span>{loadingComments ? 'Refreshing...' : 'Refresh Comments'}</span>
            </button>
          </div>

          {/* Filter Tabs & Search */}
          <div
            style={{
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              flexWrap: 'wrap',
              gap: '15px',
              marginBottom: '20px',
            }}
          >
            <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
              {[
                { key: 'all', label: 'All Comments', count: commentCounts.total, color: '#171151', bg: '#F1F5F9' },
                { key: 'pending', label: 'Pending Approval', count: commentCounts.pending, color: '#D97706', bg: '#FEF3C7' },
                { key: 'approved', label: 'Approved & Live', count: commentCounts.approved, color: '#0B9748', bg: '#E7FAF6' },
                { key: 'rejected', label: 'Rejected / Spam', count: commentCounts.rejected, color: '#EF4444', bg: '#FEE2E2' },
              ].map((f) => {
                const isSelected = commentFilter === f.key;
                return (
                  <button
                    key={f.key}
                    onClick={() => setCommentFilter(f.key)}
                    style={{
                      display: 'inline-flex',
                      alignItems: 'center',
                      gap: '8px',
                      padding: '8px 14px',
                      borderRadius: '20px',
                      border: isSelected ? `2px solid ${f.color}` : '1px solid #E2E8F0',
                      backgroundColor: isSelected ? f.bg : '#ffffff',
                      color: isSelected ? f.color : '#64748B',
                      fontSize: '13px',
                      fontWeight: isSelected ? '700' : '500',
                      cursor: 'pointer',
                      transition: 'all 0.2s',
                    }}
                  >
                    <span>{f.label}</span>
                    <span
                      style={{
                        padding: '2px 8px',
                        borderRadius: '10px',
                        backgroundColor: isSelected ? f.color : '#E2E8F0',
                        color: isSelected ? '#ffffff' : '#475569',
                        fontSize: '11px',
                        fontWeight: '700',
                      }}
                    >
                      {f.count || 0}
                    </span>
                  </button>
                );
              })}
            </div>

            <div style={{ position: 'relative', minWidth: '260px', flex: '1', maxWidth: '380px' }}>
              <Search
                size={16}
                style={{ position: 'absolute', left: '12px', top: '50%', transform: 'translateY(-50%)', color: '#94A3B8' }}
              />
              <input
                type="text"
                placeholder="Search author, email, comment, blog..."
                value={commentSearch}
                onChange={(e) => setCommentSearch(e.target.value)}
                style={{
                  width: '100%',
                  padding: '9px 12px 9px 36px',
                  borderRadius: '8px',
                  border: '1px solid #CBD5E1',
                  fontSize: '13px',
                  boxSizing: 'border-box',
                }}
              />
            </div>
          </div>

          {/* Comments List */}
          {loadingComments && comments.length === 0 ? (
            <div style={{ padding: '60px 20px', textAlign: 'center', color: '#64748B' }}>
              <RefreshCw size={30} className="animate-spin" style={{ margin: '0 auto 10px', color: '#0E63FF' }} />
              <p style={{ fontWeight: '600' }}>Loading comments...</p>
            </div>
          ) : comments.filter((c) => (commentFilter === 'all' ? true : c.status === commentFilter)).length === 0 ? (
            <div
              style={{
                padding: '60px 20px',
                textAlign: 'center',
                backgroundColor: '#F8FAFC',
                borderRadius: '10px',
                border: '1px dashed #CBD5E1',
              }}
            >
              <MessageSquare size={40} style={{ margin: '0 auto 12px', color: '#94A3B8' }} />
              <h4 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', margin: '0 0 6px' }}>
                No comments found
              </h4>
              <p style={{ fontSize: '13px', color: '#64748B', margin: 0 }}>
                {commentSearch ? 'No comments match your search criteria.' : 'There are no comments in this category.'}
              </p>
            </div>
          ) : (
            <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
              {(() => {
                const filteredCommentsList = comments.filter((c) => {
                  const matchFilter = commentFilter === 'all' ? true : c.status === commentFilter;
                  const matchSearch =
                    commentSearch === '' ||
                    (c.name && c.name.toLowerCase().includes(commentSearch.toLowerCase())) ||
                    (c.email && c.email.toLowerCase().includes(commentSearch.toLowerCase())) ||
                    (c.comment && c.comment.toLowerCase().includes(commentSearch.toLowerCase())) ||
                    (c.blogTitle && c.blogTitle.toLowerCase().includes(commentSearch.toLowerCase())) ||
                    (c.blogSlug && c.blogSlug.toLowerCase().includes(commentSearch.toLowerCase()));
                  return matchFilter && matchSearch;
                });

                const paginatedComments = filteredCommentsList.slice(
                  (commentsPage - 1) * commentsPageSize,
                  commentsPage * commentsPageSize
                );
                const totalCommentPages = Math.ceil(filteredCommentsList.length / commentsPageSize) || 1;

                return (
                  <>
                    {paginatedComments.map((c) => (
                      <div
                        key={c._id}
                        style={{
                          borderRadius: '10px',
                          border: c.status === 'pending' ? '1.5px solid #FCD34D' : '1px solid #E2E8F0',
                          backgroundColor: c.status === 'pending' ? '#FFFDF5' : '#ffffff',
                          padding: '20px',
                          boxShadow: '0 2px 8px rgba(0,0,0,0.02)',
                        }}
                      >
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', flexWrap: 'wrap', gap: '12px', marginBottom: '12px' }}>
                          <div>
                            <div style={{ display: 'flex', alignItems: 'center', gap: '10px', flexWrap: 'wrap' }}>
                              <span style={{ fontSize: '15px', fontWeight: '700', color: '#171151' }}>
                                {c.name}
                              </span>
                              <span style={{ fontSize: '12px', color: '#64748B' }}>
                                &lt;{c.email}&gt;
                              </span>
                              <span
                                style={{
                                  fontSize: '11px',
                                  fontWeight: '700',
                                  padding: '2px 8px',
                                  borderRadius: '10px',
                                  textTransform: 'uppercase',
                                  backgroundColor:
                                    c.status === 'approved'
                                      ? '#E7FAF6'
                                      : c.status === 'pending'
                                      ? '#FEF3C7'
                                      : '#FEE2E2',
                                  color:
                                    c.status === 'approved'
                                      ? '#0B9748'
                                      : c.status === 'pending'
                                      ? '#D97706'
                                      : '#EF4444',
                                }}
                              >
                                {c.status || 'pending'}
                              </span>
                            </div>

                            <div style={{ fontSize: '12px', color: '#0E63FF', fontWeight: '600', marginTop: '4px' }}>
                              Article: <a href={`/blog/${c.blogSlug}`} target="_blank" rel="noreferrer" style={{ color: '#0E63FF', textDecoration: 'none' }}>{c.blogTitle || c.blogSlug} &rarr;</a>
                            </div>
                          </div>

                          <div style={{ fontSize: '12px', color: '#94A3B8' }}>
                            {c.createdAt ? new Date(c.createdAt).toLocaleString() : ''}
                          </div>
                        </div>

                        <div
                          style={{
                            padding: '14px 16px',
                            backgroundColor: '#F8FAFC',
                            borderRadius: '8px',
                            fontSize: '14px',
                            color: '#1E293B',
                            lineHeight: '1.6',
                            marginBottom: '16px',
                            border: '1px solid #F1F5F9',
                            whiteSpace: 'pre-wrap',
                          }}
                        >
                          {c.comment}
                        </div>

                        {/* Action Bar */}
                        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '10px' }}>
                          <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
                            {c.status !== 'approved' && (
                              <button
                                onClick={() => handleModerateComment(c._id, 'approved')}
                                style={{
                                  display: 'inline-flex',
                                  alignItems: 'center',
                                  gap: '6px',
                                  padding: '7px 14px',
                                  borderRadius: '6px',
                                  backgroundColor: '#0B9748',
                                  color: '#ffffff',
                                  border: 'none',
                                  fontSize: '12px',
                                  fontWeight: '700',
                                  cursor: 'pointer',
                                }}
                              >
                                <Check size={14} />
                                <span>Approve & Publish Live</span>
                              </button>
                            )}

                            {c.status !== 'rejected' && (
                              <button
                                onClick={() => handleModerateComment(c._id, 'rejected')}
                                style={{
                                  display: 'inline-flex',
                                  alignItems: 'center',
                                  gap: '6px',
                                  padding: '7px 14px',
                                  borderRadius: '6px',
                                  backgroundColor: '#FFFBEB',
                                  color: '#D97706',
                                  border: '1px solid #FDE68A',
                                  fontSize: '12px',
                                  fontWeight: '600',
                                  cursor: 'pointer',
                                }}
                              >
                                <XCircle size={14} />
                                <span>Reject / Spam</span>
                              </button>
                            )}

                            {c.status !== 'pending' && (
                              <button
                                onClick={() => handleModerateComment(c._id, 'pending')}
                                style={{
                                  display: 'inline-flex',
                                  alignItems: 'center',
                                  gap: '6px',
                                  padding: '7px 14px',
                                  borderRadius: '6px',
                                  backgroundColor: '#F1F5F9',
                                  color: '#64748B',
                                  border: '1px solid #CBD5E1',
                                  fontSize: '12px',
                                  fontWeight: '600',
                                  cursor: 'pointer',
                                }}
                              >
                                <span>Move to Pending</span>
                              </button>
                            )}
                          </div>

                          <button
                            onClick={() => handleDeleteComment(c._id)}
                            style={{
                              display: 'inline-flex',
                              alignItems: 'center',
                              gap: '6px',
                              padding: '7px 14px',
                              borderRadius: '6px',
                              backgroundColor: '#FFF1F2',
                              color: '#E11D48',
                              border: '1px solid #FDA4AF',
                              fontSize: '12px',
                              fontWeight: '600',
                              cursor: 'pointer',
                            }}
                          >
                            <Trash2 size={13} />
                            <span>Delete</span>
                          </button>
                        </div>
                      </div>
                    ))}

                    {/* Comments Pagination Controls */}
                    {filteredCommentsList.length > commentsPageSize && (
                      <div
                        style={{
                          display: 'flex',
                          alignItems: 'center',
                          justifyContent: 'space-between',
                          paddingTop: '16px',
                          marginTop: '8px',
                          borderTop: '1px solid #F1F5F9',
                          flexWrap: 'wrap',
                          gap: '12px',
                        }}
                      >
                        <span style={{ fontSize: '13px', color: '#64748B' }}>
                          Showing {(commentsPage - 1) * commentsPageSize + 1} to {Math.min(commentsPage * commentsPageSize, filteredCommentsList.length)} of {filteredCommentsList.length} comments
                        </span>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                          <button
                            type="button"
                            disabled={commentsPage === 1}
                            onClick={() => setCommentsPage((p) => Math.max(p - 1, 1))}
                            style={{
                              padding: '6px 12px',
                              borderRadius: '6px',
                              border: '1px solid #D1D6E0',
                              backgroundColor: commentsPage === 1 ? '#F8FAFC' : '#ffffff',
                              color: commentsPage === 1 ? '#CBD5E1' : '#171151',
                              cursor: commentsPage === 1 ? 'not-allowed' : 'pointer',
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
                            Page {commentsPage} of {totalCommentPages}
                          </span>
                          <button
                            type="button"
                            disabled={commentsPage >= totalCommentPages}
                            onClick={() => setCommentsPage((p) => Math.min(p + 1, totalCommentPages))}
                            style={{
                              padding: '6px 12px',
                              borderRadius: '6px',
                              border: '1px solid #D1D6E0',
                              backgroundColor: commentsPage >= totalCommentPages ? '#F8FAFC' : '#ffffff',
                              color: commentsPage >= totalCommentPages ? '#CBD5E1' : '#171151',
                              cursor: commentsPage >= totalCommentPages ? 'not-allowed' : 'pointer',
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
                  </>
                );
              })()}
            </div>
          )}
        </div>
      )}

      {/* Edit Modal */}
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
          }}
          onClick={() => setEditModal({ open: false, data: null })}
        >
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '16px',
              maxWidth: '650px',
              width: '100%',
              maxHeight: '90vh',
              overflowY: 'auto',
              padding: '28px',
              position: 'relative',
            }}
            onClick={(e) => e.stopPropagation()}
          >
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px', borderBottom: '1px solid #ECEEF3', paddingBottom: '14px' }}>
              <h3 style={{ fontSize: '18px', fontWeight: '800', color: '#171151', margin: 0 }}>
                Edit Medical Article
              </h3>
              <button
                type="button"
                onClick={() => setEditModal({ open: false, data: null })}
                style={{ background: '#F1F5F9', border: 'none', borderRadius: '50%', width: '32px', height: '32px', display: 'flex', alignItems: 'center', justifyContent: 'center', cursor: 'pointer' }}
              >
                <X size={16} />
              </button>
            </div>

            <form onSubmit={handleSaveModalEdit}>
              <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Title</label>
                  <input
                    type="text"
                    value={editModal.data.title || ''}
                    onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, title: e.target.value } })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '8px', border: '1px solid #CBD5E1', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Category</label>
                  <input
                    type="text"
                    value={editModal.data.category || ''}
                    onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, category: e.target.value } })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '8px', border: '1px solid #CBD5E1', boxSizing: 'border-box' }}
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Author Name</label>
                  <input
                    type="text"
                    placeholder="e.g. Engr. Faisal Malik"
                    value={editModal.data.author || ''}
                    onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, author: e.target.value } })}
                    style={{ width: '100%', padding: '10px', borderRadius: '8px', border: '1px solid #CBD5E1', boxSizing: 'border-box' }}
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
                      style={{ width: '50%', padding: '10px', borderRadius: '8px', border: '1px solid #CBD5E1', boxSizing: 'border-box' }}
                    />
                    <input
                      type="text"
                      placeholder="Day (e.g. 14)"
                      value={editModal.data.dateDay || ''}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, dateDay: e.target.value } })}
                      style={{ width: '50%', padding: '10px', borderRadius: '8px', border: '1px solid #CBD5E1', boxSizing: 'border-box' }}
                    />
                  </div>
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>URL Slug</label>
                  <input
                    type="text"
                    value={editModal.data.slug || ''}
                    onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, slug: e.target.value } })}
                    style={{ width: '100%', padding: '10px', borderRadius: '8px', border: '1px solid #CBD5E1', boxSizing: 'border-box' }}
                  />
                </div>
                <FileUploadField
                  label="Cover Image"
                  value={editModal.data.image || ''}
                  onChange={(url) => setEditModal({ ...editModal, data: { ...editModal.data, image: url } })}
                />
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Tags (comma separated)</label>
                  <input
                    type="text"
                    value={editModal.data.tags || ''}
                    onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, tags: e.target.value } })}
                    style={{ width: '100%', padding: '10px', borderRadius: '8px', border: '1px solid #CBD5E1', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Excerpt / Summary</label>
                  <textarea
                    rows={2}
                    value={editModal.data.excerpt || ''}
                    onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, excerpt: e.target.value } })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '8px', border: '1px solid #CBD5E1', boxSizing: 'border-box' }}
                  ></textarea>
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Full Article Body</label>
                  <textarea
                    rows={6}
                    value={editModal.data.content || ''}
                    onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, content: e.target.value } })}
                    style={{ width: '100%', padding: '10px', borderRadius: '8px', border: '1px solid #CBD5E1', boxSizing: 'border-box' }}
                  ></textarea>
                </div>

                {/* Social Media Links in Edit Modal */}
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

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '10px', marginTop: '24px' }}>
                <button
                  type="button"
                  onClick={() => setEditModal({ open: false, data: null })}
                  style={{ padding: '10px 18px', backgroundColor: '#F1F5F9', color: '#475569', border: 'none', borderRadius: '8px', fontWeight: '600', cursor: 'pointer' }}
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  style={{ padding: '10px 22px', backgroundColor: '#0E63FF', color: '#fff', border: 'none', borderRadius: '8px', fontWeight: '600', cursor: 'pointer' }}
                >
                  Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Delete Popup */}
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
          }}
          onClick={() => setDeleteModal({ open: false, id: null, title: '' })}
        >
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '16px',
              maxWidth: '460px',
              width: '100%',
              padding: '28px',
              textAlign: 'center',
            }}
            onClick={(e) => e.stopPropagation()}
          >
            <div style={{ width: '56px', height: '56px', borderRadius: '50%', backgroundColor: '#FEEAF1', color: '#F72A75', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 16px' }}>
              <AlertTriangle size={28} />
            </div>
            <h3 style={{ fontSize: '19px', fontWeight: '800', color: '#171151', margin: '0 0 8px' }}>
              Confirm Deletion
            </h3>
            <p style={{ fontSize: '14px', color: '#64748B', margin: '0 0 24px' }}>
              Are you sure you want to delete <strong style={{ color: '#171151' }}>&ldquo;{deleteModal.title}&rdquo;</strong>?
            </p>
            <div style={{ display: 'flex', gap: '12px' }}>
              <button
                type="button"
                onClick={() => setDeleteModal({ open: false, id: null, title: '' })}
                style={{ flex: 1, padding: '11px 18px', backgroundColor: '#F1F5F9', color: '#475569', border: 'none', borderRadius: '8px', fontWeight: '600', cursor: 'pointer' }}
              >
                Cancel
              </button>
              <button
                type="button"
                onClick={handleConfirmDelete}
                style={{ flex: 1, padding: '11px 18px', backgroundColor: '#F72A75', color: '#fff', border: 'none', borderRadius: '8px', fontWeight: '600', cursor: 'pointer' }}
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

'use client';

import React, { useState, useEffect, useRef } from 'react';
import {
  Save,
  Plus,
  Trash2,
  Edit2,
  CheckCircle2,
  FlaskConical,
  Eye,
  EyeOff,
  X,
  AlertTriangle,
  UploadCloud,
  LayoutTemplate,
} from 'lucide-react';
import { fallbackResearchPage } from '@/lib/data';

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

export default function AdminResearchManager() {
  const [researchData, setResearchData] = useState(fallbackResearchPage);
  const [newProject, setNewProject] = useState({
    title: '',
    category: 'Diagnostic Systems',
    description: '',
    image: '/assets/img/research/research-thumb-01.jpg',
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

  useEffect(() => {
    fetchData();
  }, []);

  const showToast = (msg, type = 'success') => {
    setSaveStatus({ type, msg });
    setTimeout(() => {
      setSaveStatus((prev) => (prev.msg === msg ? { type: '', msg: '' } : prev));
    }, 4500);
  };

  const fetchData = async () => {
    try {
      const res = await fetch('/api/admin/research');
      const data = await res.json();
      if (data.data) {
        setResearchData(data.data);
      }
    } catch (e) {
      console.error('Error fetching research data:', e);
    }
  };

  const syncData = async (updated) => {
    try {
      await fetch('/api/admin/research', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updated || researchData),
      });
    } catch (err) {
      console.error(err);
    }
  };

  const handleBannerChange = (field, value) => {
    setResearchData((prev) => {
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

  const handleAddProject = (e) => {
    e.preventDefault();
    if (!newProject.title) return;
    const item = { ...newProject, id: Date.now() };
    const updated = {
      ...researchData,
      items: [item, ...(researchData.items || [])],
    };
    setResearchData(updated);
    syncData(updated);
    setNewProject({
      title: '',
      category: 'Diagnostic Systems',
      description: '',
      image: '/assets/img/research/research-thumb-01.jpg',
      enabled: true,
    });
    showToast(`Project "${item.title}" added successfully!`);
  };

  const toggleProjectEnabled = (id) => {
    const updatedItems = (researchData.items || []).map((p) =>
      p.id === id ? { ...p, enabled: p.enabled === false ? true : false } : p
    );
    const updated = { ...researchData, items: updatedItems };
    setResearchData(updated);
    syncData(updated);
    const target = updatedItems.find((p) => p.id === id);
    showToast(`Project "${target?.title}" is now ${target?.enabled ? 'Active on Live Site' : 'Disabled'}!`);
  };

  const handleSaveModalEdit = (e) => {
    e.preventDefault();
    if (!editModal.data) return;
    const updatedItems = (researchData.items || []).map((p) =>
      p.id === editModal.data.id ? editModal.data : p
    );
    const updated = { ...researchData, items: updatedItems };
    setResearchData(updated);
    syncData(updated);
    showToast(`Changes for "${editModal.data.title}" saved successfully!`);
    setEditModal({ open: false, data: null });
  };

  const handleConfirmDelete = () => {
    const { id, title } = deleteModal;
    const updatedItems = (researchData.items || []).filter((p) => p.id !== id);
    const updated = { ...researchData, items: updatedItems };
    setResearchData(updated);
    syncData(updated);
    showToast(`Project "${title}" deleted successfully!`);
    setDeleteModal({ open: false, id: null, title: '' });
  };

  const handleSaveAll = async (e) => {
    if (e) e.preventDefault();
    setSaving(true);
    try {
      const res = await fetch('/api/admin/research', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(researchData),
      });
      const data = await res.json();
      if (res.ok) {
        showToast('Research projects saved and published live!');
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
            <span>Research Projects Manager</span>
            <span style={{ fontSize: '12px', fontWeight: '600', backgroundColor: '#EFF6FF', color: '#0E63FF', padding: '4px 10px', borderRadius: '20px' }}>
              Dynamic & Live
            </span>
          </h1>
          <p style={{ fontSize: '14px', color: '#6b6b6b', margin: 0 }}>
            Manage clinical research initiatives, project thumbnails, categories, and live visibility for /research.
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
          <span>{saving ? 'Saving...' : 'Save & Publish Research'}</span>
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

      {/* Top Banner Settings */}
      <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
        <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
          <LayoutTemplate size={18} color="#0E63FF" /> Research Page Top Banner
        </h3>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
          <div>
            <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Main Title</label>
            <input
              type="text"
              value={researchData.banner?.title || ''}
              onChange={(e) => handleBannerChange('title', e.target.value)}
              style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
            />
          </div>
          <div>
            <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Subtitle</label>
            <input
              type="text"
              value={researchData.banner?.subTitle || ''}
              onChange={(e) => handleBannerChange('subTitle', e.target.value)}
              style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
            />
          </div>
          <div style={{ gridColumn: '1 / -1' }}>
            <FileUploadField
              label="Banner Background Image"
              value={researchData.banner?.bgImage || ''}
              onChange={(url) => handleBannerChange('bgImage', url)}
              placeholder="/assets/img/banner/breadcrumb-01.jpg"
            />
          </div>
        </div>
      </div>

      {/* Add Project Form */}
      <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
        <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
          <Plus size={18} color="#0E63FF" /> Add New Research Project
        </h3>
        <form onSubmit={handleAddProject}>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '16px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Project Title</label>
              <input
                type="text"
                placeholder="e.g. Next-Gen ICU Monitoring Systems"
                value={newProject.title}
                onChange={(e) => setNewProject({ ...newProject, title: e.target.value })}
                required
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Scientific Category</label>
              <input
                type="text"
                placeholder="e.g. Diagnostic Systems"
                value={newProject.category}
                onChange={(e) => setNewProject({ ...newProject, category: e.target.value })}
                required
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
            <div style={{ gridColumn: '1 / -1' }}>
              <FileUploadField
                label="Project Thumbnail Image (Upload Local Image or Enter Path)"
                value={newProject.image}
                onChange={(url) => setNewProject({ ...newProject, image: url })}
                placeholder="/assets/img/research/research-thumb-01.jpg"
              />
            </div>
            <div style={{ gridColumn: '1 / -1' }}>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Project Summary / Objective</label>
              <textarea
                rows={2}
                placeholder="Describe research findings or technology specs..."
                value={newProject.description}
                onChange={(e) => setNewProject({ ...newProject, description: e.target.value })}
                required
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              ></textarea>
            </div>
          </div>
          <button type="submit" style={{ marginTop: '16px', padding: '10px 20px', backgroundColor: '#0E63FF', color: '#fff', borderRadius: '6px', border: 'none', fontWeight: '600', cursor: 'pointer' }}>
            + Add Research Project
          </button>
        </form>
      </div>

      {/* Projects Table */}
      <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
        <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px' }}>
          Research Projects List ({(researchData.items || []).length})
        </h3>
        <div style={{ overflowX: 'auto' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '13px' }}>
            <thead>
              <tr style={{ color: '#8A879F', borderBottom: '1px solid #ECEEF3', textAlign: 'left' }}>
                <th style={{ padding: '12px 10px', width: '80px' }}>Thumbnail</th>
                <th style={{ padding: '12px 10px' }}>Title</th>
                <th style={{ padding: '12px 10px' }}>Category</th>
                <th style={{ padding: '12px 10px' }}>Description</th>
                <th style={{ padding: '12px 10px' }}>Status</th>
                <th style={{ padding: '12px 10px', textAlign: 'right' }}>Actions</th>
              </tr>
            </thead>
            <tbody>
              {(researchData.items || []).map((item) => (
                <tr key={item.id} style={{ borderBottom: '1px solid #F2F5FA' }}>
                  <td style={{ padding: '10px' }}>
                    <img src={item.image} alt={item.title} style={{ width: '60px', height: '45px', objectFit: 'cover', borderRadius: '4px' }} />
                  </td>
                  <td style={{ padding: '12px 10px', fontWeight: '700', color: '#171151' }}>{item.title}</td>
                  <td style={{ padding: '12px 10px', color: '#0E63FF', fontWeight: '600' }}>{item.category}</td>
                  <td style={{ padding: '12px 10px', color: '#6b6b6b', maxWidth: '300px' }}>{item.description}</td>
                  <td style={{ padding: '12px 10px' }}>
                    <button
                      type="button"
                      onClick={() => toggleProjectEnabled(item.id)}
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
                        onClick={() => setEditModal({ open: true, data: { ...item } })}
                        style={{ display: 'inline-flex', alignItems: 'center', gap: '4px', padding: '6px 12px', backgroundColor: '#EFF6FF', color: '#0E63FF', border: '1px solid #BFDBFE', borderRadius: '6px', fontSize: '12px', fontWeight: '600', cursor: 'pointer' }}
                      >
                        <Edit2 size={13} /> Edit
                      </button>
                      <button
                        type="button"
                        onClick={() => setDeleteModal({ open: true, id: item.id, title: item.title })}
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
      </div>

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
              maxWidth: '600px',
              width: '100%',
              padding: '28px',
              position: 'relative',
            }}
            onClick={(e) => e.stopPropagation()}
          >
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px', borderBottom: '1px solid #ECEEF3', paddingBottom: '14px' }}>
              <h3 style={{ fontSize: '18px', fontWeight: '800', color: '#171151', margin: 0 }}>
                Edit Research Project
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
                <FileUploadField
                  label="Thumbnail Image"
                  value={editModal.data.image || ''}
                  onChange={(url) => setEditModal({ ...editModal, data: { ...editModal.data, image: url } })}
                />
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Description</label>
                  <textarea
                    rows={3}
                    value={editModal.data.description || ''}
                    onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, description: e.target.value } })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '8px', border: '1px solid #CBD5E1', boxSizing: 'border-box' }}
                  ></textarea>
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

'use client';

import React, { useState, useEffect } from 'react';
import {
  Save,
  Plus,
  Trash2,
  Edit2,
  CheckCircle2,
  Eye,
  EyeOff,
  X,
  AlertTriangle,
  Menu as MenuIcon,
  PhoneCall,
  ChevronDown,
  ChevronUp,
  Layers,
  RotateCcw,
  RefreshCw,
  ArrowUp,
  ArrowDown,
  Home,
  FileText,
} from 'lucide-react';
import { fallbackHeaderConfig } from '@/lib/data';

export default function AdminHeaderManager() {
  const [headerConfig, setHeaderConfig] = useState(fallbackHeaderConfig);
  const [newMenuItem, setNewMenuItem] = useState({
    label: '',
    link: '/',
    enabled: true,
    showOnHome: true,
    showOnInner: true,
    hasDropdown: false,
    subItems: [],
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
      const res = await fetch('/api/admin/header');
      const data = await res.json();
      if (data.data) {
        setHeaderConfig(data.data);
      }
    } catch (e) {
      console.error('Error fetching header config:', e);
    }
  };

  const syncData = async (updated) => {
    try {
      await fetch('/api/admin/header', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updated || headerConfig),
      });
    } catch (err) {
      console.error(err);
    }
  };

  const toggleShowOnHome = (id) => {
    const updatedItems = (headerConfig.menuItems || []).map((item) => {
      if (item.id === id) {
        const current = typeof item.showOnHome !== 'undefined' ? item.showOnHome : (item.enabled !== false);
        return { ...item, showOnHome: !current };
      }
      return item;
    });

    const updated = { ...headerConfig, menuItems: updatedItems };
    setHeaderConfig(updated);
    syncData(updated);

    const target = updatedItems.find((i) => i.id === id);
    showToast(`"${target?.label}" is now ${target?.showOnHome ? 'Visible on Home Header' : 'Hidden from Home Header'}!`);
  };

  const toggleShowOnInner = (id) => {
    const updatedItems = (headerConfig.menuItems || []).map((item) => {
      if (item.id === id) {
        const current = typeof item.showOnInner !== 'undefined' ? item.showOnInner : (item.enabled !== false);
        return { ...item, showOnInner: !current };
      }
      return item;
    });

    const updated = { ...headerConfig, menuItems: updatedItems };
    setHeaderConfig(updated);
    syncData(updated);

    const target = updatedItems.find((i) => i.id === id);
    showToast(`"${target?.label}" is now ${target?.showOnInner ? 'Visible on Inner Pages Header' : 'Hidden from Inner Pages Header'}!`);
  };

  const moveItem = (index, direction) => {
    const items = [...(headerConfig.menuItems || [])];
    const targetIdx = direction === 'up' ? index - 1 : index + 1;
    if (targetIdx < 0 || targetIdx >= items.length) return;

    const temp = items[index];
    items[index] = items[targetIdx];
    items[targetIdx] = temp;

    const updated = { ...headerConfig, menuItems: items };
    setHeaderConfig(updated);
    syncData(updated);
    showToast('Menu order updated!');
  };

  const handleAddMenuItem = (e) => {
    e.preventDefault();
    if (!newMenuItem.label) return;

    const item = {
      ...newMenuItem,
      id: Date.now().toString(),
    };

    const updated = {
      ...headerConfig,
      menuItems: [...(headerConfig.menuItems || []), item],
    };

    setHeaderConfig(updated);
    syncData(updated);
    setNewMenuItem({
      label: '',
      link: '/',
      enabled: true,
      showOnHome: true,
      showOnInner: true,
      hasDropdown: false,
      subItems: [],
    });
    showToast(`Menu link "${item.label}" added successfully!`);
  };

  const handleSaveModalEdit = (e) => {
    e.preventDefault();
    if (!editModal.data) return;

    const updatedItems = (headerConfig.menuItems || []).map((item) =>
      item.id === editModal.data.id ? editModal.data : item
    );

    const updated = { ...headerConfig, menuItems: updatedItems };
    setHeaderConfig(updated);
    syncData(updated);
    showToast(`Menu item "${editModal.data.label}" updated successfully!`);
    setEditModal({ open: false, data: null });
  };

  const handleConfirmDelete = () => {
    const { id, title } = deleteModal;
    const updatedItems = (headerConfig.menuItems || []).filter((item) => item.id !== id);
    const updated = { ...headerConfig, menuItems: updatedItems };
    setHeaderConfig(updated);
    syncData(updated);
    showToast(`Menu item "${title}" removed from header!`);
    setDeleteModal({ open: false, id: null, title: '' });
  };

  const handlePhoneChange = (val) => {
    const updated = { ...headerConfig, helpDeskPhone: val };
    setHeaderConfig(updated);
    syncData(updated);
  };

  const handleResetDefaults = () => {
    if (window.confirm('Reset all header navigation menus and dropdowns to complete default settings?')) {
      setHeaderConfig(fallbackHeaderConfig);
      syncData(fallbackHeaderConfig);
      showToast('Header navigation menus reset to default standard!');
    }
  };

  const handleSyncWithServices = async () => {
    try {
      const res = await fetch('/api/admin/services');
      const data = await res.json();
      if (data.data && data.data.length > 0) {
        const activeServices = data.data.filter((s) => s.enabled !== false && s.showInHeader !== false);
        const subItems = activeServices.map((s, idx) => ({
          id: `s-${s.slug || idx}`,
          label: s.title,
          link: `/services/${s.slug}`,
          enabled: true,
        }));

        subItems.push({
          id: 's-all',
          label: 'All Products & Services',
          link: '/services',
          enabled: true,
        });

        const updatedMenuItems = (headerConfig.menuItems || []).map((item) => {
          if (item.link === '/services' || item.label.toLowerCase().includes('services') || item.hasDropdown) {
            return {
              ...item,
              hasDropdown: true,
              subItems,
            };
          }
          return item;
        });

        const updated = { ...headerConfig, menuItems: updatedMenuItems };
        setHeaderConfig(updated);
        syncData(updated);
        showToast(`✓ "Products & Services" dropdown synchronized with ${activeServices.length} active service pages!`);
      }
    } catch (e) {
      showToast('Error syncing services dropdown', 'error');
    }
  };

  const handleSaveAll = async (e) => {
    if (e) e.preventDefault();
    setSaving(true);
    try {
      const res = await fetch('/api/admin/header', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(headerConfig),
      });
      const data = await res.json();
      if (res.ok) {
        showToast('✓ Header navigation menu and dropdowns published live!');
      } else {
        showToast(data.message || 'Error saving header settings', 'error');
      }
    } catch (err) {
      showToast('Network error saving header settings.', 'error');
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
            <span>Header Navigation &amp; Dropdown Menu Manager</span>
            <span style={{ fontSize: '12px', fontWeight: '600', backgroundColor: '#EFF6FF', color: '#0E63FF', padding: '4px 10px', borderRadius: '20px' }}>
              Live Customizer
            </span>
          </h1>
          <p style={{ fontSize: '14px', color: '#6b6b6b', margin: 0 }}>
            Configure exactly which menu items appear on the <strong>Home Page (Primary Header)</strong> vs <strong>Inner Pages (Secondary Header)</strong>.
          </p>
        </div>

        <div style={{ display: 'flex', gap: '10px', flexWrap: 'wrap' }}>
          <button
            type="button"
            onClick={handleSyncWithServices}
            style={{
              display: 'inline-flex',
              alignItems: 'center',
              gap: '6px',
              padding: '10px 18px',
              backgroundColor: '#EFF6FF',
              color: '#0E63FF',
              borderRadius: '8px',
              border: '1px solid #BFDBFE',
              fontWeight: '700',
              fontSize: '13px',
              cursor: 'pointer',
            }}
          >
            <RefreshCw size={15} />
            <span>Sync Services Dropdown</span>
          </button>

          <button
            type="button"
            onClick={handleResetDefaults}
            style={{
              display: 'inline-flex',
              alignItems: 'center',
              gap: '6px',
              padding: '10px 16px',
              backgroundColor: '#F8FAFC',
              color: '#64748B',
              borderRadius: '8px',
              border: '1px solid #CBD5E1',
              fontWeight: '700',
              fontSize: '13px',
              cursor: 'pointer',
            }}
          >
            <RotateCcw size={15} />
            <span>Reset Defaults</span>
          </button>

          <button
            onClick={handleSaveAll}
            disabled={saving}
            style={{
              display: 'inline-flex',
              alignItems: 'center',
              gap: '8px',
              padding: '10px 24px',
              backgroundColor: '#0b9748',
              color: '#ffffff',
              borderRadius: '8px',
              border: 'none',
              fontWeight: '700',
              fontSize: '13px',
              cursor: saving ? 'not-allowed' : 'pointer',
              boxShadow: '0 4px 12px rgba(11, 151, 72, 0.25)',
            }}
          >
            <Save size={16} />
            <span>{saving ? 'Saving...' : 'Save & Publish Header'}</span>
          </button>
        </div>
      </div>

      {/* Toast Alert */}
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

      {/* Help Desk Header Settings */}
      <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
        <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
          <PhoneCall size={18} color="#0E63FF" /> Header Helpline &amp; Support Box
        </h3>
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
          <div>
            <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Help Desk Phone Number</label>
            <input
              type="text"
              value={headerConfig.helpDeskPhone || ''}
              onChange={(e) => handlePhoneChange(e.target.value)}
              placeholder="+92 331 6699992"
              style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
            />
          </div>
        </div>
      </div>

      {/* Add New Menu Link Form */}
      <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', marginBottom: '20px' }}>
        <h3 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '16px', display: 'flex', alignItems: 'center', gap: '8px' }}>
          <Plus size={18} color="#0E63FF" /> Add New Header Menu Item
        </h3>
        <form onSubmit={handleAddMenuItem}>
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '16px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Menu Label / Name</label>
              <input
                type="text"
                placeholder="e.g. Careers, Gallery, or Downloads"
                value={newMenuItem.label}
                onChange={(e) => setNewMenuItem({ ...newMenuItem, label: e.target.value })}
                required
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Page URL / Link</label>
              <input
                type="text"
                placeholder="e.g. /gallery, /allteams, or /about"
                value={newMenuItem.link}
                onChange={(e) => setNewMenuItem({ ...newMenuItem, link: e.target.value })}
                required
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
            <div style={{ display: 'flex', flexDirection: 'column', gap: '8px', paddingTop: '8px' }}>
              <label style={{ display: 'flex', alignItems: 'center', gap: '8px', cursor: 'pointer', fontSize: '13px', fontWeight: '600', color: '#171151' }}>
                <input
                  type="checkbox"
                  checked={newMenuItem.showOnHome !== false}
                  onChange={(e) => setNewMenuItem({ ...newMenuItem, showOnHome: e.target.checked })}
                  style={{ width: '16px', height: '16px' }}
                />
                <span>Show on Home Header</span>
              </label>
              <label style={{ display: 'flex', alignItems: 'center', gap: '8px', cursor: 'pointer', fontSize: '13px', fontWeight: '600', color: '#171151' }}>
                <input
                  type="checkbox"
                  checked={newMenuItem.showOnInner !== false}
                  onChange={(e) => setNewMenuItem({ ...newMenuItem, showOnInner: e.target.checked })}
                  style={{ width: '16px', height: '16px' }}
                />
                <span>Show on Inner Header</span>
              </label>
            </div>
            <div style={{ display: 'flex', alignItems: 'center', paddingTop: '8px' }}>
              <label style={{ display: 'flex', alignItems: 'center', gap: '8px', cursor: 'pointer', fontSize: '13px', fontWeight: '600', color: '#0E63FF' }}>
                <input
                  type="checkbox"
                  checked={newMenuItem.hasDropdown}
                  onChange={(e) => setNewMenuItem({ ...newMenuItem, hasDropdown: e.target.checked })}
                  style={{ width: '16px', height: '16px' }}
                />
                <span>Enable Dropdown Submenu</span>
              </label>
            </div>
          </div>
          <button type="submit" style={{ marginTop: '16px', padding: '10px 20px', backgroundColor: '#0E63FF', color: '#fff', borderRadius: '6px', border: 'none', fontWeight: '600', cursor: 'pointer' }}>
            + Add Menu Link
          </button>
        </form>
      </div>

      {/* Menu Items Table / Grid */}
      <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3', boxShadow: '0 2px 8px rgba(0,0,0,0.03)' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '16px', flexWrap: 'wrap', gap: '10px' }}>
          <div>
            <h3 style={{ fontSize: '17px', fontWeight: '800', color: '#171151', margin: '0 0 4px' }}>
              Header Menu Navigation Links ({(headerConfig.menuItems || []).length})
            </h3>
            <p style={{ fontSize: '13px', color: '#64748B', margin: 0 }}>
              Use the switches below to customize which menus appear on the <strong>Home Header</strong> vs <strong>Inner Page Header</strong>.
            </p>
          </div>
        </div>

        <div style={{ overflowX: 'auto' }}>
          <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '13px' }}>
            <thead>
              <tr style={{ backgroundColor: '#F8FAFC', color: '#64748B', borderBottom: '2px solid #ECEEF3', textAlign: 'left' }}>
                <th style={{ padding: '14px 12px', width: '70px' }}>Order</th>
                <th style={{ padding: '14px 12px' }}>Menu Label &amp; URL</th>
                <th style={{ padding: '14px 12px' }}>Dropdown Type</th>
                <th style={{ padding: '14px 12px', textAlign: 'center' }}>Home Header (Primary)</th>
                <th style={{ padding: '14px 12px', textAlign: 'center' }}>Inner Header (Secondary)</th>
                <th style={{ padding: '14px 12px', textAlign: 'right' }}>Actions</th>
              </tr>
            </thead>
            <tbody>
              {(headerConfig.menuItems || []).map((item, idx) => {
                const isShownOnHome = item.showOnHome !== false && item.enabled !== false;
                const isShownOnInner = item.showOnInner !== false && item.enabled !== false;

                return (
                  <tr key={item.id || idx} style={{ borderBottom: '1px solid #F1F5F9', transition: 'background-color 0.15s' }}>
                    {/* Order */}
                    <td style={{ padding: '14px 12px' }}>
                      <div style={{ display: 'flex', gap: '4px', alignItems: 'center' }}>
                        <button
                          type="button"
                          onClick={() => moveItem(idx, 'up')}
                          disabled={idx === 0}
                          style={{ padding: '4px', borderRadius: '4px', border: '1px solid #CBD5E1', backgroundColor: idx === 0 ? '#F1F5F9' : '#ffffff', cursor: idx === 0 ? 'not-allowed' : 'pointer' }}
                        >
                          <ArrowUp size={12} />
                        </button>
                        <button
                          type="button"
                          onClick={() => moveItem(idx, 'down')}
                          disabled={idx === (headerConfig.menuItems || []).length - 1}
                          style={{ padding: '4px', borderRadius: '4px', border: '1px solid #CBD5E1', backgroundColor: idx === (headerConfig.menuItems || []).length - 1 ? '#F1F5F9' : '#ffffff', cursor: idx === (headerConfig.menuItems || []).length - 1 ? 'not-allowed' : 'pointer' }}
                        >
                          <ArrowDown size={12} />
                        </button>
                      </div>
                    </td>

                    {/* Label & URL */}
                    <td style={{ padding: '14px 12px' }}>
                      <div style={{ fontWeight: '700', color: '#171151', fontSize: '14px', marginBottom: '2px' }}>
                        {item.label}
                      </div>
                      <div style={{ color: '#64748B', fontFamily: 'monospace', fontSize: '12px' }}>
                        {item.link}
                      </div>
                    </td>

                    {/* Dropdown status */}
                    <td style={{ padding: '14px 12px' }}>
                      {item.hasDropdown ? (
                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: '4px', padding: '4px 10px', borderRadius: '6px', backgroundColor: '#EFF6FF', color: '#0E63FF', fontSize: '12px', fontWeight: '700' }}>
                          <ChevronDown size={13} /> {item.subItems?.length || 0} Sub-items
                        </span>
                      ) : (
                        <span style={{ color: '#94A3B8', fontSize: '12px' }}>Direct Link</span>
                      )}
                    </td>

                    {/* Home Header Toggle */}
                    <td style={{ padding: '14px 12px', textAlign: 'center' }}>
                      <button
                        type="button"
                        onClick={() => toggleShowOnHome(item.id)}
                        style={{
                          display: 'inline-flex',
                          alignItems: 'center',
                          gap: '6px',
                          padding: '6px 14px',
                          borderRadius: '20px',
                          border: isShownOnHome ? '1px solid #A7F3D0' : '1px solid #E2E8F0',
                          backgroundColor: isShownOnHome ? '#ECFDF5' : '#F8FAFC',
                          color: isShownOnHome ? '#059669' : '#94A3B8',
                          fontSize: '12px',
                          fontWeight: '700',
                          cursor: 'pointer',
                          transition: 'all 0.2s ease',
                        }}
                      >
                        {isShownOnHome ? (
                          <>
                            <CheckCircle2 size={13} />
                            <span>Shown on Home</span>
                          </>
                        ) : (
                          <>
                            <EyeOff size={13} />
                            <span>Hidden on Home</span>
                          </>
                        )}
                      </button>
                    </td>

                    {/* Inner Header Toggle */}
                    <td style={{ padding: '14px 12px', textAlign: 'center' }}>
                      <button
                        type="button"
                        onClick={() => toggleShowOnInner(item.id)}
                        style={{
                          display: 'inline-flex',
                          alignItems: 'center',
                          gap: '6px',
                          padding: '6px 14px',
                          borderRadius: '20px',
                          border: isShownOnInner ? '1px solid #BFDBFE' : '1px solid #E2E8F0',
                          backgroundColor: isShownOnInner ? '#EFF6FF' : '#F8FAFC',
                          color: isShownOnInner ? '#0E63FF' : '#94A3B8',
                          fontSize: '12px',
                          fontWeight: '700',
                          cursor: 'pointer',
                          transition: 'all 0.2s ease',
                        }}
                      >
                        {isShownOnInner ? (
                          <>
                            <CheckCircle2 size={13} />
                            <span>Shown on Inner</span>
                          </>
                        ) : (
                          <>
                            <EyeOff size={13} />
                            <span>Hidden on Inner</span>
                          </>
                        )}
                      </button>
                    </td>

                    {/* Actions */}
                    <td style={{ padding: '14px 12px', textAlign: 'right' }}>
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
                          onClick={() => setDeleteModal({ open: true, id: item.id, title: item.label })}
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
              maxHeight: '90vh',
              overflowY: 'auto',
            }}
            onClick={(e) => e.stopPropagation()}
          >
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px', borderBottom: '1px solid #ECEEF3', paddingBottom: '14px' }}>
              <h3 style={{ fontSize: '18px', fontWeight: '800', color: '#171151', margin: 0 }}>
                Edit Menu Item &amp; Dropdowns
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
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Menu Label / Text Name</label>
                  <input
                    type="text"
                    value={editModal.data.label || ''}
                    onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, label: e.target.value } })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '8px', border: '1px solid #CBD5E1', boxSizing: 'border-box' }}
                  />
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Page URL Link</label>
                  <input
                    type="text"
                    value={editModal.data.link || ''}
                    onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, link: e.target.value } })}
                    required
                    style={{ width: '100%', padding: '10px', borderRadius: '8px', border: '1px solid #CBD5E1', boxSizing: 'border-box' }}
                  />
                </div>

                {/* Dual Visibility Checkboxes */}
                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '12px', padding: '12px', backgroundColor: '#F8FAFC', borderRadius: '8px', border: '1px solid #E2E8F0' }}>
                  <label style={{ display: 'flex', alignItems: 'center', gap: '8px', cursor: 'pointer', fontSize: '13px', fontWeight: '700', color: '#171151' }}>
                    <input
                      type="checkbox"
                      checked={editModal.data.showOnHome !== false}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, showOnHome: e.target.checked } })}
                      style={{ width: '16px', height: '16px' }}
                    />
                    <span>Show on Home Header</span>
                  </label>
                  <label style={{ display: 'flex', alignItems: 'center', gap: '8px', cursor: 'pointer', fontSize: '13px', fontWeight: '700', color: '#171151' }}>
                    <input
                      type="checkbox"
                      checked={editModal.data.showOnInner !== false}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, showOnInner: e.target.checked } })}
                      style={{ width: '16px', height: '16px' }}
                    />
                    <span>Show on Inner Header</span>
                  </label>
                </div>

                {/* Toggle Dropdown */}
                <div>
                  <label style={{ display: 'flex', alignItems: 'center', gap: '8px', cursor: 'pointer', fontSize: '13px', fontWeight: '700', color: '#0E63FF' }}>
                    <input
                      type="checkbox"
                      checked={editModal.data.hasDropdown || false}
                      onChange={(e) => setEditModal({ ...editModal, data: { ...editModal.data, hasDropdown: e.target.checked } })}
                      style={{ width: '18px', height: '18px' }}
                    />
                    <span>Has Dropdown Submenu</span>
                  </label>
                </div>

                {/* Sub-items if hasDropdown */}
                {editModal.data.hasDropdown && (
                  <div style={{ marginTop: '10px', padding: '16px', borderRadius: '8px', backgroundColor: '#F8FAFC', border: '1px solid #E2E8F0' }}>
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '12px' }}>
                      <label style={{ fontSize: '13px', fontWeight: '700', color: '#171151', margin: 0 }}>Dropdown Submenu Items</label>
                      <button
                        type="button"
                        onClick={() => {
                          const updatedSub = [...(editModal.data.subItems || []), { id: `sub-${Date.now()}`, label: 'New Item', link: '/services', enabled: true }];
                          setEditModal({ ...editModal, data: { ...editModal.data, subItems: updatedSub } });
                        }}
                        style={{ padding: '4px 10px', backgroundColor: '#0E63FF', color: '#fff', border: 'none', borderRadius: '4px', fontSize: '12px', fontWeight: '600', cursor: 'pointer' }}
                      >
                        + Add Sub-item
                      </button>
                    </div>

                    {(editModal.data.subItems || []).map((sub, sIdx) => (
                      <div key={sIdx} style={{ display: 'flex', gap: '6px', marginBottom: '8px', alignItems: 'center' }}>
                        <input
                          type="text"
                          value={sub.label}
                          placeholder="Sub-item Label"
                          onChange={(e) => {
                            const updatedSub = [...editModal.data.subItems];
                            updatedSub[sIdx].label = e.target.value;
                            setEditModal({ ...editModal, data: { ...editModal.data, subItems: updatedSub } });
                          }}
                          style={{ flex: 1.2, padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px' }}
                        />
                        <input
                          type="text"
                          value={sub.link}
                          placeholder="/page-link"
                          onChange={(e) => {
                            const updatedSub = [...editModal.data.subItems];
                            updatedSub[sIdx].link = e.target.value;
                            setEditModal({ ...editModal, data: { ...editModal.data, subItems: updatedSub } });
                          }}
                          style={{ flex: 1, padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px' }}
                        />
                        <button
                          type="button"
                          onClick={() => {
                            const updatedSub = [...editModal.data.subItems];
                            updatedSub[sIdx].enabled = updatedSub[sIdx].enabled === false ? true : false;
                            setEditModal({ ...editModal, data: { ...editModal.data, subItems: updatedSub } });
                          }}
                          style={{ padding: '6px 10px', borderRadius: '6px', border: 'none', fontSize: '11px', fontWeight: '700', backgroundColor: sub.enabled !== false ? '#E7FAF6' : '#FEEAF1', color: sub.enabled !== false ? '#0b9748' : '#F72A75', cursor: 'pointer' }}
                        >
                          {sub.enabled !== false ? 'Active' : 'Off'}
                        </button>
                        <button
                          type="button"
                          onClick={() => {
                            const updatedSub = editModal.data.subItems.filter((_, i) => i !== sIdx);
                            setEditModal({ ...editModal, data: { ...editModal.data, subItems: updatedSub } });
                          }}
                          style={{ padding: '6px 8px', borderRadius: '6px', border: 'none', backgroundColor: '#FEEAF1', color: '#F72A75', cursor: 'pointer' }}
                        >
                          <Trash2 size={13} />
                        </button>
                      </div>
                    ))}
                  </div>
                )}
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
              Are you sure you want to delete <strong style={{ color: '#171151' }}>&ldquo;{deleteModal.title}&rdquo;</strong> from header navigation?
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

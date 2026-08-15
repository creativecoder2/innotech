'use client';

import React, { useState } from 'react';

export default function AppointmentForm({ config = {} }) {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    subject: '',
    phone: '',
    message: '',
  });

  const [loading, setLoading] = useState(false);
  const [statusMsg, setStatusMsg] = useState({ type: '', text: '' });

  const apptConfig = config.appointment || {};
  const formTitle = apptConfig.title || 'GET IN TOUCH WITH US';
  const emergencyText = apptConfig.emergencyText || '24/7 Emergency Service :';
  const emergencyPhone = apptConfig.emergencyPhone || '+92 3316699992';

  const handleChange = (e) => {
    const { name, value } = e.target;
    if (name === 'phone') {
      const numericValue = value.replace(/[^0-9+]/g, '');
      setFormData({ ...formData, phone: numericValue });
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!formData.name.trim() || !formData.phone.trim() || !formData.email.trim()) {
      setStatusMsg({ type: 'error', text: 'Please provide your full name, email, and phone number.' });
      return;
    }

    setLoading(true);
    setStatusMsg({ type: '', text: '' });

    try {
      const res = await fetch('/api/inquiries', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          name: formData.name.trim(),
          email: formData.email.trim(),
          phone: formData.phone.trim(),
          subject: formData.subject.trim() || 'Home Page Consultation / Booking',
          message: formData.message.trim() || 'General consultation request from home page.',
          source: 'Home Page Consultation',
        }),
      });

      const data = await res.json();

      if (res.ok && data.success) {
        setStatusMsg({
          type: 'success',
          text: data.message || 'Thank you! Your consultation inquiry has been received. Our biomedical team will contact you shortly.',
        });
        setFormData({
          name: '',
          email: '',
          subject: '',
          phone: '',
          message: '',
        });
      } else {
        setStatusMsg({
          type: 'error',
          text: data.message || 'Failed to submit inquiry. Please try again.',
        });
      }
    } catch (err) {
      console.error('Home form submit error:', err);
      setStatusMsg({
        type: 'error',
        text: 'Network error. Please check your connection or call our direct helpline.',
      });
    } finally {
      setLoading(false);
    }
  };

  return (
    <section className="appoinment-area">
      <div className="container-fluid">
        <div className="row">
          <div className="col-xxl-6 col-xl-5 col-lg-12 col-md-12 p-0">
            <div className="appoinment-thumb">
              <img src="/assets/img/banner/appoinment-01.jpg" alt="appoinment-img" />
            </div>
          </div>
          <div className="col-xxl-6 col-xl-7 col-lg-12 col-md-12 p-0">
            <div className="visitor-info">
              <h4 className="appoinment-title mb-25">
                <i className="fa-light fa-file-signature mr-2"></i>
                {formTitle}
              </h4>
              <div className="visitor-form">
                <form onSubmit={handleSubmit}>
                  {statusMsg.text && (
                    <div
                      className={`alert ${
                        statusMsg.type === 'success' ? 'alert-success' : 'alert-danger'
                      } mb-20`}
                      style={{
                        padding: '12px 16px',
                        borderRadius: '6px',
                        fontSize: '14px',
                        backgroundColor: statusMsg.type === 'success' ? '#e8f5e9' : '#ffebee',
                        color: statusMsg.type === 'success' ? '#2e7d32' : '#c62828',
                        border: `1px solid ${
                          statusMsg.type === 'success' ? '#a5d6a7' : '#ef9a9a'
                        }`,
                      }}
                    >
                      {statusMsg.text}
                    </div>
                  )}
                  <div className="row">
                    <div className="col-lg-6">
                      <div className="visitor-form__input mb-25">
                        <input
                          type="text"
                          name="name"
                          placeholder="Enter your full name"
                          value={formData.name}
                          onChange={handleChange}
                          required
                        />
                      </div>
                    </div>
                    <div className="col-lg-6">
                      <div className="visitor-form__input mb-25">
                        <input
                          type="email"
                          name="email"
                          placeholder="Enter your email"
                          value={formData.email}
                          onChange={handleChange}
                          required
                        />
                      </div>
                    </div>
                    <div className="col-lg-6">
                      <div className="visitor-form__input mb-25">
                        <input
                          type="tel"
                          name="phone"
                          inputMode="numeric"
                          placeholder="Enter phone / mobile number"
                          value={formData.phone}
                          onChange={handleChange}
                          required
                        />
                      </div>
                    </div>
                    <div className="col-lg-6">
                      <div className="visitor-form__input mb-25">
                        <input
                          type="text"
                          name="subject"
                          placeholder="Equipment inquiry / Organization"
                          value={formData.subject}
                          onChange={handleChange}
                        />
                      </div>
                    </div>
                    <div className="col-lg-12">
                      <div className="visitor-form__input mb-25">
                        <textarea
                          placeholder="How can our biomedical team assist your facility?"
                          name="message"
                          value={formData.message}
                          onChange={handleChange}
                          rows={4}
                        ></textarea>
                      </div>
                    </div>
                    <div className="col-lg-4 col-md-4 col-12">
                      <div className="visit-btn mt-10">
                        <button
                          className="tp-btn"
                          type="submit"
                          disabled={loading}
                          style={{ cursor: loading ? 'not-allowed' : 'pointer' }}
                        >
                          {loading ? 'Sending...' : 'SEND'}
                        </button>
                      </div>
                    </div>
                    <div className="col-lg-8 col-md-8 col-12">
                      <div className="visit-serial mt-35">
                        <span>
                          {emergencyText}{' '}
                          <a href={`tel:${emergencyPhone.replace(/\s+/g, '')}`}>
                            {emergencyPhone}
                            <i className="fa-regular fa-arrow-right ml-1"></i>
                          </a>
                        </span>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

'use client';

import React, { useState } from 'react';

export default function ServiceSupportForm({
  tagline = 'Direct Contact with us',
  btnText = 'Send Message',
}) {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
  });
  const [status, setStatus] = useState({ type: '', msg: '' });
  const [submitting, setSubmitting] = useState(false);

  const handleInputChange = (e) => {
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
    if (!formData.name.trim() || !formData.phone.trim()) {
      setStatus({
        type: 'error',
        msg: 'Please provide at least your full name and phone number.',
      });
      return;
    }

    setSubmitting(true);
    setStatus({ type: '', msg: '' });

    try {
      const res = await fetch('/api/inquiries', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          name: formData.name.trim(),
          phone: formData.phone.trim(),
          email: formData.email.trim() || 'Not Provided',
          subject: formData.subject.trim() || 'Services Page Inquiry',
          message:
            formData.message.trim() || 'General service inquiry submitted from services page form.',
          source: 'Services Page Contact Form',
        }),
      });

      const data = await res.json();
      if (res.ok && data.success) {
        setStatus({
          type: 'success',
          msg:
            data.message ||
            'Thank you! Your inquiry has been received. Our biomedical team will contact you shortly.',
        });
        setFormData({ name: '', email: '', phone: '', subject: '', message: '' });
      } else {
        setStatus({
          type: 'error',
          msg: data.message || 'Failed to submit inquiry. Please try again.',
        });
      }
    } catch (err) {
      setStatus({
        type: 'error',
        msg: 'Network error. Please check your connection and retry.',
      });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="contactform wow fadeInUp" data-wow-delay=".3s">
      {tagline && (
        <span
          style={{
            fontSize: '13px',
            fontWeight: '700',
            color: '#0E63FF',
            letterSpacing: '1px',
            textTransform: 'uppercase',
            display: 'block',
            marginBottom: '8px',
            textAlign: 'center',
          }}
        >
          {tagline}
        </span>
      )}
      <h4 className="contactform__title text-center mb-35">Send us a Message :</h4>

      {status.msg && (
        <div
          style={{
            padding: '14px 18px',
            marginBottom: '24px',
            borderRadius: '8px',
            fontSize: '14px',
            fontWeight: '600',
            backgroundColor: status.type === 'success' ? '#E7FAF6' : '#FEEAF1',
            color: status.type === 'success' ? '#0B9748' : '#F72A75',
            border: `1px solid ${status.type === 'success' ? '#A3EAD8' : '#FECDD3'}`,
            textAlign: 'center',
          }}
        >
          {status.msg}
        </div>
      )}

      <div className="contactform__list mb-60">
        <form onSubmit={handleSubmit}>
          <div className="row">
            <div className="col-lg-6">
              <div className="contactform__input mb-30">
                <input
                  name="name"
                  type="text"
                  placeholder="Enter your Name"
                  value={formData.name}
                  onChange={handleInputChange}
                  required
                />
              </div>
            </div>
            <div className="col-lg-6">
              <div className="contactform__input mb-30">
                <input
                  name="email"
                  type="email"
                  placeholder="Enter your mail"
                  value={formData.email}
                  onChange={handleInputChange}
                />
              </div>
            </div>
            <div className="col-lg-6">
              <div className="contactform__input mb-30">
                <input
                  name="phone"
                  type="tel"
                  inputMode="numeric"
                  placeholder="Enter your number"
                  value={formData.phone}
                  onChange={handleInputChange}
                  required
                />
              </div>
            </div>
            <div className="col-lg-6">
              <div className="contactform__input mb-30">
                <input
                  name="subject"
                  type="text"
                  placeholder="Equipment inquiry / Organization"
                  value={formData.subject}
                  onChange={handleInputChange}
                />
              </div>
            </div>
            <div className="col-lg-12">
              <div className="contactform__input mb-30">
                <textarea
                  name="message"
                  placeholder="Type your comment"
                  value={formData.message}
                  onChange={handleInputChange}
                ></textarea>
              </div>
            </div>
            <div className="col-lg-12 text-center">
              <div className="contactform__input mb-30-btn">
                <button
                  type="submit"
                  className="tp-btn"
                  disabled={submitting}
                  style={{ cursor: submitting ? 'not-allowed' : 'pointer' }}
                >
                  {submitting ? 'Sending...' : btnText}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  );
}

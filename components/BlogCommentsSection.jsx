'use client';

import React, { useState, useEffect } from 'react';

export default function BlogCommentsSection({ blogSlug, blogTitle }) {
  const [comments, setComments] = useState([]);
  const [loading, setLoading] = useState(true);
  const [formData, setFormData] = useState({ name: '', email: '', comment: '' });
  const [submitting, setSubmitting] = useState(false);
  const [submitStatus, setSubmitStatus] = useState({ type: '', msg: '' });

  useEffect(() => {
    if (blogSlug) {
      fetchComments();
    }
  }, [blogSlug]);

  const fetchComments = async () => {
    try {
      const res = await fetch(`/api/blogs/comments?slug=${encodeURIComponent(blogSlug)}`);
      const data = await res.json();
      if (data.success && Array.isArray(data.data)) {
        setComments(data.data);
      }
    } catch (e) {
      console.error('Error fetching blog comments:', e);
    } finally {
      setLoading(false);
    }
  };

  const handleInputChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmitComment = async (e) => {
    e.preventDefault();
    if (!formData.name.trim() || !formData.email.trim() || !formData.comment.trim()) {
      setSubmitStatus({ type: 'error', msg: 'Please provide your name, email, and comment message.' });
      return;
    }

    setSubmitting(true);
    setSubmitStatus({ type: '', msg: '' });

    try {
      const res = await fetch('/api/blogs/comments', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          blogSlug,
          blogTitle: blogTitle || 'Clinical Article',
          name: formData.name.trim(),
          email: formData.email.trim(),
          comment: formData.comment.trim(),
        }),
      });

      const data = await res.json();
      if (res.ok && data.success) {
        setSubmitStatus({
          type: 'success',
          msg: data.message || 'Thank you! Your comment has been submitted and is pending administrator approval before being published.',
        });
        setFormData({ name: '', email: '', comment: '' });
      } else {
        setSubmitStatus({
          type: 'error',
          msg: data.message || 'Failed to submit comment. Please try again.',
        });
      }
    } catch (err) {
      setSubmitStatus({
        type: 'error',
        msg: 'Network error submitting comment. Please try again later.',
      });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="postbox__comment-wrapper mt-50">
      {/* 1. Approved Comments List */}
      <div className="postbox__comment mb-65">
        <h3 className="postbox__comment-title">
          Comments ({comments.length})
        </h3>

        {loading ? (
          <div className="py-4 text-center" style={{ color: 'var(--tp-text-2)' }}>
            Loading comments...
          </div>
        ) : comments.length === 0 ? (
          <div
            className="p-4 text-center mb-30"
            style={{
              backgroundColor: 'var(--tp-grey-1)',
              borderRadius: '7px',
              border: '1px dashed var(--tp-border-secondary)',
            }}
          >
            <p style={{ margin: 0, color: 'var(--tp-text-2)', fontSize: '15px' }}>
              No comments yet on this article. Be the first to share your thoughts below!
            </p>
          </div>
        ) : (
          <ul>
            {comments.map((c, idx) => (
              <li key={c._id || idx}>
                <div className="postbox__comment-box d-flex">
                  <div
                    className="postbox__comment-avater mr-20"
                    style={{
                      height: '65px',
                      width: '65px',
                      borderRadius: '50%',
                      backgroundColor: 'var(--tp-icon-blue-light)',
                      color: 'var(--tp-theme-blue)',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      fontSize: '22px',
                      fontWeight: '700',
                      flexShrink: 0,
                    }}
                  >
                    {c.name ? c.name.charAt(0).toUpperCase() : 'U'}
                  </div>
                  <div className="postbox__comment-text">
                    <div className="postbox__comment-name">
                      <h5>{c.name}</h5>
                      <span>
                        {c.createdAt
                          ? new Date(c.createdAt).toLocaleDateString('en-US', {
                              year: 'numeric',
                              month: 'short',
                              day: 'numeric',
                            })
                          : 'Recent'}
                      </span>
                    </div>
                    <p style={{ whiteSpace: 'pre-wrap' }}>{c.comment}</p>
                  </div>
                </div>
              </li>
            ))}
          </ul>
        )}
      </div>

      {/* 2. Leave a Comment Form */}
      <div className="postbox__comment-form">
        <h3 className="postbox__comment-form-title">Leave a Comment</h3>
        <p>
          Your email address will not be published. Required fields are marked *
        </p>

        {submitStatus.msg && (
          <div
            className="p-3 mb-30"
            style={{
              borderRadius: '7px',
              fontSize: '14px',
              fontWeight: '600',
              backgroundColor: submitStatus.type === 'error' ? 'var(--tp-icon-pink-light)' : 'var(--tp-icon-green-light)',
              color: submitStatus.type === 'error' ? 'var(--tp-icon-pink)' : 'var(--tp-icon-green)',
              border: `1px solid ${submitStatus.type === 'error' ? 'var(--tp-icon-pink)' : 'var(--tp-icon-green)'}`,
            }}
          >
            {submitStatus.msg}
          </div>
        )}

        <form onSubmit={handleSubmitComment}>
          <div className="row">
            <div className="col-xxl-6 col-xl-6 col-lg-6">
              <div className="postbox__comment-input">
                <span>Your Name *</span>
                <input
                  type="text"
                  name="name"
                  placeholder="Enter your name"
                  value={formData.name}
                  onChange={handleInputChange}
                  required
                />
              </div>
            </div>
            <div className="col-xxl-6 col-xl-6 col-lg-6">
              <div className="postbox__comment-input">
                <span>Your Email *</span>
                <input
                  type="email"
                  name="email"
                  placeholder="Enter your email"
                  value={formData.email}
                  onChange={handleInputChange}
                  required
                />
              </div>
            </div>
            <div className="col-xxl-12">
              <div className="postbox__comment-input">
                <span>Write Your Comment *</span>
                <textarea
                  name="comment"
                  placeholder="Write your comment here..."
                  value={formData.comment}
                  onChange={handleInputChange}
                  required
                ></textarea>
              </div>
            </div>
            <div className="col-xxl-12">
              <div className="postbox__comment-btn mt-20">
                <button type="submit" className="tp-btn" disabled={submitting}>
                  {submitting ? 'Submitting...' : 'Post Comment'} <i className="fa-regular fa-arrow-right ml-10"></i>
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  );
}


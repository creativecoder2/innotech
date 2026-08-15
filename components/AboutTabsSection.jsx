'use client';

import React, { useState } from 'react';

export default function AboutTabsSection({ config = {} }) {
  const [activeTab, setActiveTab] = useState('process');

  const tabsConfig = config.tabsSection || config.missionVision || {};

  // Tab 1 (Process) Data
  const processInfo =
    tabsConfig.processInfo ||
    'Your trusted partner for medical equipment procurement, turnkey installations,\nand technical integration across Pakistan.';
  const step1Title = tabsConfig.step1Title || 'Consultation & Proposal';
  const step1Desc =
    tabsConfig.step1Desc ||
    'Understanding facility requirements to recommend compliant, cost-effective medical equipment solutions.';
  const step2Title = tabsConfig.step2Title || 'Seamless Deployment';
  const step2Desc =
    tabsConfig.step2Desc ||
    'Rapid procurement, physical installation, and precise site calibration by certified biomedical engineers.';
  const step3Title = tabsConfig.step3Title || 'Integration & Support';
  const step3Desc =
    tabsConfig.step3Desc ||
    'Comprehensive staff application training alongside 24/7 technical support and routine maintenance.';

  // Tab 2 (Mission) Data
  const missionInfo =
    tabsConfig.missionInfo ||
    'Your full service lab for clinical trials. Our process is to ensure the generation of \naccurate and precise findings';
  const missionHeadline =
    tabsConfig.missionHeadline ||
    tabsConfig.missionTitle ||
    'Our Mission is Give You Always Best Results.';
  const missionDesc =
    tabsConfig.missionDesc ||
    'To enhance the quality of healthcare across Pakistan by delivering state-of-the-art medical devices, advanced diagnostic technologies, and uncompromised technical support to hospitals and laboratories.';
  const missionImage1 = tabsConfig.missionImage1 || '/assets/img/tab/tab-thumb-03.jpg';
  const missionImage2 = tabsConfig.missionImage2 || '/assets/img/tab/tab-thumb-04.jpg';

  // Tab 3 (Value) Data
  const valueInfo =
    tabsConfig.valueInfo ||
    'Your full service lab for clinical trials. Our process is to ensure the generation of \naccurate and precise findings';
  const valueHeadline =
    tabsConfig.valueHeadline ||
    tabsConfig.valuesTitle ||
    'We are Trusted by over 25000+ of customers';
  const valueDesc =
    tabsConfig.valueDesc ||
    tabsConfig.valuesDesc ||
    'To become Pakistan’s premier and most trusted B2B healthcare partner, driving innovation in biomedical engineering and empowering institutions with futuristic medical solutions.';
  const valueImage1 = tabsConfig.valueImage1 || '/assets/img/tab/tab-thumb-01.jpg';
  const valueImage2 = tabsConfig.valueImage2 || '/assets/img/tab/tab-thumb-02.jpg';

  return (
    <section id="process-mission-tabs" className="nav-area tp-common-area pt-130 pb-80">
      <div className="container">
        {/* Navigation Tabs Header */}
        <ul className="nav tp-nav-tavs mb-70" role="tablist">
          <li className="nav-item" role="presentation">
            <button
              className={`nav-link ${activeTab === 'process' ? 'active' : ''}`}
              type="button"
              onClick={() => setActiveTab('process')}
            >
              Our Process
            </button>
          </li>
          <li className="nav-item" role="presentation">
            <button
              className={`nav-link ${activeTab === 'mission' ? 'active' : ''}`}
              type="button"
              onClick={() => setActiveTab('mission')}
            >
              Our Mission
            </button>
          </li>
          <li className="nav-item" role="presentation">
            <button
              className={`nav-link ${activeTab === 'value' ? 'active' : ''}`}
              type="button"
              onClick={() => setActiveTab('value')}
            >
              Our Value
            </button>
          </li>
        </ul>

        {/* Tab Content Panes */}
        <div className="tab-content">
          {/* TAB 1: OUR PROCESS */}
          {activeTab === 'process' && (
            <div className="tab-pane fade show active">
              <span className="nav-info d-flex justify-content-center text-center mb-75">
                {processInfo}
              </span>
              <div className="row">
                <div className="col-xl-4 col-lg-4 col-md-6">
                  <div className="navtabs nav-primary p-relative text-center mb-40">
                    <div className="navtabs__icon mb-35">
                      <i className="flaticon-approval"></i>
                    </div>
                    <div className="navtabs__content">
                      <h5 className="navtabs__title mb-25">{step1Title}</h5>
                      <p>{step1Desc}</p>
                    </div>
                    <div className="navtabs__shape d-none d-lg-block">
                      <img src="/assets/img/shape/navtabs-01.png" alt="shape" />
                    </div>
                  </div>
                </div>

                <div className="col-xl-4 col-lg-4 col-md-6">
                  <div className="navtabs nav-secondary p-relative text-center mb-40">
                    <div className="navtabs__icon mb-35">
                      <i className="flaticon-flask"></i>
                    </div>
                    <div className="navtabs__content">
                      <h5 className="navtabs__title mb-25">{step2Title}</h5>
                      <p>{step2Desc}</p>
                    </div>
                    <div className="navtabs__shape d-none d-lg-block">
                      <img src="/assets/img/shape/navtabs-01.png" alt="shape" />
                    </div>
                  </div>
                </div>

                <div className="col-xl-4 col-lg-4 col-md-6">
                  <div className="navtabs nav-tertiary text-center mb-40">
                    <div className="navtabs__icon mb-35">
                      <i className="flaticon-report"></i>
                    </div>
                    <div className="navtabs__content">
                      <h5 className="navtabs__title mb-25">{step3Title}</h5>
                      <p>{step3Desc}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* TAB 2: OUR MISSION */}
          {activeTab === 'mission' && (
            <div className="tab-pane fade show active">
              <span className="nav-info d-flex justify-content-center text-center mb-75">
                {missionInfo}
              </span>
              <div className="row align-items-center">
                <div className="col-xl-6 col-lg-6 col-md-12 order-lg-2">
                  <div className="nabmission mb-30">
                    <div className="nabmission__content text-center ml-50 mr-50 pt-20">
                      <h4 className="nabmission__title mb-35">{missionHeadline}</h4>
                      <p className="mb-35">{missionDesc}</p>
                    </div>
                  </div>
                </div>
                <div className="col-xl-3 col-lg-3 col-md-6 order-lg-1">
                  <div className="nabthumb mb-30">
                    <img src={missionImage1} alt="Our Mission Left" />
                  </div>
                </div>
                <div className="col-xl-3 col-lg-3 col-md-6 order-lg-3">
                  <div className="nabthumb mb-30">
                    <img src={missionImage2} alt="Our Mission Right" />
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* TAB 3: OUR VALUE */}
          {activeTab === 'value' && (
            <div className="tab-pane fade show active">
              <span className="nav-info d-flex justify-content-center text-center mb-75">
                {valueInfo}
              </span>
              <div className="row align-items-center">
                <div className="col-xl-6 col-lg-6 col-md-12 order-lg-2">
                  <div className="nabmission mb-30">
                    <div className="nabmission__content text-center ml-50 mr-50 pt-20">
                      <h4 className="nabmission__title mb-35">{valueHeadline}</h4>
                      <p className="mb-35">{valueDesc}</p>
                    </div>
                  </div>
                </div>
                <div className="col-xl-3 col-lg-3 col-md-6 order-lg-1">
                  <div className="nabthumb mb-30">
                    <img src={valueImage1} alt="Our Value Left" />
                  </div>
                </div>
                <div className="col-xl-3 col-lg-3 col-md-6 order-lg-3">
                  <div className="nabthumb mb-30">
                    <img src={valueImage2} alt="Our Value Right" />
                  </div>
                </div>
              </div>
            </div>
          )}
        </div>
      </div>
    </section>
  );
}

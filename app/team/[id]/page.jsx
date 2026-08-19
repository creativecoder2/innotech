import React from 'react';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import { fallbackTeam, fallbackSiteConfig } from '@/lib/data';
import { getLocalStore } from '@/lib/storage';
import connectToDatabase from '@/lib/db';
import SiteConfig from '@/models/SiteConfig';

export const revalidate = 60;

export async function generateStaticParams() {
  try {
    const local = getLocalStore();
    const members = local?.team?.length ? local.team : fallbackTeam;
    return members.map((m) => ({ id: String(m._id || m.id) }));
  } catch (e) {
    return fallbackTeam.map((m) => ({ id: String(m._id || m.id) }));
  }
}

async function getTeamData(idOrSlug) {
  let allMembers = fallbackTeam;
  let siteConfig = fallbackSiteConfig;

  try {
    const local = getLocalStore();
    if (local) {
      if (Array.isArray(local.team) && local.team.length > 0) {
        allMembers = local.team;
      } else if (local.config?.teamSection?.members?.length > 0) {
        allMembers = local.config.teamSection.members;
      }
      if (local.config) siteConfig = local.config;
    }
  } catch (e) {
    console.error('Error reading team member in /team/[id]:', e);
  }

  const decodeParam = decodeURIComponent(idOrSlug).toLowerCase().trim();
  const generateSlug = (str) =>
    (str || '').toLowerCase().trim().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-');

  const member = allMembers.find(
    (m) =>
      String(m._id) === decodeParam ||
      String(m.id) === decodeParam ||
      (m.slug && m.slug.toLowerCase() === decodeParam) ||
      generateSlug(m.name) === decodeParam ||
      m.name?.toLowerCase().replace(/\s+/g, '-') === decodeParam
  );

  return { member, allMembers, siteConfig, teamPagesConfig: siteConfig?.teamPages || {} };
}

export async function generateMetadata({ params }) {
  const { member } = await getTeamData(params.id);
  if (!member) {
    return { title: 'Specialist Not Found - INNOTECH MEDICAL PVT LTD' };
  }
  return {
    title: `${member.name} - ${member.position} | INNOTECH MEDICAL PVT LTD`,
    description: member.bio || `Profile of ${member.name}, ${member.position} at Innotech Medical Pvt Ltd.`,
  };
}

export default async function TeamDetailsPage({ params }) {
  const { member, siteConfig, teamPagesConfig } = await getTeamData(params.id);

  if (!member) notFound();

  const detailsPageConfig = teamPagesConfig?.teamDetails || {};
  const bannerImage    = detailsPageConfig.bannerImage    || '/assets/img/banner/breadcrumb-01.jpg';
  const bannerTitle    = detailsPageConfig.bannerTitle    || 'Team Details';
  const bannerBreadcrumb = detailsPageConfig.bannerBreadcrumb || 'Our Team';

  const social = member.socialLinks || {};
  const facebookUrl  = social.facebook  && social.facebook  !== '#' ? social.facebook  : null;
  const instagramUrl = social.instagram && social.instagram !== '#' ? social.instagram : null;
  const twitterUrl   = social.twitter   && social.twitter   !== '#' ? social.twitter   : null;
  const linkedinUrl  = social.linkedin  && social.linkedin  !== '#' ? social.linkedin  : null;
  const youtubeUrl   = social.youtube   && social.youtube   !== '#' ? social.youtube   : null;

  const phone    = member.phone    || siteConfig.footer?.phone || '+92 331 6699992';
  const email    = member.email    || siteConfig.footer?.email || 'info@innotecmedical.org';
  const experience = member.experience || '10+ Years';

  const biography =
    member.biography ||
    member.bio ||
    `${member.name} is a seasoned medical technology specialist at Innotech Medical Pvt Ltd, with extensive expertise in ${member.position}.`;

  let skillsList = [];
  if (Array.isArray(member.skills) && member.skills.length > 0) {
    skillsList = member.skills;
  } else if (typeof member.skills === 'string' && member.skills.trim()) {
    skillsList = member.skills.split('\n').map((s) => s.trim()).filter(Boolean);
  } else if (Array.isArray(member.competencies) && member.competencies.length > 0) {
    skillsList = member.competencies;
  } else if (typeof member.competencies === 'string' && member.competencies.trim()) {
    skillsList = member.competencies.split('\n').map((s) => s.trim()).filter(Boolean);
  } else {
    skillsList = [
      'Biomedical Device Calibration & Safety Testing',
      'Turnkey Operating Room & ICU Integration',
      'Clinical Training for Healthcare Providers',
      '24/7 Rapid Emergency Technical Support',
    ];
  }

  // Split skills into 3 groups for the 3-column layout
  const third = Math.ceil(skillsList.length / 3);
  const group1 = skillsList.slice(0, third);
  const group2 = skillsList.slice(third, third * 2);
  const group3 = skillsList.slice(third * 2);

  return (
    <>
      {/* ── Breadcrumb (exact team-details.html structure) ── */}
      <section
        className="breadcrumb__area pt-100 pb-120 breadcrumb__overlay"
        style={{ backgroundImage: `url('${bannerImage}')`, backgroundSize: 'cover', backgroundPosition: 'center' }}
      >
        <div className="container">
          <div className="row align-items-center">
            <div className="col-xl-7 col-lg-12 col-md-12 col-12">
              <div className="tp-breadcrumb">
                <h2 className="tp-breadcrumb__title">{bannerTitle}</h2>
              </div>
            </div>
            <div className="col-xl-5 col-lg-12 col-md-12 col-12">
              <div className="tp-breadcrumb__link text-xl-end">
                <span>
                  Innotech : <Link href="/allteams">{bannerBreadcrumb}</Link>
                </span>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ── Team Details Area (exact team-details.html 3-column layout) ── */}
      <section className="team-details-area pt-130 pb-70">
        <div className="container">

          {/* Row 1: Photo | Info | Social Icons */}
          <div className="row">
            {/* Col 1: Photo */}
            <div className="col-lg-5 col-md-6">
              <div className="tp-team-dtls__thumb mb-50">
                <img
                  src={member.image || '/assets/img/team/team-thumb-01.jpg'}
                  alt={member.name}
                  style={{ width: '100%', height: 'auto', borderRadius: '4px' }}
                />
              </div>
            </div>

            {/* Col 2: Name, Position, Bio, Info */}
            <div className="col-lg-4 col-md-6">
              <div className="tp-team-dtls__content mt-50 mb-50">
                <h4 className="tp-team-dtls__title mb-10">
                  <Link href="/allteams">{member.name}</Link>
                </h4>
                <span className="mb-35">{member.position}</span>
                <p style={{ marginTop: '20px', marginBottom: '30px' }}>{member.bio}</p>

                <div className="tp-team-dtls__info">
                  <ul>
                    <li>
                      Expertise: <span>{member.position}</span>
                    </li>
                    <li>
                      Experience: <span>{experience}</span>
                    </li>
                    <li>
                      E-mail:{' '}
                      <span>
                        <a href={`mailto:${email}`}>{email}</a>
                      </span>
                    </li>
                    {phone && (
                      <li>
                        Phone:{' '}
                        <span>
                          <a href={`tel:${phone.replace(/\s+/g, '')}`}>{phone}</a>
                        </span>
                      </li>
                    )}
                  </ul>
                </div>
              </div>
            </div>

            {/* Col 3: Social Icons (top-right colored squares) */}
            <div className="col-lg-3 col-md-12">
              <div className="tp-team-dtls__social mt-45 mb-50">
                {/* Facebook — default blue */}
                <a href={facebookUrl || '#'} target={facebookUrl ? '_blank' : undefined} rel="noreferrer" aria-label="Facebook">
                  <i className="fa-brands fa-facebook-f"></i>
                </a>
                {/* Instagram — blue (tp-dtls-insta) */}
                <a className="tp-dtls-insta" href={instagramUrl || '#'} target={instagramUrl ? '_blank' : undefined} rel="noreferrer" aria-label="Instagram">
                  <i className="fa-brands fa-instagram"></i>
                </a>
                {/* Twitter — tweet blue (tp-dtls-tweet) */}
                <a className="tp-dtls-tweet" href={twitterUrl || '#'} target={twitterUrl ? '_blank' : undefined} rel="noreferrer" aria-label="Twitter">
                  <i className="fa-brands fa-twitter"></i>
                </a>
                {/* LinkedIn — red/pink (tp-dtls-pinter) */}
                <a className="tp-dtls-pinter" href={linkedinUrl || '#'} target={linkedinUrl ? '_blank' : undefined} rel="noreferrer" aria-label="LinkedIn">
                  <i className="fa-brands fa-linkedin-in"></i>
                </a>
                {youtubeUrl && (
                  <a
                    href={youtubeUrl}
                    target="_blank"
                    rel="noreferrer"
                    aria-label="YouTube"
                    style={{ backgroundColor: '#FF0000', marginLeft: '10px' }}
                  >
                    <i className="fa-brands fa-youtube"></i>
                  </a>
                )}
              </div>
            </div>
          </div>

          {/* Row 2: Full Biography */}
          <div className="row">
            <div className="col-lg-12">
              <div className="tp-team-dtls-text mt-40">
                <h4 className="tp-team-dtls-text__title mb-30">Professional Background & Clinical Expertise</h4>
                <p style={{ whiteSpace: 'pre-line' }}>{biography}</p>
              </div>
            </div>
          </div>

          {/* Row 3: Skills/Competencies in 3 columns */}
          {skillsList.length > 0 && (
            <div className="row">
              {/* Col A: Core Skills */}
              {group1.length > 0 && (
                <div className="col-lg-4 col-md-6">
                  <div className="tp-team-dtls-item mb-50 mt-35">
                    <h4 className="tp-team-dtls-item__title mb-25">Core Skills</h4>
                    <div className="tp-team-dtls-item__list">
                      <ul>
                        {group1.map((skill, i) => (
                          <li key={i}>
                            <i className="fa-solid fa-check"></i>{skill}
                          </li>
                        ))}
                      </ul>
                    </div>
                  </div>
                </div>
              )}

              {/* Col B: Expertise */}
              {group2.length > 0 && (
                <div className="col-lg-4 col-md-6">
                  <div className="tp-team-dtls-item mb-50 mt-35 ml-40">
                    <h4 className="tp-team-dtls-item__title mb-25">Expertise</h4>
                    <div className="tp-team-dtls-item__list">
                      <ul>
                        {group2.map((skill, i) => (
                          <li key={i}>
                            <i className="fa-solid fa-check"></i>{skill}
                          </li>
                        ))}
                      </ul>
                    </div>
                  </div>
                </div>
              )}

              {/* Col C: Competencies */}
              {group3.length > 0 && (
                <div className="col-lg-4 col-md-6">
                  <div className="tp-team-dtls-item mb-50 mt-35 ml-75">
                    <h4 className="tp-team-dtls-item__title mb-25">Competencies</h4>
                    <div className="tp-team-dtls-item__list">
                      <ul>
                        {group3.map((skill, i) => (
                          <li key={i}>
                            <i className="fa-solid fa-check"></i>{skill}
                          </li>
                        ))}
                      </ul>
                    </div>
                  </div>
                </div>
              )}
            </div>
          )}

        </div>
      </section>
    </>
  );
}

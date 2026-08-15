'use client';

import React, { useState, useEffect, useRef } from 'react';

function CounterNumber({ targetNumber }) {
  const [count, setCount] = useState(0);
  const ref = useRef(null);
  const [hasAnimated, setHasAnimated] = useState(false);

  const num = parseInt(targetNumber?.toString().replace(/\D/g, ''), 10) || 0;

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting && !hasAnimated) {
          setHasAnimated(true);
          let start = 0;
          const duration = 1500;
          const stepTime = 20;
          const totalSteps = duration / stepTime;
          const increment = num / totalSteps;

          const timer = setInterval(() => {
            start += increment;
            if (start >= num) {
              setCount(num);
              clearInterval(timer);
            } else {
              setCount(Math.floor(start));
            }
          }, stepTime);
        }
      },
      { threshold: 0.2 }
    );

    if (ref.current) {
      observer.observe(ref.current);
    }

    return () => observer.disconnect();
  }, [num, hasAnimated]);

  return <span ref={ref} className="counter">{hasAnimated ? count : targetNumber}</span>;
}

export default function CounterSection({ config = {} }) {
  const countersConfig = config.counters || {};
  const labCount = countersConfig.item1Number || countersConfig.laboratories || '1492';
  const labTitle = countersConfig.item1Title || 'Laboratories in 100+ states';
  const specCount = countersConfig.item2Number || countersConfig.specialists || '152';
  const specTitle = countersConfig.item2Title || 'Laboratory specialists';
  const collectCount = countersConfig.item3Number || countersConfig.collectionPoints || '1022';
  const collectTitle = countersConfig.item3Title || 'Material collection points';
  const diagCount = countersConfig.item4Number || countersConfig.patientsDiagnosed || '24332';
  const diagTitle = countersConfig.item4Title || 'Patients diagnosed in 2022';

  const counters = [
    {
      id: 1,
      number: labCount,
      title: labTitle,
      borderClass: 'blue-border',
      iconClass: '',
      delay: '.2s',
    },
    {
      id: 2,
      number: specCount,
      title: specTitle,
      borderClass: 'pink-border',
      iconClass: 'pink-hard',
      delay: '.4s',
    },
    {
      id: 3,
      number: collectCount,
      title: collectTitle,
      borderClass: 'sky-border',
      iconClass: 'sky-hard',
      delay: '.6s',
    },
    {
      id: 4,
      number: diagCount,
      title: diagTitle,
      borderClass: 'green-border',
      iconClass: 'green-hard',
      delay: '.8s',
    },
  ];

  return (
    <section className="counter-area pt-40 pb-100">
      <div className="container">
        <div className="row">
          {counters.map((item) => (
            <div key={item.id} className="col-xl-3 col-md-6">
              <div
                className={`counter__item ${item.borderClass} mb-30 wow fadeInUp`}
                data-wow-delay={item.delay}
              >
                <div className={`counter__icon ${item.iconClass} mb-15`}>
                  <i></i>
                </div>
                <div className="counter__content">
                  <h4 className="counter__title">
                    <CounterNumber targetNumber={item.number} />
                  </h4>
                  <p>{item.title}</p>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

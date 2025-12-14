'use client'

import { motion } from 'framer-motion'
import { useInView } from 'react-intersection-observer'

interface HeroProps {
  data: {
    name: string
    title: string
    subtitle: string
    description: string
    profile_picture?: string
  }
}

export default function Hero({ data }: HeroProps) {
  const [ref, inView] = useInView({
    triggerOnce: true,
    threshold: 0.1,
  })

  // Vérification de sécurité
  if (!data) {
    return null
  }

  return (
    <section
      ref={ref}
      className="min-h-screen flex items-center justify-center relative overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900/30 to-slate-900"
    >
      {/* Animated background elements */}
      <div className="absolute inset-0 overflow-hidden">
        <div className="absolute top-20 left-10 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow"></div>
        <div className="absolute top-40 right-10 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow animation-delay-2000"></div>
        <div className="absolute -bottom-8 left-1/2 w-72 h-72 bg-green-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse-slow animation-delay-4000"></div>
      </div>

      {/* Data visualization elements */}
      <div className="absolute inset-0 opacity-10">
        <svg className="w-full h-full" viewBox="0 0 1200 800">
          <defs>
            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
              <stop offset="0%" style={{ stopColor: '#3b82f6', stopOpacity: 1 }} />
              <stop offset="100%" style={{ stopColor: '#8b5cf6', stopOpacity: 1 }} />
            </linearGradient>
          </defs>
          <path
            d="M 100 400 Q 300 200, 500 400 T 900 400"
            stroke="url(#grad1)"
            strokeWidth="2"
            fill="none"
            className="data-flow"
          />
          <circle cx="200" cy="300" r="4" fill="#3b82f6" className="animate-pulse" />
          <circle cx="400" cy="350" r="4" fill="#8b5cf6" className="animate-pulse animation-delay-1000" />
          <circle cx="600" cy="300" r="4" fill="#10b981" className="animate-pulse animation-delay-2000" />
          <circle cx="800" cy="350" r="4" fill="#f59e0b" className="animate-pulse animation-delay-3000" />
        </svg>
      </div>

      <motion.div
        initial={{ opacity: 0, y: 30 }}
        animate={inView ? { opacity: 1, y: 0 } : {}}
        transition={{ duration: 0.8 }}
        className="relative z-10 text-center px-4"
      >
        {/* Photo de profil */}
        <motion.div
          initial={{ opacity: 0, scale: 0.8 }}
          animate={inView ? { opacity: 1, scale: 1 } : {}}
          transition={{ duration: 0.8, delay: 0.1 }}
          className="mb-8 flex justify-center"
        >
          <div className="relative">
            <div className="absolute inset-0 bg-gradient-to-r from-blue-500 via-purple-500 to-green-500 rounded-full blur-2xl opacity-50 animate-pulse"></div>
            {data.profile_picture ? (
              <img
                src={data.profile_picture}
                alt={data.name}
                className="relative w-48 h-48 md:w-64 md:h-64 rounded-full object-cover border-4 border-slate-800 shadow-2xl"
                onError={(e) => {
                  const target = e.target as HTMLImageElement
                  target.style.display = 'none'
                  const placeholder = target.nextElementSibling as HTMLElement
                  if (placeholder) placeholder.style.display = 'flex'
                }}
              />
            ) : null}
            <div 
              className={`relative w-48 h-48 md:w-64 md:h-64 rounded-full border-4 border-slate-800 shadow-2xl bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center ${data.profile_picture ? 'hidden' : ''}`}
            >
              <div className="text-6xl md:text-8xl text-slate-400">
                {data.name.split(' ').map(n => n[0]).join('')}
              </div>
            </div>
          </div>
        </motion.div>
        <motion.h1
          initial={{ opacity: 0, scale: 0.9 }}
          animate={inView ? { opacity: 1, scale: 1 } : {}}
          transition={{ duration: 0.8, delay: 0.2 }}
          className="text-5xl md:text-7xl font-bold mb-4 gradient-text"
        >
          {data.name}
        </motion.h1>
        <motion.h2
          initial={{ opacity: 0, y: 20 }}
          animate={inView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.8, delay: 0.4 }}
          className="text-3xl md:text-5xl text-white mb-4"
        >
          {data.title}
        </motion.h2>
        <motion.p
          initial={{ opacity: 0, y: 20 }}
          animate={inView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.8, delay: 0.6 }}
          className="text-xl md:text-2xl text-gray-300 mb-6"
        >
          {data.subtitle}
        </motion.p>
        <motion.p
          initial={{ opacity: 0 }}
          animate={inView ? { opacity: 1 } : {}}
          transition={{ duration: 0.8, delay: 0.8 }}
          className="text-lg text-gray-400 max-w-2xl mx-auto"
        >
          {data.description}
        </motion.p>

        {/* Scroll indicator */}
        <motion.div
          initial={{ opacity: 0 }}
          animate={inView ? { opacity: 1 } : {}}
          transition={{ duration: 1, delay: 1.2 }}
          className="mt-16"
        >
          <motion.div
            animate={{ y: [0, 10, 0] }}
            transition={{ repeat: Infinity, duration: 2 }}
            className="text-white text-4xl"
          >
            ↓
          </motion.div>
          <p className="text-gray-400 mt-2">Découvrez mon histoire</p>
        </motion.div>
      </motion.div>
    </section>
  )
}


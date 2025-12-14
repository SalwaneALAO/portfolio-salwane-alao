'use client'

import { motion } from 'framer-motion'
import { useInView } from 'react-intersection-observer'

interface Quality {
  id: number
  name: string
  icon?: string
}

interface QualitiesProps {
  qualities: Quality[]
}

export default function Qualities({ qualities }: QualitiesProps) {
  const [ref, inView] = useInView({
    triggerOnce: true,
    threshold: 0.1,
  })

  if (!qualities || qualities.length === 0) {
    return null
  }

  return (
    <section ref={ref} className="py-20 px-4 bg-gradient-to-b from-slate-800/30 to-slate-900/50">
      <div className="max-w-6xl mx-auto">
        <motion.h2
          initial={{ opacity: 0, y: 20 }}
          animate={inView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.6 }}
          className="text-5xl font-bold text-center mb-16 gradient-text"
        >
          Mes Qualités
        </motion.h2>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {qualities.map((quality, index) => (
            <QualityCard key={quality.id} quality={quality} index={index} inView={inView} />
          ))}
        </div>
      </div>
    </section>
  )
}

function QualityCard({ quality, index, inView }: { quality: Quality; index: number; inView: boolean }) {
  return (
    <motion.div
      initial={{ opacity: 0, y: 30, scale: 0.9 }}
      animate={inView ? { opacity: 1, y: 0, scale: 1 } : {}}
      transition={{ duration: 0.5, delay: index * 0.1 }}
      whileHover={{ scale: 1.05, y: -5 }}
      className="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-sm rounded-2xl p-8 border border-slate-700/50 text-center shadow-xl hover:shadow-2xl hover:shadow-purple-500/20 transition-all group"
    >
      <div className="text-6xl mb-4 transform group-hover:scale-110 transition-transform">
        {quality.icon || '⭐'}
      </div>
      <h3 className="text-xl font-bold text-white group-hover:text-blue-400 transition-colors">
        {quality.name}
      </h3>
    </motion.div>
  )
}


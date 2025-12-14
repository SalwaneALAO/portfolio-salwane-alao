'use client'

import { motion } from 'framer-motion'
import { useInView } from 'react-intersection-observer'
import { useEffect, useState } from 'react'

interface Stat {
  label: string
  value: number
  icon: string
}

interface StatsProps {
  stats: Stat[]
}

export default function Stats({ stats }: StatsProps) {
  const [ref, inView] = useInView({
    triggerOnce: true,
    threshold: 0.3,
  })

  if (!stats || stats.length === 0) {
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
          En Chiffres
        </motion.h2>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {stats.map((stat, index) => (
            <StatCard key={stat.label} stat={stat} index={index} inView={inView} />
          ))}
        </div>
      </div>
    </section>
  )
}

function StatCard({ stat, index, inView }: { stat: Stat; index: number; inView: boolean }) {
  const [count, setCount] = useState(0)

  useEffect(() => {
    if (inView) {
      const duration = 2000
      const steps = 60
      const increment = stat.value / steps
      const stepDuration = duration / steps

      let current = 0
      const timer = setInterval(() => {
        current += increment
        if (current >= stat.value) {
          setCount(stat.value)
          clearInterval(timer)
        } else {
          setCount(Math.floor(current))
        }
      }, stepDuration)

      return () => clearInterval(timer)
    }
  }, [inView, stat.value])

  return (
    <motion.div
      initial={{ opacity: 0, scale: 0.8, y: 30 }}
      animate={inView ? { opacity: 1, scale: 1, y: 0 } : {}}
      transition={{ duration: 0.5, delay: index * 0.1 }}
      whileHover={{ scale: 1.08, y: -8, rotateY: 5 }}
      className="bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-sm rounded-2xl p-8 border border-slate-700/50 text-center shadow-2xl hover:shadow-purple-500/30 transition-all group relative overflow-hidden"
    >
      {/* Effet de brillance au survol */}
      <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
      
      <div className="text-6xl mb-4 transform group-hover:scale-110 group-hover:rotate-12 transition-transform">
        {stat.icon}
      </div>
      <motion.div
        initial={{ opacity: 0, scale: 0.5 }}
        animate={inView ? { opacity: 1, scale: 1 } : {}}
        transition={{ duration: 0.5, delay: index * 0.1 + 0.3 }}
        className="text-6xl font-bold gradient-text mb-3"
      >
        {count}+
      </motion.div>
      <div className="text-gray-300 text-lg font-medium group-hover:text-white transition-colors">
        {stat.label}
      </div>
    </motion.div>
  )
}


'use client'

import { motion } from 'framer-motion'
import { useInView } from 'react-intersection-observer'

interface Skill {
  name: string
  level: number
  category: string
  logo_url?: string
}

interface SkillsProps {
  skills: Skill[]
}

export default function Skills({ skills }: SkillsProps) {
  const [ref, inView] = useInView({
    triggerOnce: true,
    threshold: 0.1,
  })

  if (!skills || skills.length === 0) {
    return null
  }

  const categories = Array.from(new Set(skills.map(s => s.category)))

  return (
    <section ref={ref} className="py-20 px-4 bg-gradient-to-b from-slate-900/50 to-slate-800/30">
      <div className="max-w-6xl mx-auto">
        <motion.h2
          initial={{ opacity: 0, y: 20 }}
          animate={inView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.6 }}
          className="text-5xl font-bold text-center mb-16 gradient-text"
        >
          Mes Compétences
        </motion.h2>

        {categories.map((category, catIndex) => {
          const categorySkills = skills.filter(s => s.category === category)
          return (
            <motion.div
              key={category}
              initial={{ opacity: 0, y: 30 }}
              animate={inView ? { opacity: 1, y: 0 } : {}}
              transition={{ duration: 0.6, delay: catIndex * 0.2 }}
              className="mb-12"
            >
              <h3 className="text-2xl font-semibold text-white mb-6 text-center">
                {category}
              </h3>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {categorySkills.map((skill, index) => (
                  <SkillBar key={skill.name} skill={skill} index={index} inView={inView} />
                ))}
              </div>
            </motion.div>
          )
        })}
      </div>
    </section>
  )
}

function SkillBar({ skill, index, inView }: { skill: Skill; index: number; inView: boolean }) {
  return (
    <motion.div
      initial={{ opacity: 0, x: -20 }}
      animate={inView ? { opacity: 1, x: 0 } : {}}
      transition={{ duration: 0.5, delay: index * 0.1 }}
      whileHover={{ scale: 1.02, y: -2 }}
      className="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-sm rounded-xl p-5 border border-slate-700/50 hover:border-blue-500/70 hover:shadow-xl hover:shadow-blue-500/20 transition-all group"
    >
      <div className="flex items-center gap-4 mb-4">
        {skill.logo_url && (
          <div className="w-14 h-14 flex items-center justify-center bg-gradient-to-br from-slate-700/80 to-slate-800/80 rounded-xl p-3 group-hover:scale-110 transition-transform shadow-lg">
            <img 
              src={skill.logo_url} 
              alt={skill.name}
              className="w-full h-full object-contain filter brightness-0 invert"
              onError={(e) => {
                const target = e.target as HTMLImageElement
                target.style.display = 'none'
                const parent = target.parentElement
                if (parent) {
                  parent.innerHTML = `<span class="text-2xl">${skill.name.charAt(0)}</span>`
                }
              }}
            />
          </div>
        )}
        <div className="flex-1 flex justify-between items-center">
          <span className="text-white font-semibold text-lg group-hover:text-blue-400 transition-colors">{skill.name}</span>
          <span className="text-blue-400 font-bold text-lg">{skill.level}%</span>
        </div>
      </div>
      <div className="w-full bg-slate-700/50 rounded-full h-4 overflow-hidden shadow-inner">
        <motion.div
          initial={{ width: 0 }}
          animate={inView ? { width: `${skill.level}%` } : {}}
          transition={{ duration: 1.2, delay: index * 0.1 + 0.3, ease: 'easeOut' }}
          className="h-full bg-gradient-to-r from-blue-500 via-purple-500 to-green-500 rounded-full relative overflow-hidden"
        >
          <motion.div
            initial={{ opacity: 0 }}
            animate={inView ? { opacity: 1 } : {}}
            transition={{ duration: 0.5, delay: index * 0.1 + 0.8 }}
            className="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-shimmer"
          />
        </motion.div>
      </div>
    </motion.div>
  )
}


'use client'

import { motion } from 'framer-motion'
import { useInView } from 'react-intersection-observer'

interface Language {
  id: number
  name: string
  level: string
  flag_emoji?: string
  toeic_url?: string
}

interface LanguagesProps {
  languages: Language[]
}

export default function Languages({ languages }: LanguagesProps) {
  const [ref, inView] = useInView({
    triggerOnce: true,
    threshold: 0.1,
  })

  if (!languages || languages.length === 0) {
    return null
  }

  return (
    <section ref={ref} className="py-20 px-4 bg-gradient-to-b from-slate-900/50 to-slate-800/30">
      <div className="max-w-6xl mx-auto">
        <motion.h2
          initial={{ opacity: 0, y: 20 }}
          animate={inView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.6 }}
          className="text-5xl font-bold text-center mb-16 gradient-text"
        >
          Langues
        </motion.h2>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {languages.map((language, index) => (
            <LanguageCard key={language.id} language={language} index={index} inView={inView} />
          ))}
        </div>
      </div>
    </section>
  )
}

function LanguageCard({ language, index, inView }: { language: Language; index: number; inView: boolean }) {
  const isEnglish = language.name === 'Anglais' && language.toeic_url
  
  const cardContent = (
    <>
      <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
      <div className="text-7xl mb-4 transform group-hover:scale-110 transition-transform relative z-10">
        {language.flag_emoji || '🌍'}
      </div>
      <h3 className="text-2xl font-bold text-white mb-3 group-hover:text-blue-400 transition-colors relative z-10">
        {language.name}
      </h3>
      <p className="text-gray-300 text-lg group-hover:text-white transition-colors relative z-10 mb-2">
        {language.level}
      </p>
      {isEnglish && (
        <motion.a
          href={language.toeic_url}
          target="_blank"
          rel="noopener noreferrer"
          whileHover={{ scale: 1.05 }}
          whileTap={{ scale: 0.95 }}
          className="inline-block mt-3 px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg text-white text-sm font-semibold hover:from-blue-600 hover:to-purple-600 transition-all shadow-lg hover:shadow-xl relative z-10"
          onClick={(e) => e.stopPropagation()}
        >
          📋 Voir le score TOEIC
        </motion.a>
      )}
    </>
  )

  if (isEnglish) {
    return (
      <motion.a
        href={language.toeic_url}
        target="_blank"
        rel="noopener noreferrer"
        initial={{ opacity: 0, y: 30 }}
        animate={inView ? { opacity: 1, y: 0 } : {}}
        transition={{ duration: 0.5, delay: index * 0.1 }}
        whileHover={{ scale: 1.08, y: -8, rotateY: 5 }}
        className="bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-sm rounded-2xl p-8 border border-slate-700/50 text-center shadow-2xl hover:shadow-purple-500/30 transition-all group relative overflow-hidden block cursor-pointer"
      >
        {cardContent}
      </motion.a>
    )
  }

  return (
    <motion.div
      initial={{ opacity: 0, y: 30 }}
      animate={inView ? { opacity: 1, y: 0 } : {}}
      transition={{ duration: 0.5, delay: index * 0.1 }}
      whileHover={{ scale: 1.08, y: -8, rotateY: 5 }}
      className="bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-sm rounded-2xl p-8 border border-slate-700/50 text-center shadow-2xl hover:shadow-purple-500/30 transition-all group relative overflow-hidden"
    >
      {cardContent}
    </motion.div>
  )
}


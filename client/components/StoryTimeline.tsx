'use client'

import { motion } from 'framer-motion'
import { useInView } from 'react-intersection-observer'

interface StoryItem {
  id: number
  year: string
  title: string
  description: string
  icon: string
}

function isUploadIcon(icon: string) {
  return icon?.startsWith('/uploads/') || icon?.startsWith('uploads/') || icon?.match(/\.(png|jpg|jpeg|svg)$/i)
}

interface StoryTimelineProps {
  story: StoryItem[]
}

export default function StoryTimeline({ story }: StoryTimelineProps) {
  const items = Array.isArray(story) ? story.slice(0, 4) : []

  if (items.length === 0) {
    return null
  }

  return (
    <section className="py-20 px-4 relative bg-gradient-to-b from-slate-900 via-purple-900/20 to-slate-900">
      <div className="max-w-6xl mx-auto">
        <motion.h2
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="text-5xl font-bold text-center mb-16 gradient-text"
        >
          Mon Histoire
        </motion.h2>

        <div className="relative">
          {/* Timeline line */}
          <div className="absolute left-8 md:left-1/2 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 via-purple-500 to-green-500 transform md:-translate-x-1/2"></div>

          {items.map((item, index) => (
            <StoryItem key={item.id} item={item} index={index} />
          ))}
        </div>
      </div>
    </section>
  )
}

function StoryItem({ item, index }: { item: StoryItem; index: number }) {
  const [ref, inView] = useInView({
    triggerOnce: true,
    threshold: 0.3,
  })

  const isEven = index % 2 === 0

  return (
    <motion.div
      ref={ref}
      initial={{ opacity: 0, x: isEven ? -50 : 50 }}
      animate={inView ? { opacity: 1, x: 0 } : {}}
      transition={{ duration: 0.6, delay: index * 0.2 }}
      className={`relative mb-12 flex items-center ${
        isEven ? 'md:flex-row' : 'md:flex-row-reverse'
      }`}
    >
      {/* Timeline dot */}
      <div className="absolute left-8 md:left-1/2 w-6 h-6 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full border-4 border-slate-900 transform md:-translate-x-1/2 z-10 shadow-lg shadow-blue-500/50"></div>

      {/* Content card */}
      <div
        className={`w-full md:w-5/12 ml-20 md:ml-0 ${
          isEven ? 'md:mr-auto md:pr-8' : 'md:ml-auto md:pl-8'
        }`}
      >
        <motion.div
          whileHover={{ scale: 1.03, y: -5 }}
          className="bg-gradient-to-br from-slate-800/80 to-slate-900/80 backdrop-blur-sm rounded-xl p-6 border border-slate-700/50 shadow-2xl hover:shadow-purple-500/30 transition-all group relative overflow-hidden"
        >
          <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
          <div className="flex items-center gap-4 mb-4">
            {isUploadIcon(item.icon) ? (
              <img
                src={item.icon}
                alt={item.title}
                className="w-12 h-12 object-contain rounded-md bg-white/5 p-1"
                onError={(e) => {
                  const target = e.target as HTMLImageElement
                  target.style.display = 'none'
                }}
              />
            ) : (
              <span className="text-4xl">{item.icon}</span>
            )}
            <div>
              <span className="text-blue-400 font-bold text-lg">{item.year}</span>
              <h3 className="text-2xl font-bold text-white">{item.title}</h3>
            </div>
          </div>
          <p className="text-gray-300 leading-relaxed">{item.description}</p>
        </motion.div>
      </div>
    </motion.div>
  )
}


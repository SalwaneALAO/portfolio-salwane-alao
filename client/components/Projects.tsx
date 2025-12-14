'use client'

import { motion } from 'framer-motion'
import { useInView } from 'react-intersection-observer'

interface Project {
  id: number
  title: string
  description: string
  technologies: string[]
  image: string
}

interface ProjectsProps {
  projects: Project[]
}

export default function Projects({ projects }: ProjectsProps) {
  const [ref, inView] = useInView({
    triggerOnce: true,
    threshold: 0.1,
  })

  if (!projects || projects.length === 0) {
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
          Mes Projets
        </motion.h2>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {projects.map((project, index) => (
            <ProjectCard key={project.id} project={project} index={index} inView={inView} />
          ))}
        </div>
      </div>
    </section>
  )
}

function ProjectCard({ project, index, inView }: { project: Project; index: number; inView: boolean }) {
  return (
    <motion.div
      initial={{ opacity: 0, y: 30 }}
      animate={inView ? { opacity: 1, y: 0 } : {}}
      transition={{ duration: 0.5, delay: index * 0.1 }}
      whileHover={{ y: -10, scale: 1.02 }}
      className="bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-sm rounded-2xl overflow-hidden border border-slate-700/50 shadow-2xl hover:shadow-purple-500/30 transition-all group relative"
    >
      {/* Project image placeholder */}
      <div className="h-48 bg-gradient-to-br from-blue-500/30 via-purple-500/30 to-green-500/30 relative overflow-hidden group-hover:from-blue-500/40 group-hover:via-purple-500/40 group-hover:to-green-500/40 transition-all">
        <div className="absolute inset-0 flex items-center justify-center">
          <div className="text-6xl opacity-40 group-hover:opacity-60 group-hover:scale-110 transition-all">📊</div>
        </div>
        <div className="absolute inset-0 bg-gradient-to-t from-slate-900/90 to-transparent"></div>
        <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
      </div>

      <div className="p-6 relative z-10">
        <h3 className="text-2xl font-bold text-white mb-3 group-hover:text-blue-400 transition-colors">
          {project.title}
        </h3>
        <p className="text-gray-300 mb-5 leading-relaxed group-hover:text-gray-200 transition-colors">
          {project.description}
        </p>
        
        <div className="flex flex-wrap gap-2">
          {project.technologies.map((tech) => (
            <span
              key={tech}
              className="px-3 py-1.5 bg-gradient-to-r from-blue-500/20 to-purple-500/20 text-blue-300 rounded-lg text-sm border border-blue-500/40 group-hover:border-blue-400/60 group-hover:from-blue-500/30 group-hover:to-purple-500/30 transition-all font-medium"
            >
              {tech}
            </span>
          ))}
        </div>
      </div>
    </motion.div>
  )
}


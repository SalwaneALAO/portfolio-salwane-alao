'use client'

import { motion } from 'framer-motion'
import { useInView } from 'react-intersection-observer'

export default function Contact() {
  const [ref, inView] = useInView({
    triggerOnce: true,
    threshold: 0.1,
  })

  return (
    <section ref={ref} className="py-20 px-4 bg-gradient-to-b from-slate-900 to-black">
      <div className="max-w-4xl mx-auto text-center">
        <motion.h2
          initial={{ opacity: 0, y: 20 }}
          animate={inView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.6 }}
          className="text-5xl font-bold mb-8 gradient-text"
        >
          Travaillons Ensemble
        </motion.h2>
        <motion.p
          initial={{ opacity: 0, y: 20 }}
          animate={inView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.6, delay: 0.2 }}
          className="text-xl text-gray-300 mb-12"
        >
          Vous avez un projet de data analysis ? Discutons-en !
        </motion.p>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={inView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.6, delay: 0.4 }}
          className="flex flex-wrap justify-center gap-6"
        >
          <motion.a
            href="mailto:ayomidesalwane@gmail.com"
            whileHover={{ scale: 1.1 }}
            whileTap={{ scale: 0.95 }}
            className="px-8 py-4 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg text-white font-semibold text-lg shadow-lg hover:shadow-xl hover:shadow-purple-500/50 transition-all"
          >
            📧 Me Contacter
          </motion.a>
          <motion.a
            href="https://www.linkedin.com/in/salwane-alao/"
            target="_blank"
            rel="noopener noreferrer"
            whileHover={{ scale: 1.1 }}
            whileTap={{ scale: 0.95 }}
            className="px-8 py-4 bg-slate-800 border border-slate-700 rounded-lg text-white font-semibold text-lg hover:bg-slate-700 transition-all"
          >
            💼 LinkedIn
          </motion.a>
        </motion.div>

        <motion.div
          initial={{ opacity: 0 }}
          animate={inView ? { opacity: 1 } : {}}
          transition={{ duration: 0.6, delay: 0.6 }}
          className="mt-16 pt-8 border-t border-slate-700"
        >
          <p className="text-gray-400">
            © {new Date().getFullYear()} Portfolio Data Analyst. Tous droits réservés.
          </p>
        </motion.div>
      </div>
    </section>
  )
}


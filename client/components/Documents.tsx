'use client'

import { motion } from 'framer-motion'
import { useInView } from 'react-intersection-observer'

interface Document {
  id: number
  type: 'cv' | 'diploma' | 'certification'
  title: string
  file_path?: string
  file_url?: string
  description?: string
}

interface DocumentsProps {
  documents: Document[]
}

export default function Documents({ documents }: DocumentsProps) {
  const [ref, inView] = useInView({
    triggerOnce: true,
    threshold: 0.1,
  })

  if (!documents || documents.length === 0) {
    return null
  }

  const getIcon = (type: string) => {
    switch (type) {
      case 'cv':
        return '📄'
      case 'diploma':
        return '🎓'
      case 'certification':
        return '🏆'
      default:
        return '📎'
    }
  }

  const getTypeLabel = (type: string) => {
    switch (type) {
      case 'cv':
        return 'CV'
      case 'diploma':
        return 'Diplôme'
      case 'certification':
        return 'Certification'
      default:
        return 'Document'
    }
  }

  const handleDownload = async (doc: Document) => {
    if (doc.file_url) {
      // Ouvrir l'URL externe directement
      window.open(doc.file_url, '_blank')
    } else if (doc.file_path) {
      // Pour Next.js, les fichiers dans public/ sont servis directement depuis la racine
      // /uploads/file.pdf devient accessible via /uploads/file.pdf
      let filePath = doc.file_path.trim()
      
      // Nettoyer le chemin
      if (filePath.startsWith('http://localhost') || filePath.startsWith('http://127.0.0.1')) {
        filePath = filePath.replace(/^https?:\/\/[^\/]+/, '')
      }
      if (!filePath.startsWith('/')) {
        filePath = '/' + filePath
      }
      
      // Construire l'URL complète
      const fullUrl = window.location.origin + filePath
      
      console.log('Tentative d\'ouverture:', {
        originalPath: doc.file_path,
        cleanedPath: filePath,
        fullUrl: fullUrl
      })
      
      // Ouvrir directement (Next.js devrait servir depuis public/)
      // Si ça ne marche pas, c'est que le serveur Next.js ne sert pas les fichiers statiques
      const newWindow = window.open(fullUrl, '_blank')
      
      // Si la fenêtre n'a pas pu s'ouvrir ou si elle affiche une erreur 404
      if (!newWindow || newWindow.closed) {
        // Essayer avec une requête fetch pour voir l'erreur
        try {
          const response = await fetch(fullUrl, { method: 'HEAD' })
          if (!response.ok) {
            console.error('Erreur HTTP:', response.status, response.statusText)
            alert(`Le fichier n'a pas pu être trouvé (${response.status}).\nChemin: ${filePath}\n\nAssurez-vous que le serveur Next.js est démarré et que les fichiers sont dans client/public/uploads/`)
          }
        } catch (error) {
          console.error('Erreur lors de la vérification:', error)
          alert(`Impossible d'accéder au fichier.\nChemin: ${filePath}\n\nVérifiez que le serveur Next.js est démarré.`)
        }
      }
    } else {
      alert('Le fichier n\'est pas encore disponible. Veuillez contacter pour plus d\'informations.')
    }
  }

  // Séparer les documents par type
  const documentsList = documents.filter(doc => doc.type === 'cv' || doc.type === 'diploma')
  const certifications = documents.filter(doc => doc.type === 'certification')

  return (
    <section ref={ref} className="py-20 px-4 bg-slate-900/50">
      <div className="max-w-6xl mx-auto">
        <motion.h2
          initial={{ opacity: 0, y: 20 }}
          animate={inView ? { opacity: 1, y: 0 } : {}}
          transition={{ duration: 0.6 }}
          className="text-5xl font-bold text-center mb-16 gradient-text"
        >
          Documents & Certifications
        </motion.h2>

        {/* Documents (CV et Diplôme) */}
        {documentsList.length > 0 && (
          <div className="mb-16">
            <motion.h3
              initial={{ opacity: 0, y: 20 }}
              animate={inView ? { opacity: 1, y: 0 } : {}}
              transition={{ duration: 0.6, delay: 0.2 }}
              className="text-3xl font-bold text-center mb-8 text-white"
            >
              Documents
            </motion.h3>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
              {documentsList.map((doc, index) => (
                <DocumentCard key={doc.id} document={doc} index={index} inView={inView} getIcon={getIcon} getTypeLabel={getTypeLabel} handleDownload={handleDownload} />
              ))}
            </div>
          </div>
        )}

        {/* Certifications */}
        {certifications.length > 0 && (
          <div>
            <motion.h3
              initial={{ opacity: 0, y: 20 }}
              animate={inView ? { opacity: 1, y: 0 } : {}}
              transition={{ duration: 0.6, delay: 0.4 }}
              className="text-3xl font-bold text-center mb-8 text-white"
            >
              Certifications
            </motion.h3>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              {certifications.map((doc, index) => (
                <DocumentCard key={doc.id} document={doc} index={index + documentsList.length} inView={inView} getIcon={getIcon} getTypeLabel={getTypeLabel} handleDownload={handleDownload} />
              ))}
            </div>
          </div>
        )}
      </div>
    </section>
  )
}

function DocumentCard({ 
  document, 
  index, 
  inView, 
  getIcon, 
  getTypeLabel, 
  handleDownload 
}: { 
  document: Document
  index: number
  inView: boolean
  getIcon: (type: string) => string
  getTypeLabel: (type: string) => string
  handleDownload: (doc: Document) => void
}) {
  const isCertification = document.type === 'certification'
  
  return (
    <motion.div
      initial={{ opacity: 0, y: 30 }}
      animate={inView ? { opacity: 1, y: 0 } : {}}
      transition={{ duration: 0.5, delay: index * 0.05 }}
      whileHover={{ scale: 1.05, y: -5 }}
      className={`bg-slate-800/50 backdrop-blur-sm rounded-lg ${isCertification ? 'p-4' : 'p-6'} border border-slate-700 shadow-xl hover:shadow-2xl hover:shadow-purple-500/20 transition-all ${isCertification ? 'text-center flex flex-col' : ''}`}
    >
      <div className={`${isCertification ? 'text-5xl' : 'text-6xl'} mb-4 ${isCertification ? '' : 'text-center'}`}>{getIcon(document.type)}</div>
      <div className="mb-2">
        <span className="text-xs text-blue-400 font-semibold uppercase tracking-wide">
          {getTypeLabel(document.type)}
        </span>
      </div>
      <h3 className={`${isCertification ? 'text-lg' : 'text-xl'} font-bold text-white mb-3 ${isCertification ? 'text-center' : ''}`}>{document.title}</h3>
      {document.description && (
        <p className={`text-gray-400 ${isCertification ? 'text-xs' : 'text-sm'} mb-4 ${isCertification ? 'text-center flex-grow' : ''}`}>{document.description}</p>
      )}
      <div className="mt-auto">
        {(document.file_url || document.file_path) ? (
          <motion.button
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            onClick={() => handleDownload(document)}
            className="w-full px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg text-white font-semibold hover:from-blue-600 hover:to-purple-600 transition-all"
          >
            {document.type === 'cv' ? '📥 Télécharger' : document.type === 'diploma' ? '👁️ Voir' : '🔗 Voir la certification'}
          </motion.button>
        ) : (
          <div className="text-gray-500 text-sm text-center italic">
            {isCertification ? 'Certification en cours' : 'Document à venir'}
          </div>
        )}
      </div>
    </motion.div>
  )
}


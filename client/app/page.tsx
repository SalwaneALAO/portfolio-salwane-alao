'use client'

import { useEffect, useState } from 'react'
import Hero from '@/components/Hero'
import StoryTimeline from '@/components/StoryTimeline'
import Skills from '@/components/Skills'
import Languages from '@/components/Languages'
import Qualities from '@/components/Qualities'
import Projects from '@/components/Projects'
import Stats from '@/components/Stats'
import Documents from '@/components/Documents'
import Contact from '@/components/Contact'
import ScrollProgress from '@/components/ScrollProgress'

export default function Home() {
  const [portfolioData, setPortfolioData] = useState<any>(null)
  const [isLoading, setIsLoading] = useState(true)

  useEffect(() => {
    let isMounted = true
    setIsLoading(true)
    
    fetch('/api/portfolio')
      .then(res => {
        if (!res.ok) {
          throw new Error(`HTTP error! status: ${res.status}`)
        }
        return res.json()
      })
      .then(data => {
        console.log('Portfolio data loaded:', data)
        console.log('Skills count:', data.skills?.length || 0)
        console.log('Languages count:', data.languages?.length || 0)
        console.log('Qualities count:', data.qualities?.length || 0)
        
        // Vérifier si l'API a retourné une erreur
        if (data.error) {
          console.error('API Error:', data.error)
        }
        
        // S'assurer que toutes les propriétés existent et sont des tableaux
        const portfolio = {
          hero: data.hero || {
            name: 'SALWANE ALAO',
            title: 'Data Analyst & Data Scientist',
            subtitle: 'Data Visualisation & Big Data',
            description: 'En recherche active d\'un CDI ou CDD',
            profile_picture: '/images/profile-picture.png'
          },
          story: Array.isArray(data.story) ? data.story : [],
          skills: Array.isArray(data.skills) ? data.skills : [],
          languages: Array.isArray(data.languages) ? data.languages : [],
          qualities: Array.isArray(data.qualities) ? data.qualities : [],
          projects: Array.isArray(data.projects) ? data.projects : [],
          stats: Array.isArray(data.stats) ? data.stats : [],
          documents: Array.isArray(data.documents) ? data.documents : []
        }
        // #region agent log
        fetch('http://127.0.0.1:7242/ingest/9a64805e-8576-4f68-bfae-5a55f797667f', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            sessionId: 'debug-session',
            runId: 'run-frontend',
            hypothesisId: 'H-frontend',
            location: 'client/app/page.tsx:portfolio-success',
            message: 'Portfolio fetch success',
            data: {
              skills: portfolio.skills.length,
              languages: portfolio.languages.length,
              qualities: portfolio.qualities.length,
              projects: portfolio.projects.length,
              stats: portfolio.stats.length,
              documents: portfolio.documents.length,
              heroProfile: portfolio.hero?.profile_picture || null
            },
            timestamp: Date.now()
          })
        }).catch(() => {})
        // #endregion

        console.log('Setting portfolio data:', portfolio)
        if (isMounted) {
          setPortfolioData(portfolio)
          setIsLoading(false)
        }
      })
      .catch(err => {
        console.error('Error fetching portfolio data:', err)
        // Données par défaut en cas d'erreur réseau
        if (isMounted) {
          setPortfolioData({
            hero: {
              name: 'SALWANE ALAO',
              title: 'Data Analyst & Data Scientist',
              subtitle: 'Data Visualisation & Big Data',
              description: 'En recherche active d\'un CDI ou CDD',
              profile_picture: '/images/profile-picture.png'
            },
            story: [],
            skills: [],
            languages: [],
            qualities: [],
            projects: [],
            stats: [],
            documents: []
          })
          setIsLoading(false)
        }
        // #region agent log
        fetch('http://127.0.0.1:7242/ingest/9a64805e-8576-4f68-bfae-5a55f797667f', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            sessionId: 'debug-session',
            runId: 'run-frontend',
            hypothesisId: 'H-frontend',
            location: 'client/app/page.tsx:portfolio-error',
            message: 'Portfolio fetch error',
            data: { error: err?.message || 'unknown' },
            timestamp: Date.now()
          })
        }).catch(() => {})
        // #endregion
      })
    
    return () => {
      isMounted = false
    }
  }, [])

  if (isLoading || !portfolioData) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
        <div className="text-white text-xl">Chargement...</div>
      </div>
    )
  }

  return (
    <main className="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
      <ScrollProgress />
      {portfolioData.hero && <Hero data={portfolioData.hero} />}
      {portfolioData.story && portfolioData.story.length > 0 && <StoryTimeline story={portfolioData.story} />}
      {portfolioData.skills && portfolioData.skills.length > 0 && <Skills skills={portfolioData.skills} />}
      {portfolioData.languages && portfolioData.languages.length > 0 && <Languages languages={portfolioData.languages} />}
      {portfolioData.qualities && portfolioData.qualities.length > 0 && <Qualities qualities={portfolioData.qualities} />}
      {portfolioData.stats && portfolioData.stats.length > 0 && <Stats stats={portfolioData.stats} />}
      {portfolioData.projects && portfolioData.projects.length > 0 && <Projects projects={portfolioData.projects} />}
      {portfolioData.documents && portfolioData.documents.length > 0 && <Documents documents={portfolioData.documents} />}
      <Contact />
    </main>
  )
}


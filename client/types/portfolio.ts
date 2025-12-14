export interface HeroData {
  name: string
  title: string
  subtitle: string
  description: string
}

export interface StoryItem {
  id: number
  year: string
  title: string
  description: string
  icon: string
}

export interface Skill {
  name: string
  level: number
  category: string
}

export interface Project {
  id: number
  title: string
  description: string
  technologies: string[]
  image: string
}

export interface Stat {
  label: string
  value: number
  icon: string
}

export interface PortfolioData {
  hero: HeroData
  story: StoryItem[]
  skills: Skill[]
  projects: Project[]
  stats: Stat[]
}



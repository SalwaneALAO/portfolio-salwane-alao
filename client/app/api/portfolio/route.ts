import { NextResponse } from 'next/server';
import { query } from '@/lib/db';

export async function GET() {
  try {
    // Récupérer toutes les données du portfolio
    const portfolio: any = {};
    
    // Hero
    const hero = await query("SELECT * FROM hero LIMIT 1");
    portfolio.hero = hero[0] || {
      name: 'SALWANE ALAO',
      title: 'Data Analyst & Data Scientist',
      subtitle: 'Data Visualisation & Big Data | En recherche active d\'un CDI ou CDD',
      description: 'Passionné par l\'analyse de données et la visualisation, je transforme les informations brutes en décisions éclairées. Alternant chez GRDF depuis 2023, j\'ai contribué à améliorer la fiabilité des données de 25% et à accélérer la prise de décision stratégique.',
      profile_picture: '/images/profile-picture.png'
    };
    
    // Story
    portfolio.story = await query("SELECT * FROM story ORDER BY display_order ASC, year ASC");
    
    // Skills
    portfolio.skills = await query("SELECT * FROM skills ORDER BY category, name");
    
    // Projects
    const projects = await query("SELECT * FROM projects ORDER BY id");
    // Convertir les technologies en tableau pour les projets
    portfolio.projects = projects.map((project: any) => {
      if (project.technologies && typeof project.technologies === 'string') {
        try {
          project.technologies = JSON.parse(project.technologies);
        } catch {
          project.technologies = [];
        }
      }
      return project;
    });
    
    // Stats
    portfolio.stats = await query("SELECT * FROM stats ORDER BY id");
    
    // Languages
    const languages = await query("SELECT * FROM languages ORDER BY id");
    // S'assurer que toeic_url est inclus même si la colonne n'existe pas encore
    portfolio.languages = languages.map((lang: any) => ({
      ...lang,
      toeic_url: lang.toeic_url || null
    }));
    
    // Documents
    portfolio.documents = await query("SELECT * FROM documents ORDER BY type, id");
    
    // Qualities
    portfolio.qualities = await query("SELECT * FROM qualities ORDER BY id");
    
    return NextResponse.json(portfolio, {
      status: 200,
      headers: {
        'Content-Type': 'application/json; charset=utf-8',
        'Access-Control-Allow-Origin': '*',
      },
    });
    
  } catch (error: any) {
    console.error('Portfolio API error:', error);
    
    // En cas d'erreur, retourner une structure valide avec des données par défaut
    const defaultData = {
      hero: {
        name: 'SALWANE ALAO',
        title: 'Data Analyst & Data Scientist',
        subtitle: 'Data Visualisation & Big Data | En recherche active d\'un CDI ou CDD',
        description: 'Passionné par l\'analyse de données et la visualisation, je transforme les informations brutes en décisions éclairées. Alternant chez GRDF depuis 2023, j\'ai contribué à améliorer la fiabilité des données de 25% et à accélérer la prise de décision stratégique.',
        profile_picture: '/images/profile-picture.png'
      },
      story: [
        { id: 1, year: '2020', title: 'Baccalauréat Scientifique', description: 'Obtention du Baccalauréat Scientifique à Cotonou, Bénin.', icon: '🎓', display_order: 1 },
        { id: 2, year: '2022-2025', title: 'ESIGELEC - BIG DATA', description: 'Formation d\'ingénieur en Génie Électrique spécialité BIG DATA.', icon: '🚀', display_order: 2 },
        { id: 3, year: '2023-2025', title: 'Alternance GRDF', description: 'Data Analyst/Data Scientist/BI Analyst chez GRDF.', icon: '⚡', display_order: 3 }
      ],
      skills: [
        { id: 1, name: 'Python', level: 90, category: 'Langages', logo_url: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg' },
        { id: 2, name: 'SQL', level: 85, category: 'Langages', logo_url: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg' },
        { id: 3, name: 'Power BI', level: 92, category: 'Visualisation', logo_url: 'https://powerbi.microsoft.com/pictures/application-logos/svg/powerbi.svg' }
      ],
      projects: [
        { id: 1, title: 'Datawarehouse GRDF', description: 'Conception et développement d\'un Datawarehouse intégrant des données historiques.', technologies: ['Python', 'Power BI', 'SQL'], image: '/api/placeholder/600/400' }
      ],
      stats: [
        { id: 1, label: 'Projets Réalisés', value: 4, icon: '📊' },
        { id: 2, label: 'Années d\'Expérience', value: 4, icon: '⏱️' },
        { id: 3, label: 'Entreprises', value: 3, icon: '🏢' },
        { id: 4, label: 'Certifications', value: 8, icon: '🎓' }
      ],
      languages: [
        { id: 1, name: 'Français', level: 'Langue maternelle', flag_emoji: '🇫🇷' },
        { id: 2, name: 'Anglais', level: 'B2 - Professionnel (TOEIC)', flag_emoji: '🇬🇧', toeic_url: null },
        { id: 3, name: 'Espagnol', level: 'Intermédiaire', flag_emoji: '🇪🇸' }
      ],
      qualities: [
        { id: 1, name: 'Esprit coopératif', icon: '🤝' },
        { id: 2, name: 'Autonome', icon: '🎯' },
        { id: 3, name: 'Dynamique', icon: '⚡' },
        { id: 4, name: 'Analyse stratégique', icon: '🧠' }
      ],
      documents: [],
      error: error?.message || 'Unknown error',
      warning: 'Données par défaut - Vérifiez la connexion à la base de données'
    };
    
    return NextResponse.json(defaultData, {
      status: 200,
      headers: {
        'Content-Type': 'application/json; charset=utf-8',
        'Access-Control-Allow-Origin': '*',
      },
    });
  }
}


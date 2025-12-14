/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  async rewrites() {
    // En production, utiliser l'URL de l'API backend
    // En développement, utiliser localhost
    const apiUrl = process.env.NODE_ENV === 'production' 
      ? process.env.API_URL || 'https://votre-backend.herokuapp.com/Portfolio/server/api'
      : 'http://localhost/Portfolio/server/api';
    
    return [
      {
        source: '/api/:path*',
        destination: `${apiUrl}/:path*`,
      },
    ];
  },
}

module.exports = nextConfig

